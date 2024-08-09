# Use the official PHP 8.2 Apache image as a base
FROM php:8.2-apache

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libldap2-dev \
    libpq-dev \
    && docker-php-ext-install ldap pdo_mysql mysqli

# Copy the application files into the container
COPY ./www /var/www/html

# Set the working directory
WORKDIR /var/www/html
