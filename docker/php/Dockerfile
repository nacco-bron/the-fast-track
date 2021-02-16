ARG PHP_VERSION

FROM php:${PHP_VERSION}-cli

# php-extensions: 
RUN apt-get update  \
  && apt-get install -y \
  wget \
  git \
  libzip-dev \
  libpq-dev \
  curl \
  libcurl4 \
  libcurl4-openssl-dev \
  libxslt-dev \
  && docker-php-ext-install \
  pdo \
  zip \
  pdo_pgsql \
  curl \
  xsl

# php-extension: gd
RUN apt-get update \
  && apt-get install -y \
  libfreetype6-dev \
  libpng-dev \
  libjpeg-dev \
  && docker-php-ext-configure gd \
  && docker-php-ext-install -j$(nproc) gd \
  && apt-get purge -y \
  libfreetype6-dev \
  libpng-dev \
  libjpeg-dev

# php-extension: intl
RUN apt-get update \
  && apt-get install -y \
  libicu-dev \
  && docker-php-ext-install -j$(nproc) \
  intl \
  && apt-get purge -y \
  libicu-dev

# php-extension: redis
RUN docker-php-ext-install \
  bcmath \
  sockets \
  && pecl update-channels \
  && pecl install redis \
  && docker-php-ext-enable redis

# php-extension: amqp php8.0対応のためmaster branchのソースからインストール
RUN apt-get update \
  && apt-get install -y \
  librabbitmq-dev \
  libssh-dev \
  && curl -sSLf https://codeload.github.com/php-amqp/php-amqp/tar.gz/df1241852b359cf12c346beaa68de202257efdf1 | tar xz \
  && cd php-amqp-df1241852b359cf12c346beaa68de202257efdf1 \
  && phpize \
  && ./configure \
  && make -j$(nproc) \
  && make install \
  && cd .. \
  && rm -rf php-amqp-df1241852b359cf12c346beaa68de202257efdf1 \
  && docker-php-ext-enable amqp \
  && apt-get purge -y \
  librabbitmq-dev \
  libssh-dev

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

ARG APP_NAME

USER root

RUN mkdir /usr/src/${APP_NAME}

RUN export COMPOSER_PROCESS_TIMEOUT=5000

WORKDIR /usr/src/${APP_NAME}
