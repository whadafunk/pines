# GLPI implementation in Docker

This repo proposes a method to run glpi using a container architecture, and explains some of the challenges and solutions of this process.

**First of all, GLPI app has a couple of dependencies:**

- SQL Database -> implemented here through mariadb
- WebServer with PHP -> implemented here through httpd 
  with php on alpine (also one of my containers called routerology/phpine)
- A cron process -> implemented through standard cron process 
  running in the same container with httpd

As hinted in the last requirement, we will have a multiprocess container that needs to run httpd and cron, and as you will see this container will be started with the "--init" option and a custom entrypoint script

**I have included in this repo the following resources:**

- Dockerfile for building glpi_app container
- Files used in the build stage, under build-files:
	- httpd.conf
	- php.ini
	- cron scripts
	- entrypoint script
	- glpi files downstream and local_define
- Container startup files (provided as an example to get you going)
	- start_glpi
	- start_glpi_nopersist
	- start_mariadb
	- compose_nopersist.yaml
	- compose_persist.yaml
- Container persistency directory structure (also as an example under startup-files)
	- glpi_var
	- glpi_config
	- glpi_plugins
	- glpi_marketplace
	- server config files (php.ini and httpd.conf)

### Database details

As said, we need a database for this glpi app to work, so the database container is provided here through mariadb, which is started with these credentials:

- root password: pass123
- custom database: somebase
- custom database owner: somedude
- custom database secret: somesecret

You can of course change this to whatever you like or even implement them as secrets.

We could provide a configured database config file, but I want a clean glpi instance withouth any preconfigured stuff, 
so when you first start the container you will have to go through the glpi setup process  
which will produce the db\_config file.

Under startup-environment you have scripts to start individual containers:
	- start_glpi_persistent
	- start_glpi_nopersistent
	- start_mariadb

But you can do the same with compose scripts, also available under startup-environment:
	- compose_persist.yaml
	- compose_nopersist.yaml

### A short summary of GLPI process:

The special goal of this project is to have a container that is easy to setup with persistency.  
In order to have a custom directory layout we need to leverage two files: **downstream.php** and **local_define.php**.  
The **downstream.php** file specifies the location of the config directory (in our case */etc/glpi*), and needs to be placed under */var/www/glpi/inc/*  
The **local_define.php** file, specifies further the location of files and logs directories.  
Having said that, we provide out of the box two custom downstream and local_define files in a temp location, and these two files will be copied in the 
final location by the entrypoint script. We do this, in order to be able to populate any persistent runtime mounted storage (like /etc/glpi for example).

The GLPI app has a couple of requirements from the https/php server, and most of them have been already satisfied by the base layer image **routerology/phpine**,
but there is also a requirement regarding the DocumentRoot of the apache, which needs to be **/var/www/glpi/public**.  
Having such a DocumentRoot, there needs to be some rewrite directives to direct request towards top folder /var/www/glpi, 
and you will see that in the provided custom httpd.conf file:

- redirect all request to the GLPI router (thats an index.php file under public)
- ensure authorization headers are passed to php

Here is the directory structure I use with this container:

- /var/www/glpi -> the root directory of glpi
- /var/www/glpi/public -> apache DocumentRoot (required by installation procedure)
- /var/www/glpi/inc/downstream.php -> glpi custom config file (defines the /etc/glpi and the locali\_define file)
- /etc/glpi -> glpi config directory (includes custom local\_define)
- /var/lib/glpi -> the files folder from glpi (glpi\_var, defined in local\_define); this is basically the user created data, and a folder that needs to be preserved between upgrades
- /var/log/glpi -> glpi log files (defined in local\_define)

* I basicaly mode the files and logs directories out of the official structure, then modify local_define to point to those

### Container initialization script

The container has an entrypoint script which does the following:

- moves glpi/files folder to a dedicated /var/lib/glpi (glpi\_var) directory
- overwrites the original downstream.php with the one from the container image
- starts the cronie process
- starts the apache process





