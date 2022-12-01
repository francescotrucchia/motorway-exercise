FROM php:8.1-cli as php

ENV TZ UTC

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list

RUN apt-get update && \
    apt-get install -y git procps libicu-dev && \
    docker-php-ext-install bcmath intl

RUN echo "Using GID $GROUP_ID and UID $USER_ID" && \
    mkdir /motorway && \
    groupadd -g $GROUP_ID motorway && \
    useradd -m -s /bin/bash -g $GROUP_ID -u $USER_ID motorway && \
    chown motorway:motorway /motorway && \
    chown motorway:motorway -R /usr/local/etc/ && \
    chown motorway:motorway -R /home/motorway

RUN cd /motorway && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

CMD cd /motorway && php -S 0.0.0.0:8080 -t public public/index.php

WORKDIR /motorway

