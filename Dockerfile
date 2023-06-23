FROM php:8.1-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
#    mysql-client \
    locales \
    git \
    unzip \
    zip \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql exif pcntl

# Install nginx
RUN apt-get install -y nginx
RUN rm /etc/nginx/sites-available/default
COPY docker/nginx/conf.d /etc/nginx/sites-available/default

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

COPY docker/php/local.ini       /usr/local/etc/php/conf.d

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD composer install && /bin/sh -c php-fpm
