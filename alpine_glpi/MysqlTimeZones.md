On most systems, you’ll have to initialize timezones data from your system’s timezones:

mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -p -u root mysql



GRANT SELECT ON `mysql`.`time_zone_name` TO 'glpi'@'localhost';

vim /etc/mysql/my.cnf

(the path depends on the operating system) and add the default time zone to [mysqld]:

default-time-zone = 'Europe/Madrid'
