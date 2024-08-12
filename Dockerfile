FROM php:8.2-apache

# Install XDebug extension
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libldap2-dev \
    libpq-dev \
    netcat-openbsd \
    ldap-utils \
    default-mysql-client \
    nano \
    && docker-php-ext-install ldap pdo_mysql mysqli

# Create the PHP session directory and set appropriate permissions
RUN mkdir -p /var/lib/php/sessions && chown -R www-data:www-data /var/lib/php/sessions

# Copy the application files into the container
COPY ./www /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Set permissions for the /var/www/html directory
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html

# Copy the php.ini file to the appropriate location
COPY ./www/php.ini /usr/local/etc/php/

# Copy the import script into the container
COPY ./www/import-ldif.sh /usr/local/bin/import-ldif.sh

# Set the script as executable
RUN chmod +x /usr/local/bin/import-ldif.sh
