# Use an official PHP image as the base image
FROM php:8.0-apache

# Install PHP extensions for MySQL support
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Update package lists and upgrade installed packages
RUN apt-get update && apt-get upgrade -y && rm -rf /var/lib/apt/lists/*

# Install dependencies required for Composer
RUN apt-get update && apt-get install -y \
    curl \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy your PHP application files into the container
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Expose port 8000 to the outside world
EXPOSE 8000

# Command to run your PHP application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
