# alpine container image with some nice improvements
FROM alpine:latest
MAINTAINER gri.daniel@gmail.com
LABEL my_name="Daniel Grigore"
LABEL my_email="gri.daniel@gmail.com"

COPY Readme.md /usr/src/app/README.md

# Setting up a nicer prompt
ENV PS1='\n\e[32m\e[1m\u@\h\e[0m \e[94m\w \e[32m\e[1m$\e[0m\e[0m\e[39m\e[49m '

# I usually mount these files persistent, but I added them here to have them at least as a template
COPY log/admin/journal /var/log/admin/journal
COPY log/admin/summary /var/log/admin/summary

# Set the timezone
ENV TZ="Europe/Bucharest"

# An alternative to setting timezone with environment variables would be the following
# RUN echo "Europe/Bucharest" > /etc/timzone

# Install a couple of basic tools
RUN 	apk update && \
	apk add \
		vim\
		procps-ng \
		shadow\
		ca-certificates\
		wget\
		bash\
		sudo\
		&& rm -rf /var/cache/apk/*

# Copy an edited sudoers file, with uncommented wheel group line
COPY sudoers /etc/sudoers

# If you have custom ca certificates, place them under ca_certs directory
COPY ca_certs/*.crt /usr/local/share/ca-certificates
RUN update-ca-certificates

# You might want to have some special profile settings
COPY profile /etc/profile

# It is a good practice to not use the container as root
RUN 	adduser --disabled-password -g admin -G wheel -h /home/admin -s /bin/sh admin && \
	echo "admin:pass123" | chpasswd && \
	addgroup admin wheel

USER admin

# This is the default from the base image, but I have it here just for clarity
CMD ["/bin/sh"]

