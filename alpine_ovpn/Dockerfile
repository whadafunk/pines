# OpenVPN Alpine-Based Container
FROM alpine:latest
LABEL maintainer="gri.daniel@gmail.com"
LABEL my_name="Daniel Grigore"
LABEL my_email="gri.daniel@gmail.com"

COPY Readme.md /usr/src/app/README.md

# Setting up a nicer prompt
ENV PS1='\n\e[32m\e[1m\u@\h\e[0m \e[94m\w \e[32m\e[1m$\e[0m\e[0m\e[39m\e[49m '

# I usually mount these files persistent, but I added them here to have them at least as a template
#COPY log/admin/journal /var/log/admin/journal
#COPY log/admin/summary /var/log/admin/summary

# Set the timezone
ENV TZ="Europe/Bucharest"

# An alternative to setting timezone with environment variables would be the following
# RUN echo "Europe/Bucharest" > /etc/timzone

# Install our packages
RUN 	apk update && \
	apk add \
		sudo \
		bash \
		tar \
		easy-rsa \
		ca-certificates \
		openvpn \
		openvpn-auth-pam \
		openvpn-auth-ldap \
		openvpn-auth-script \
		openvpn-doc \
		openvpn-openrc \
		&& rm -rf /var/cache/apk/*

# If you have custom ca certificates, place them under ca_certs directory
COPY ca_certs/*.crt /usr/local/share/ca-certificates
RUN update-ca-certificates

# Set a default config mode
ENV ovp_mode="server-tun"

# Configure automatic loading of the module for universal tun/tap device driver
RUN echo "tun" >> /etc/modules-load.d/tun.conf
# Configure packet forwarding
RUN echo "net.ipv4.ip_forward = 1" >> /etc/sysctl.d/ipv4.conf

# Copy the openvpn stuff
COPY pki /root/openvpn/pki
COPY ccd /root/openvpn/ccd
COPY config /root/openvpn/config
COPY scripts /root/openvpn/scripts
COPY entrypoint.sh /root/entrypoint.sh



# It is a good practice to not use the container as root
RUN	adduser --disabled-password -g admin -G wheel -h /home/admin -s /bin/sh admin && \
	adduser --disabled-password -g ovpn -h /home/ovpn -s /bin/sh ovpn && \
	echo "admin:pass123" | chpasswd 

RUN chown -R ovpn:ovpn /etc/openvpn

# Copy the configuration files

#USER admin

WORKDIR /etc/openvpn

EXPOSE 1194/udp
EXPOSE 1194/tcp
EXPOSE 10194/udp


# This is the default from the base image, but I have it here just for clarity
ENTRYPOINT ["/bin/bash","/root/entrypoint.sh"]
