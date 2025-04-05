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
	--mount type=bind,source=$(pwd)/logs_server,target=/var/log/ \
	routerology/ovpn "--status /var/logs_server/ovpn.status"
