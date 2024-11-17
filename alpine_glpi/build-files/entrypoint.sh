#!/bin/bash

# These script is needed for two reasons:

# 1. copy the files that can be potentially overriden by persistent mounts to their final location: downstream.php, local_define.php, files, marketplace and plugins directories
# 2. starts 2 processes inside the container: apache and cron


# Moving the files directory to its final location, but first test if we are not overwriting something, like mounted persistent storage

	if [ -n "$( ls -A '/var/lib/glpi' )" ]; then
		cd /var/www/glpi/files
		tar cvf - --remove-files * | tar xvf - -C /var/lib/glpi
		echo "files folder moved to /var/lib/glpi"
	fi

# The marketplace and plugins directories are initialy empty so if we mount them as persistent with empty (for an initial installation),
# we don't need to populate them

# When container was built, we copied inside it custom downstream and local_define files, inside a temp location.
# We are moving those two files now, to their final location, testing for overwriting conditions

		if [ -f /var/www/glpi/downstream.php ] && [ ! -f /etc/glpi/inc/downstream.php ]; then
			mv /var/www/glpi/downstream.php /var/www/glpi/inc/
			echo "downstream.php moved to /var/www/glpi/inc"
		fi

		if [ -f /var/www/glpi/local_define.php ] && [ ! -f /etc/glpi/local_define.php ]; then
			mv /var/www/glpi/local_define.php /etc/glpi/local_define.php
			echo "local_define.php moved to /etc/glpi/"
		fi

	touch /var/www/glpi/glpi_installed
  
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
 
