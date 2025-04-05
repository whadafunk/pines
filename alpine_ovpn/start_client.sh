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
	--mount type=bind,source=$(pwd)/log_client,target=/var/log/ \
	ovpn_test "--remote ovpn_server 1194 udp" "--data-ciphers-fallback BF-CBC"
#	routerology/ovpn:latest
	

