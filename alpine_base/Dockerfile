# alpine container image with some nice improvements
FROM alpine:latest
MAINTAINER gri.daniel@gmail.com
LABEL my_name="Daniel Grigore"
LABEL my_email="gri.daniel@gmail.com"


# Setting up a nicer prompt
ENV PS1='\n\e[32m\e[1m\u@\h\e[0m \e[94m\w \e[32m\e[1m#\e[0m\e[0m\e[39m\e[49m '

# Install a couple of basic tools
RUN 	apk update && \
	apk add vim shadow ca-certificates wget bash && \
	rm -rf /var/cache/apk/*

# If you have custom ca certificates, place them under ca_certs directory
COPY ca_certs/*.crt /usr/local/share/ca-certificates
RUN update-ca-certificates

# You might want to have some special profile settings
COPY profile /etc/profile

# Just in case you need a custom user
RUN 	adduser --disabled-password -g admin -G wheel -h /home/admin -s /bin/sh admin && \
	echo "admin:pass123" | chpasswd

# This is the default from the base image, but I have it here just for clarity
CMD ["/bin/sh"]

