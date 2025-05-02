#!/bin/bash
# We need NET_ADMIN in for the tun/tap interface creation inside the container

docker container run -it \
	--detach \
	--rm \
	--name ovpn_server \
	--hostname ovpn_server \
	--user root \
	--cap-add=NET_ADMIN \
	--env ovp_mode="server-tun" \
	--publish 1194:1194/udp \
	--mount type=bind,source=$(pwd)/logs_server,target=/var/log/ \
	routerology/ovpn "--status /var/log/ovpn.status"
