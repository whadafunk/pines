# Alpine Base

### Just added a couple of small improvements to the official alpine image

The dockerfile is commented and simple enough to understand just by reading it

The container runs as admin user which is created inside the container with password admin123
The admin user inside the container has UID 1000 which is usualy the first user created in the system, so there's a good chance
that your local user on the docker host has also UID 1000. The GID is that of wheel, which is 10.

See the included start script for an example of how I run this image

[**GitHub Repository**](https://github.com/whadafunk/alpines.git)

This file is included in the container at */usr/src/app/README.md*, but it will only work with automated build for which I don't have funds.

