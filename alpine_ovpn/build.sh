#!/bin/bash

docker buildx build --push -t routerology/ovpn:2.6-1 --platform linux/amd64,linux/arm64 .
docker buildx build --push -t routerology/ovpn:latest --platform linux/amd64,linux/arm64 .
