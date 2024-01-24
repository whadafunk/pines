# Bastillion Docker Implementation


### A simple bastillion container based on Alpine

[ Bastillion Product Page ](https://www.bastillion.io/)

The dockerfile is just preparing the container for installation, by installing all needed prerequisites, 
creating an user to run the service, and copying the actual installation script entrypoint.sh

The actual installation is done at container runtime. I did it this way because I want the installed files to be in a persistent mounted directory, 
together with the database and all the configurations.
Included in the repository is also a start script which maybe put things in a more clear perspective.




