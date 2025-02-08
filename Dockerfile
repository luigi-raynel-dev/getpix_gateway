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

# Instalar extensões MongoDB, Kafka, Swoole e Redis
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install rdkafka && docker-php-ext-enable rdkafka
RUN pecl install swoole --configure-options="--enable-brotli=no" && docker-php-ext-enable swoole
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar a aplicação
COPY html /var/www/html

# Expor a porta da aplicação
EXPOSE 9501

CMD ["php", "bin/hyperf.php", "start"]
