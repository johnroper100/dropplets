FROM php:7.4-apache

RUN a2enmod rewrite ssl proxy proxy_http headers

RUN apt-get update && apt-get install -y \
git \
bash \
curl \
unzip \
vim