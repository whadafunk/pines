#!/bin/bash
# We need NET_ADMIN in for the tun/tap interface creation inside the container

docker container run -it \
	--detach \
	--name ovpn \
	--hostname ovpn \
	--user root \
	--cap-add=NET_ADMIN \
	routerology/ovpn:latest
	

