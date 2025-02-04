# Usar imagem oficial do PHP 8
FROM php:8.2-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  librdkafka-dev \
  libbrotli-dev \
  && docker-php-ext-install pdo mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar extensões MongoDB, Kafka e Swoole'''
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install rdkafka && docker-php-ext-enable rdkafka
RUN pecl install swoole --configure-options="--enable-brotli=no" && docker-php-ext-enable swoole

# Definir diretório de trabalho
WORKDIR /var/www/html
