#!/bin/bash

docker container run -it \
	--detach \
	--rm \
	--name ovpn \
	--hostname ovpn \
	--user root \
	--mount type=bind,source=$(pwd)/log/admin,destination=/var/log/admin \
	routerology/ovpn:latest
	

#--mount type=bind,source=$(pwd)/tls,destination=/etc/openvpn/tls \
