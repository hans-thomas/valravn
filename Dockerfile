FROM php:8.1-fpm

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd gmp intl bcmath zip pdo_mysql redis pcntl xdebug exif

# Install Supervisor & Ping & Nano
RUN apt-get update && apt-get install -y \
    git \
    supervisor \
    iputils-ping \
    nano \
    unzip

# Install Composer
RUN curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# add aliases
RUN echo 'alias chownw="chown -R www-data:www-data "' >> ~/.bashrc

CMD ["php-fpm"]

EXPOSE 9000
