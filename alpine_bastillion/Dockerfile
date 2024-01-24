FROM alpine:latest

RUN 	apk update && \
	apk add \
	openjdk21-jdk \
	bash \
	shadow \
	&& rm -rf /var/cache/apk/*


RUN useradd -c "Bastillion User" -d /home/buser -m -u 1000 -s /bin/bash buser && \
	chown -R buser:buser /opt && \
	chmod -R 3770 /opt 
USER buser
RUN 	set JAVA_HOME=  && \
	set PATH=$JAVA_HOME/bin:$PATH

WORKDIR /opt

EXPOSE 8443

COPY ./entrypoint.sh /home/buser/entrypoint.sh

ENTRYPOINT ["/home/buser/entrypoint.sh"]




	 
	 
