#!/bin/bash
# We need NET_ADMIN in for the tun/tap interface creation inside the container

docker container run -it \
	--rm \
	--detach \
	--name ovpn_client \
	--hostname ovpn_client \
	--user root \
	--cap-add=NET_ADMIN \
	--env ovp_mode="client-tun" \
	--env client_pass="1234" \
	--mount type=bind,source=$(pwd)/logs_client,target=/var/log/ \
	routerology/ovpn "--remote 172.17.0.3 1194 udp" "--askpass -"  "--data-ciphers-fallback BF-CBC"
	

