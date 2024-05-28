#!/bin/bash -X

### Starting OpenVPN in a container poses a couple of challenges.
### First of all the container needs the NET_ADMIN capability, to be able to create the tun/tap interface
### Even with the NET_ADMIN, it won't be able to create the tun/tap if it can't find a device file /dev/net/tun
### There are two options arround /dev/net/tun
### 1. start the container with --device /dev/net/tun
### 2. create the /dev/net/tun inside the container with an entrypoint.sh script.
### The tun module will be automatically loaded in the host kernel by the kmod mechanism



mkdir -p /dev/net
mknod /dev/net/tun c 10 200
chmod 600 /dev/net/tun
/usr/sbin/openvpn --config /etc/openvpn/openvpn-server-tun.conf
