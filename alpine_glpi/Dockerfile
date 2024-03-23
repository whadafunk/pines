FROM routerology/phpine:latest
MAINTAINER gri.daniel@gmail.com
LABEL my_name="daniel grigore"
LABEL my_youtube="https://www.youtube.com/@routerologyblog1111"

RUN apk update && \
	apk add \
	bash \
	tzdata \
	wget \
	tar \
	&& rm -rf /var/lib/apt/lists/*

ENV TZ="Europe/Bucharest"

RUN mkdir /home/apache && \
	mkdir /etc/glpi && \
	mkdir /var/log/glpi && \
	mkdir /var/lib/glpi && \
	mkdir /var/www/glpi

RUN chown -R apache:apache /var/log/glpi && \
	chown -R apache:apache /var/lib/glpi && \
	chown -R apache:apache /home/apache && \
	chown -R apache:apache /etc/glpi && \
	chown -R apache:apache /var/www/glpi

COPY ./httpd_conf/httpd.conf /etc/apache2/httpd.conf
COPY ./php_ini/php.ini	etc/php82/php.ini
COPY ./downstream /var/www/glpi/downstream.php
COPY ./local_define.php /etc/glpi/local_define.php

COPY ./wrapper_script.sh /root/wrapper_script.sh
COPY cron/php /etc/cron.d
COPY cron/glpi /home/apache/
RUN chown apache:apache /home/apache/glpi

RUN crontab -u apache /home/apache/glpi
WORKDIR /var/www/

ENTRYPOINT ["/bin/bash","/root/wrapper_script.sh"]


#ADD https://github.com/glpi-project/glpi/releases/download/10.0.11/glpi-10.0.11.tgz /var/www/
#RUN tar xzvf glpi-10.0.11.tgz
#RUN rm -f /var/www/glpi-10.0.11.tgz
