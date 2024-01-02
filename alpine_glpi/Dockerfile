FROM routerology/phpine:latest
MAINTAINER gri.daniel@gmail.com
LABEL my_name="daniel grigore"
LABEL my_youtube="https://www.youtube.com/@routerologyblog1111"

RUN apk update && \
	apk add \
	bash \
	tzdata \
	&& rm -rf /var/lib/apt/lists/*

ENV TZ="Europe/Bucharest"

RUN mkdir /home/apache

COPY ./wrapper_script.sh /root/wrapper_script.sh
COPY cron/php /etc/cron.d
COPY cron/glpi /home/apache/
RUN chown apache:apache /home/apache/glpi

RUN crontab -u apache /home/apache/glpi
ADD https://github.com/glpi-project/glpi/releases/download/10.0.11/glpi-10.0.11.tgz /var/www/
WORKDIR /var/www/
RUN tar xzvf glpi-10.0.11.tgz
RUN rm -f /var/www/glpi-10.0.11.tgz

ENTRYPOINT ["/bin/bash","/root/wrapper_script.sh"]

