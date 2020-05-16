# get started

# create database
    CREATE DATABASE `stocks` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    CREATE USER 'stocks'@'%' IDENTIFIED BY '1234';
    GRANT ALL PRIVILEGES ON `stocks`.* TO 'stocks'@'%';
    FLUSH PRIVILEGES;

    php artisan migrate

    php artisan db:seed


