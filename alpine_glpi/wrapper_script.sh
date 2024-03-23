#!/bin/bash

# Download the glpi archive, unpack it and move the files content to a separate folder

if ! [ -f /var/www/glpi/glpi_installed ]; then
	cd /var/www
	wget https://github.com/glpi-project/glpi/releases/download/10.0.14/glpi-10.0.14.tgz
	tar xvf glpi-10.0.14.tgz
	rm -f glpi-10.0.14.tgz
	cd /var/www/glpi/files
	tar cvf - --remove-files * | tar xvf - -C /var/lib/glpi

	# we are using custom downstream and local_define from inside the container
	# here we are overwriting the original files of the archive
		if [ -f /var/www/glpi/downstream.php ]; then
			mv /var/www/glpi/downstream.php /var/www/glpi/inc/
		fi
	touch /var/www/glpi/glpi_installed
fi
  
# Start the cron process
/usr/sbin/crond -f &
cron_pid=$!
echo "PID of crond is $cron_pid"

# Start the httpd process
httpd -DFOREGROUND &
httpd_pid=$!
echo "PID of httpd is $httpd_pid"
  
# Wait for any process to exit
wait -n $cron_pid $httpd_pid
  

# Exit with status of process that exited first
exit $?
 
