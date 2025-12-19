<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


```bash
# Build
docker compose up -d --build

# Laravel command

docker exec -it laravel_app composer install

docker exec -it laravel_app php artisan migrate

docker exec -it laravel_app php artisan config:clear

docker exec -it laravel_app php artisan cache:clear

docker exec -it laravel_app php artisan cache:clear


# Fake data (Seeder)
docker exec -it laravel_app php artisan make:seeder DevSeeder

# NPM

docker exec -it laravel_app npm install

docker exec -it laravel_app npm run build
```


```bash
# Generate SEO. You can add this command to cron
docker exec -it laravel_app php artisan sitemap:generate
```


# Replica Mysql
```bash
REPLICA NYSQL

docker exec -it mysql_primary mysql -uroot -proot
# Check GTID
SHOW VARIABLES LIKE 'gtid_mode'; # gtid_mode | ON
SHOW VARIABLES LIKE 'gtid_mode'; # ON

CREATE USER 'repl'@'%' IDENTIFIED WITH mysql_native_password BY 'replpass';
GRANT REPLICATION SLAVE, REPLICATION CLIENT ON *.* TO 'repl'@'%';
FLUSH PRIVILEGES;

docker exec -it mysql_replica mysql -uroot -proot
CHANGE REPLICATION SOURCE TO
  SOURCE_HOST='mysql_primary',
  SOURCE_USER='repl',
  SOURCE_PASSWORD='replpass',
  SOURCE_PORT=3306,
  SOURCE_AUTO_POSITION=1;

START REPLICA;

# Without GTID
# SHOW MASTER STATUS;
# GET Binlog coordinate example:
# FILE - mysql-bin.000001  POSITION - 157
 
# CHANGE REPLICATION SOURCE TO
#   SOURCE_HOST='mysql_primary',
#   SOURCE_USER='repl',
#   SOURCE_PASSWORD='replpass',
#   SOURCE_LOG_FILE='mysql-bin.000003',
#   SOURCE_LOG_POS=94641;

# CHECK
SHOW REPLICA STATUS\G
#Replica_IO_Running: Yes
#Replica_SQL_Running: Yes
#Seconds_Behind_Source: 0
```

## Replica check and prove

```bash
# Prove that read -> replica
# First Easy prove
docker exec -it mysql_replica mysql -uroot -proot
SHOW VARIABLES LIKE 'read_only';
#read_only | ON
SHOW VARIABLES LIKE 'super_read_only';
# super_read_only | ON
# IF not :
#SET GLOBAL super_read_only = ON;
#SET GLOBAL read_only = ON;

# Prove by test
docker exec -it mysql_replica mysql -uroot -proot
SET GLOBAL general_log = 'ON';
SET GLOBAL log_output = 'TABLE';

# In laravel tinker:
User::where('email', 'replica@test.com')->first();

# Check in replica
SELECT event_time, argument
FROM mysql.general_log
ORDER BY event_time DESC
LIMIT 5;
# If you see the entry, reading is indeed going to the ‘replica’ database.

----------------------------------------------------------

# Checking sticky
# Laravel tinker:
User::create(['name' => 'Sticky Test', 'email' => 'sticky@test.com','password' => bcrypt\('secret'\)]);
User::where('email', 'sticky@test.com')->first();

# Because of sticky = true, SELECT will go to PRIMARY, not to the replica.
# Checking SELECT in PRIMARY

SET GLOBAL general_log = 'ON';
SET GLOBAL log_output = 'TABLE';

SELECT event_time, argument
FROM mysql.general_log
ORDER BY event_time DESC
LIMIT 5;

# You can see SELECT → sticky is working.
---------------------------------------------------------------

# Be sure to turn off the logs. primary and replica
SET GLOBAL general_log = 'OFF';
```

# ProxySQL
#### Step 1 - Register MYSQL servers in PROXYSQL
```bash
docker exec -it proxysql mysql -uadmin -padmin -hlocalhost -P6032 --ssl=0
```
```mysql
# proxysql Container
SHOW DATABASES; # main,disk,stats,monitor,

INSERT INTO mysql_servers (hostgroup_id, hostname, port)
VALUES (10, 'mysql_primary', 3306);

INSERT INTO mysql_servers (hostgroup_id, hostname, port)
VALUES
  (20, 'mysql_replica', 3306),
  (20, 'mysql_replica_2', 3306);


LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;

# check
SELECT hostgroup_id, hostname, status
FROM runtime_mysql_servers;



```


### Step 2 - Say PROXYSQL, Where WRITER / READER
```mysql
INSERT INTO mysql_replication_hostgroups
    (writer_hostgroup, reader_hostgroup)
VALUES
    (10, 20);

LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;

```

### Step 3 - User for ProxySQL
```mysql
# primary mysql container
CREATE USER 'proxysql'@'%' IDENTIFIED WITH mysql_native_password BY 'proxypass';
GRANT ALL PRIVILEGES ON laravel.* TO 'proxysql'@'%';
FLUSH PRIVILEGES;


# proxysql container
INSERT INTO mysql_users
    (username, password, default_hostgroup)
VALUES
    ('proxysql', 'proxypass', 10);

LOAD MYSQL USERS TO RUNTIME;
SAVE MYSQL USERS TO DISK;
```


### Step 4 - Setting READ / WRITE SPLIT

```mysql
# ProxySql conatiner
# All Select query -> Replicas(20)
INSERT INTO mysql_query_rules
  (rule_id, active, match_pattern, destination_hostgroup)
VALUES
  (1, 1, '^SELECT', 20);

# Other query -> Primary(10)
INSERT INTO mysql_query_rules
    (rule_id, active, match_pattern, destination_hostgroup)
VALUES
    (2, 1, '.*', 10);


LOAD MYSQL QUERY RULES TO RUNTIME;
SAVE MYSQL QUERY RULES TO DISK;

```

### Step 5 - Check in ProxySql
```mysql
SELECT rule_id, match_pattern, destination_hostgroup
FROM runtime_mysql_query_rules;
# 1 | ^SELECT | 20
# 2 | .*      | 10

```


### Step 6 - .env Laravel
```mysql
DB_CONNECTION=mysql
DB_HOST=proxysql
DB_PORT=6033
DB_DATABASE=laravel
DB_USERNAME=proxysql
DB_PASSWORD=proxypass


docker exec -it laravel_app php artisan config:clear
docker exec -it laravel_app php artisan cache:clear

App\Models\User::create(['name' => 'Proxy Insert','email' => 'proxy-insert@test.com','password' => bcrypt('secret'),]);

# Если ошибка (Max connect timeout reached while reaching hostgroup 10 after 10000ms), проверяем в proxySql
SELECT hostgroup_id, hostname, status FROM runtime_mysql_servers;

#+--------------+-----------------+--------+
#| hostgroup_id | hostname        | status |
#+--------------+-----------------+--------+
#| 20           | mysql_primary   | ONLINE |
#| 20           | mysql_replica   | ONLINE |
#| 20           | mysql_replica_2 | ONLINE |
#+--------------+-----------------+--------+
# Если mysql_primary в hostgroup_id 20 то делаем следующее
DELETE FROM mysql_replication_hostgroups;
LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;

UPDATE mysql_servers
SET hostgroup_id = 10
WHERE hostname = 'mysql_primary';

UPDATE mysql_servers
SET hostgroup_id = 20
WHERE hostname IN ('mysql_replica', 'mysql_replica_2');

LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;

SELECT hostgroup_id, hostname, status
FROM runtime_mysql_servers;
# Должно выдать так
#+--------------+-----------------+--------+
#| hostgroup_id | hostname        | status |
#+--------------+-----------------+--------+
#| 10           | mysql_primary   | ONLINE |
#| 20           | mysql_replica   | ONLINE |
#| 20           | mysql_replica_2 | ONLINE |
#+--------------+-----------------+--------+


# Ошибка с Variable 'sql_mode' can't be set to the value of 'NO_AUTO_CREATE_USER'
# На mysql_primary, mysql_replica, mysql_replica_2
SET GLOBAL sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
```

### Step 7 - Check routing
```mysql
docker exec -it laravel_app php artisan tinker
User::create([
    'name' => 'Routing Test',
    'email' => 'routing@test.com',
    'password' => bcrypt('secret'),
]);
            
# ProxySQL 
SELECT hostgroup, sum(count_star) AS total
FROM stats_mysql_query_digest
WHERE digest_text LIKE 'INSERT%'
GROUP BY hostgroup;
# Insert list exists - DONE

# tinker
User::where('email', 'routing@test.com')
    ->update(['name' => 'Routing Test Updated']);

# ProxySQL
SELECT hostgroup, sum(count_star) AS total
FROM stats_mysql_query_digest
WHERE digest_text LIKE 'UPDATE%'
GROUP BY hostgroup;
# Update list exists - DONE


# tinker
User::where('email', 'routing@test.com')->first(); # 3-5 time run

# ProxySQL
SELECT hostgroup, sum(count_star) AS total
FROM stats_mysql_query_digest
WHERE digest_text LIKE 'SELECT%'
GROUP BY hostgroup;
# Select list exists - DONE

```
