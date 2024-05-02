FROM php:alpine

RUN apk add --no-cache bash git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

COPY . /var/www/html

WORKDIR /var/www/html

EXPOSE 8000

CMD ["symfony", "serve"]