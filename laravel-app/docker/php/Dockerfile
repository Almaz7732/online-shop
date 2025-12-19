FROM php:8.2-fpm


ARG UID=1000
ARG GID=1000


# install system deps
RUN apt-get update && apt-get install -y \
git \
unzip \
libzip-dev \
libonig-dev \
libpng-dev \
libfreetype6-dev \
libjpeg62-turbo-dev \
libxml2-dev \
libicu-dev \
libssl-dev \
pkg-config \
librabbitmq-dev \
zlib1g-dev \
&& docker-php-ext-configure gd --with-jpeg --with-freetype \
&& docker-php-ext-install -j$(nproc) gd mbstring zip pdo pdo_mysql bcmath intl sockets xml


# pecl extensions (amqp)
RUN pecl install amqp && docker-php-ext-enable amqp

# redis
RUN pecl install redis && docker-php-ext-enable redis

# NODE AND NPM
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@10.8.2

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# create app directory
WORKDIR /var/www/html


# permissions
RUN useradd -G www-data,root -u $UID -m appuser


# copy entrypoint
#COPY ./docker/php/docker-entrypoint.sh /usr/local/bin/
#RUN chmod +x /usr/local/bin/docker-entrypoint.sh
#
#
#ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
