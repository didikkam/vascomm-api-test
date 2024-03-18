
# DIDIKAM VASCOMM TEST

## How To Install
Install PHP
```
PHP >= 8.0
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
```
Install Semua Dependensi
```
composer install
```
Copy .env.example menjadi .env

sesuaikan konfigurasinya
```
cp .env.example .env
```
Lakukan migrasi
```
php artisan migrate
php artisan db:seed
```