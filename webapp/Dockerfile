# syntax=docker/dockerfile:1

# ---- assets: build the Vite bundle (Tailwind + app.css) -------------------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# ---- app: PHP + Apache, serves the actual Laravel app ----------------------
FROM php:8.3-apache AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev libzip-dev libicu-dev libonig-dev libcurl4-openssl-dev unzip git curl \
    && docker-php-ext-install pdo_pgsql pgsql zip intl opcache mbstring curl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Installed via script rather than COPY --from=composer:2 — pulling that
# extra image hung indefinitely resolving its metadata through Render's
# Docker mirror on the first deploy attempt.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Dependencies first so they're cached unless composer.json/lock changes.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-autoloader --prefer-dist

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
