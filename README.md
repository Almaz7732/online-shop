<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200">
</a>
</p>
<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://go.dev/blog/go-brand/Go-Logo/PNG/Go-Logo_LightBlue.png" width="200"></a>
</p>


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

Read more about how to use replica [REPLICA_MYSQL.md](REPLICA_MYSQL.md)

