#!/bin/bash

cd /opt
if [ ! -e /opt/Bastillion-jetty  ]; then
	wget https://github.com/bastillion-io/Bastillion/releases/download/v3.15.00/bastillion-jetty-v3.15_00.tar.gz 
	gzip -dc bastillion-jetty-v3.15_00.tar.gz | tar xvf - 
fi

cd /opt/Bastillion-jetty

(echo $DB_PASSWORD; echo $DB_PASSWORD) | ./startBastillion.sh
