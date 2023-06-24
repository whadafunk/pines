#!/bin/bash
# We need NET_ADMIN in for the tun/tap interface creation inside the container

docker container run -it \
	--detach \
	--rm \
	--name ovpn \
	--hostname ovpn \
	--user root \
	--cap-add=NET_ADMIN \
	--mount type=bind,source=$(pwd)/log/admin,destination=/var/log/admin \
	--mount type=bind,source=/lib/modules,destination=/lib/modules \
	routerology/ovpn:latest /bin/sh
	

#--mount type=bind,source=$(pwd)/tls,destination=/etc/openvpn/tls \
