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
CREATE USER 'repl'@'%' IDENTIFIED WITH mysql_native_password BY 'replpass';
GRANT REPLICATION SLAVE ON *.* TO 'repl'@'%';
FLUSH PRIVILEGES;
SHOW MASTER STATUS;
# GET Binlog coordinate example:
# FILE - mysql-bin.000001  POSITION - 157

docker exec -it mysql_replica mysql -uroot -proot
CHANGE REPLICATION SOURCE TO SOURCE_HOST='mysql_primary', SOURCE_USER='repl',SOURCE_PASSWORD='replpass', SOURCE_LOG_FILE='mysql-bin.000001', SOURCE_LOG_POS=157;
START REPLICA;

# CHECK
SHOW REPLICA STATUS\G
#
#Replica_IO_Running: Yes
#Replica_SQL_Running: Yes
```

## Replica check and prove

```bash
# Prove that read -> replica
# First Easy prove
docker exec -it mysql_replica mysql -uroot -proot
SHOW VARIABLES LIKE 'read_only';
#read_only | ON

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
