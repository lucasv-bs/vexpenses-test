FROM php:8.1-apache

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt update && apt install -y \
    git \
    curl \
    libpq-dev \
    libpng-dev \    
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Apache config
# Enable the rewrite module
RUN a2enmod rewrite

# Chage the apache document root to laravel entrypoint
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Get latest composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Add the application and set the working directory
COPY ./ /var/www/html/
WORKDIR /var/www/html

USER $user