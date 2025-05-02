# OpenVPN Notes

This repo started out as a project to help me better understand OpenVPN and have a hands-on environment where I can test various features.
So, to summarize in a bullet list what you might find in here:

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

## OpenVPN Container Architecture and Features

We start with a vanilla Alpine Linux container on top of which I add some basic utility packages:

- bash
- tar
- easyrsa

We also add the openVPN packages including pam and ldap authentication support.

There is a directory and file structure that we propose for this container, and this can be seen starting with the Dockerfile
which copies these directories inside the container. We are doing this copy from the start because we want to provide a starting point for the container.


- /etc/openvpn/pki (includes certificates and keys for server, client and tls authentication and encryption)
- /etc/openvpn/ccd (includes client configuration scripts)
- /etc/openvpn/configs (includes configuration templates for server and client)
- /etc/openvpn/scripts (includes scripts for up,down,connect and maybe other triggers)

Apart from these mentioned directories we also address the logging path /var/log/ovpn.log, which of course can be mounted persistently so 
it can be accessed outside of the container.

Let's see the contents of the PKI folder

- ca.crt
- crl.pem
- dh.pem
- tls.key
- server.crt
- server.key
- client.crt
- client.key

These are just a base to get you started. You can of course use easyrsa and create your own certificates, but it should be a good practice if you
are using the same naming and mount your pki folder persistently. This way the certificate and key names from the config templates will continue to work.

Another thing to mention and think about is the encryption of the private keys. For the server key there is no encryption so the server starts without asking for such password. 
But for the client, I have used a password, which is 1234. The way that we pass this password to the starting client is by using the config parameter --askpass  

## Container runtime parameters

We have a couple of mechanisms to pass runtime parameters. 
One such important runtime parameter would be the name of the config-file name 
which can be specified with the environment variable ovp_mode.

Here is a complete list of environment variables that we can use:


- ovp_mode { server_tun | server_tap | server_tun_p2p | client_tun | client_tap | peer  
- config_file
- remote *remote_server_ip* *port* *proto*
- ifconfig-pool
- data-ciphers
- tun-mtu
- fragment
- mssfix
- ca *file_path*
- capath *dir_path*
- cert *file_path* - certificate
- key *file_path*- private key
- keysecret *password* - the password of the private key

Apart from the above environment variables we can use the same param names as runtime container flags. 
I initialy started only with ovp_mode environment variable and the remote runtime flag, and I think these two are the most important, 
but all the above params seem like good candidates to improve the functionality of the container.

If you specify config file or any other parameter that has as argument a file name, you need to think also how to make that file available
inside of the container, and apart from mount the file at runtime or have the file already copied inside the container, 
I don't think there are other options.

## TODO List

- Review Dockerfile; add published ports
- Implement management interface
- Implement scripts (up,down,connect); experiment, produce templates
- gitignore and dockerignore
- brainstorm methods to specify certificate and key at runtime.
- document start scripts: specify config file, mount persistent storage, specify pki files


