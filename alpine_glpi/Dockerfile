FROM routerology/phpine:latest
LABEL my_name="daniel grigore"
LABEL my_youtube="https://www.youtube.com/@routerologyblog1111"
ENV GLPIVERSION="10.0.17"

# Installing prerequisite tools

RUN apk update && \
	apk add \
	bash \
	tzdata \
	wget \
	tar \
	&& rm -rf /var/lib/apt/lists/*

# Create the directory structure 

RUN mkdir /home/apache && \
	mkdir /etc/glpi && \
	mkdir /var/log/glpi && \
	mkdir /var/lib/glpi && \
	mkdir /var/www/glpi

# Download and extract GLPI files, from their github repository

WORKDIR /var/www/
RUN wget https://github.com/glpi-project/glpi/releases/download/${GLPIVERSION}/glpi-${GLPIVERSION}.tgz && \
	tar xvf glpi-${GLPIVERSION}.tgz && \
	rm -f glpi-${GLPIVERSION}.tgz && \
	cd /var/www/glpi/files && \
	tar cvf - * | tar xvf - -C /var/lib/glpi

# downstream.php is indicating the config folder (/etc/glpi) which can be mounted persistent at runtime
# local_define.php is indicating the log and files directories, which also are subject to persistent mount 
# We are copying downstream.php and local_define.php to a temporary location; 
# They will be copied to their final destination by the entrypoint script.
# I am building the container with these two preconfigured files, because I want to be sure that even if I don't mount them as persistent storage,
# the container will still run with the proposed directory structure. 
# Also, I want these two files to be copied in their final location by the entrypoint script, after the persistent storage is already mounted

COPY ./build-files/downstream.php /var/www/glpi/downstream.php
COPY ./build-files/local_define.php /var/www/glpi/local_define.php

# Copy server configuration files. This can be also mounted persistent if needed; most likely you don't need to

COPY ./build-files/httpd.conf	/etc/apache2/httpd.conf
COPY ./build-files/php.ini	etc/${PHPVERSION}/php.ini

RUN chown -R apache:apache /var/log/glpi && \
	chown -R apache:apache /var/lib/glpi && \
	chown -R apache:apache /home/apache && \
	chown -R apache:apache /etc/glpi && \
	chown -R apache:apache /var/www/glpi

# Copy and install cron scripts; glpi is run with apache user
COPY ./build-files/cron/php /etc/cron.d
COPY ./build-files/cron/glpi /home/apache/
RUN chown apache:apache /home/apache/glpi
RUN crontab -u apache /home/apache/glpi

COPY ./build-files/entrypoint.sh /root/entrypoint.sh

ENV TZ="Europe/Bucharest"

ENTRYPOINT ["/bin/bash","/root/entrypoint.sh"]


