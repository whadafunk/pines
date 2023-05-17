#!/bin/sh

# I do not currently use this entrypoint script.
# You might want to use such a script if you want to:
# 	- pass the environment variables to cron scripts
#	- run cron as a secondary process


env > /etc/environment

/usr/sbin/crond -f &
/usr/sbin/apache -DFOREGROUND &

wait -n
exit $?
