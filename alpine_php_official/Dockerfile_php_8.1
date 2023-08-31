FROM php:8.2-alpine3.16
MAINTAINER gri.daniel@gmail.com
LABEL my_name="daniel grigore"
LABEL my_youtube="https://www.youtube.com/@routerologyblog1111"

# Apache and PHP on Alpine Linux

# Start with installing some generic tools that can help us with troubleshooting
# We also need to install apache and the apache php module.
# Apparently there is no apache-php container based on alpine. Not on the DockerHub repository at least

RUN apk update && \
	apk add \
	apache2 \
	apache2-utils \
	php81-apache2 \
	&& rm -rf /var/lib/apt/lists/*

# PHP official container from docker comes with a couple of extensions preinstalled
# To add supplementary extensions we can use the docker-php-ext-install and docker-php-ext-configure scripts
# The following listing contains allmost every needed php module and its dependencies, but I have commented them out.

# Before running the scripts that will compile our extensions we need to add the required libraries

RUN 	apk add \
	libc-dev \
	&& docker-php-ext-install bcmath \
	&& apk add libzip-dev bzip2-dev \
	&& docker-php-ext-install bz2 \
	&& docker-php-ext-install zip \
	&& apk add enchant2-dev \
	&& docker-php-ext-install enchant \
	&& docker-php-ext-install dba \
	&& docker-php-ext-install exif \
	&& apk add libpng-dev \
	&& docker-php-ext-install gd \
	&& apk add gmp-dev \
	&& docker-php-ext-install gmp \
	&& docker-php-ext-install gettext \
	&& apk add imap-dev openssl-dev krb5-dev \
	&& docker-php-ext-configure imap --with-imap-ssl --with-kerberos \
	&& docker-php-ext-install imap \
	&& apk add icu-dev \
	&& docker-php-ext-install intl \
	&& apk add openldap-dev \
	&& docker-php-ext-install ldap \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install opcache \
	&& docker-php-ext-install pcntl \
	&& docker-php-ext-install pdo \
	&& docker-php-ext-install pdo_mysql \
	&& apk add aspell-dev \
	&& docker-php-ext-install pspell \
	&& docker-php-ext-install shmop \
	&& apk add libxml2-dev \
	&& docker-php-ext-install simplexml \
	&& docker-php-ext-install xml \
	&& apk add net-snmp-dev \
	&& docker-php-ext-install snmp \
	&& docker-php-ext-install soap \
	&& docker-php-ext-install sockets \
	&& apk add tidyhtml-dev \
	&& docker-php-ext-install tidy \
	&& apk add libxslt-dev \
	&& docker-php-ext-install xsl \
	&& apk add oniguruma-dev \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install sodium \
	&& docker-php-ext-install calendar \
 	&& rm -rf /var/lib/apt/lists/* \
	&&  rm -rf /usr/src/*

# Add the following extra tools only if you need them
# The same goes for the apache2-ssl also

#RUN apk update && \
#	apk add \
#	net-tools \
#	vim \
#	tcpdump \
#	ca-certificates \
#	iproute2 \
#	wget \
#	apache2-ssl \
# 	&& rm -rf /var/lib/apt/lists/* 

# If we are using SSL, the we might need to add our ca to the trusted ssl chain
#COPY ssl/*.crt /usr/local/share/ca-certificates
#RUN update-ca-certificates

# Set the correct timezone

ENV TZ="Europe/Bucharest"

# We can copy the site content straight into the container but that's not a good practice.
# I put this here just for demo purpose
# A better way would be to mount it as a volume at container start
# COPY www/ /var/www/localhost/htdocs

# The configuration files for PHP and HTTPD do not hurt to be copied into the container
# but they can be very well overriden by mounting them at container start

COPY php_ini/php.ini $PHP_INI_DIR/php.ini
COPY httpd_conf/httpd.conf /etc/apache2/httpd.conf

# The PID on this container is root/1, but the httpd workers/subprocesses are started with 100:101
# So if any files are to be created by the app inside, they will have that ownership

EXPOSE 80/tcp

ENTRYPOINT ["httpd","-DFOREGROUND"]

