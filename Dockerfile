FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    postgresql-client \
    curl \
    ca-certificates \
    lsb-release \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html
COPY ./app /var/www/html
RUN npm install bootstrap@5 --save

# using Composer with root rights
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer require symfony/mailgun-mailer

ARG USER_ID=1000
ARG GROUP_ID=1000
RUN groupadd -g ${GROUP_ID} appgroup && \
    useradd -u ${USER_ID} -g appgroup -m appuser && \
    chown -R appuser:appgroup /var/www/html

USER appuser
