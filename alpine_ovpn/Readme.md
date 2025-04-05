# OpenVPN Notes

This repo can be multiple things:

- A summary documentation about what makes OpenVPN tick
- A collection of configuration templates that can be used mostly out-of-the box, and they are enough well commented to be adapted to your needs
- A container that can run either as server, client or peer with the above mentioned config templates
- A hands-on lab that will give you a better understanding about how openVPN works

My research on openVPN covered pretty much everything that is has to offer:

- L2/L3 vpn
- Automated client configuration
- Automation scripts
- Advanced authentication


### Starting OpenVPN in a container poses a couple of challenges.
- First of all the container needs the NET_ADMIN capability, to be able to create the tun/tap interface
- Even with the NET_ADMIN, it won't be able to create the tun/tap if it can't find a device file /dev/net/tun
- There are two options arround /dev/net/tun
- 1. start the container with --device /dev/net/tun
- 2. create the /dev/net/tun inside the container with an entrypoint.sh script.
- The tun module needs to be loaded in the host kernel: it can be done automaticaly by the kmod mechanism, but is safer to load it from modules.conf

