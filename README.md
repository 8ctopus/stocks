# get started

# create database
    CREATE DATABASE `stocks` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    CREATE USER 'stocks'@'%' IDENTIFIED BY '1234';
    GRANT ALL PRIVILEGES ON `stocks`.* TO 'stocks'@'%';
    FLUSH PRIVILEGES;

# create tables and seed them
    php artisan migrate:fresh --seed


