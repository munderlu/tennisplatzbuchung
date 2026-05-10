# Wir nutzen PHP 8.2 mit Apache als Basis
FROM php:8.2-apache

# Wir installieren die PDO MySQL Erweiterung
RUN docker-php-ext-install pdo pdo_mysql

# Optional: Wir aktivieren mod_rewrite für saubere URLs später
RUN a2enmod rewrite

