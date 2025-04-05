#!/bin/bash

### Starting OpenVPN in a container poses a couple of challenges.
### First of all the container needs the NET_ADMIN capability, to be able to create the tun/tap interface
### Even with the NET_ADMIN, it won't be able to create the tun/tap if it can't find a device file /dev/net/tun
### There are two options arround /dev/net/tun
### 1. start the container with --device /dev/net/tun
### 2. create the /dev/net/tun inside the container with an entrypoint.sh script.
### The tun module will be automatically loaded in the host kernel by the kmod mechanism

### Lastly we want to be able to start this container in multiple configurations: client, server, tun/tap, etc"

### The way we will do this, is by using an environment variable
### server-tun
### server-tap
### server-tun-expanded
### server-tap-expanded
### client-tun
### client-tap
### peer

# Our containers packs some specific files and folders that are going to be copied under the persistent mounted storage
# At this point the external persistent storage is already mounted; we can copy the our files as a startup point
# The directories are: pki, ccd, config, scripts
if [ -d /root/openvpn ] && [ ! -d /etc/openvpn/pki ] && [ ! -d /etc/openvpn/ccd ] && [ ! -d /etc/openvpn/config ] && [ ! -d /etc/openvpn/scripts ]; then
	cd /root/openvpn/
	tar cvf - --remove-files * | tar xvf - -C /etc/openvpn
	echo "ovpn working files copied to /etc/openvpn"
else 
	echo "ovpn files were found already under /etc/openvpn; not overwriting"
fi


mkdir -p /dev/net
mknod /dev/net/tun c 10 200
chmod 600 /dev/net/tun
exec /usr/sbin/openvpn --config /etc/openvpn/config/openvpn-${ovp_mode}.conf $@
