# OpenVPN Notes

## This repo contains some documentation about openvpn and a dockerfile to build such a container
## There are a couple of challenges with regards to running openvpn in a container and with openvpn in general, 
## which I hope to document in here.

### Starting OpenVPN in a container poses a couple of challenges.
### First of all the container needs the NET_ADMIN capability, to be able to create the tun/tap interface
### Even with the NET_ADMIN, it won't be able to create the tun/tap if it can't find a device file /dev/net/tun
### There are two options arround /dev/net/tun
### 1. start the container with --device /dev/net/tun
### 2. create the /dev/net/tun inside the container with an entrypoint.sh script.
### The tun module will be automatically loaded in the host kernel by the kmod mechanism

