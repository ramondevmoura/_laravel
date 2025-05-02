FROM php:8.2-fpm

# Instala dependências do sistema e do PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    npm \
    nodejs \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/src

# Copia os arquivos do projeto (opcional, pode ser feito via volume no docker-compose)
# COPY . .

# Expõe a porta do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
