# GLPI implementation in Docker

This is a docker container that runs GLPI 10.x. 
Included in this repo you will also find a docker compose file
that starts both the glpi container and the database instance (mariadb)

The database details (host, dbname and credentials) can be seen in the compose file, but here they are again:

- glpi_db
- somebase
- somedude
- somesecret

We could provide a configured database, but I want a clean glpi instance withouth any preconfigured stuff, 
so when you first start the container you will have to go through the glpi setup process  
which will produce the db\_config file.

The container can be run as it is without any customization or persistence, but if you want a persistent container
you should have a look at the startup scripts, or at the compose files provided here in the repo. 

There are two compose files. One runs the container with persistence, the other without.
If you want to run the container with persistence, you should use the directory structure proposed here: 


- /var/www/glpi -> the root directory of glpi
- /var/www/glpi/public -> apache DocumentRoot (required by installation procedure)
- /var/www/glpi/inc/downstream.php -> glpi custom config file (defines the /etc/glpi and the locali\_define file)
- /etc/glpi -> glpi config directory (includes custom local\_define)
- /var/lib/glpi -> the files folder from glpi (glpi\_var, defined in local\_define); this is basically the user created data, and a folder that needs to be preserved between upgrades
- /var/log/glpi -> glpi log files (defined in local\_define)


The container has an entrypoint script which does the following:

- downloads and unpacks the glpi.tar.gz archive under /var/www
- moves glpi/files folder to a dedicated /var/lib/glpi (glpi\_var) directory
- overwrites the original downstream.php with the one from the container image
- starts the cronie process
- starts the apache process


After you run the container with persistent /var/www/glpi, there will be a filei called glpi\_installed, which
will be checked by the entrypoint script in order to skip installing again the product.

#### Compose networking

The compose files contain a comlete custom network called glpi\_net, which is not used, as the containers
started from compose will connect to the default compose network.  
If you want you can modify yourself the compose files and add the glpi\_net to the containers.


#### File volume mount situation

I have noticed an interesting behaviour with volumes mounted to docker container.

1. mount a directory like /a:/b
2. mount a file like /file:/b/file
3. at this point the /file can be seen inside the container in /b/file
4. outside, you would expect to see /a/file but instead you will see an empty file



