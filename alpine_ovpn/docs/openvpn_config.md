# OpenVPN Configuration

Before anything, the official documentation pages of OpenVPN are very helpful, especially the referrence manual

- [OpenVPN Referrence Manual 2.4](https://openvpn.net/community-resources/reference-manual-for-openvpn-2-4/)
- [OpenVPN Referrence Manual 2.6](https://openvpn.net/community-resources/reference-manual-for-openvpn-2-6/)

## Overview of mode and topology

When dealing with openvpn, one important thing you need to think of, are the various modes in which you can run it, which can be **server**, **client** and **peer**. 
Traditionally OpenVPN was running only in peer mode for **site2site** tunnels, but later versions added the server mode, which basically means that one openvpn process,
 can accept multiple connections from clients, and has some possibilities to manage those clients (ip assignment, authentication, routing, pushing other config options). 
This is legitimate from network transport layer perspective because there can be multiple sessions on a destination port, as long as the source is different.  
There is a config parameter called **mode** which sets openvpn operation mode to server or p2p. *I think (not sure) that p2p works for both client and peer roles.* 
Also very important to think about the topology, which basically states how the local tun interface will be configured; this topology can also be pushed by the server,  
but is usually included in the server directive together with ifconfig and ifconfig-pool.

There are three topologies:

- **net30** (deprecated)
- **p2p** (configures local and peer address on point to point interface; not available for windows)
- **subnet** (configures normal broadcast interface address; compatible with all OS)

Also, very important to remember that the topology needs to be the same on both peers of the tunnel, and in server client scenarios,  
Most of the times we do not think of this aspect, because is custommary to automaticaly push the topology from the server to the client.

Let's see a couple of points for each of the openVPN roles:

### Server Role

- **mode** needs to be server
- **tls-mode** (allthough can be otherways), it makes sense to use **tls-mode = server**
- the ip address pool to be assigned to clients
- the pushed options (dns, dhcp-options, routes, default-gateway,topology)
- client-config-dir;  this can be used to set options for both server and client (like pushed options or ifconfig-push)
- normally the **pool** and **ifconfig-push**(if needed), are configured automatically when using the **server** helper directive
- configure authentication scripts or plugins

### Client Role

- mode needs to be p2p; better leave it to default
- tls-mode makes sense to be client
- specify pull so the client accepts pushed parameters
- specify server address with remote; specify ha remote parameters if that's the case

### Peer Role

- mode needs to be p2p
- tls-mode can be randomly choosen but is customary to use tls-mode client

-------------------------------------------------------------------------------------------------------------------

## Other important aspects


Have a clear picture about how things are falling into place especially with routing client networks, pushing client specific configs, and running scripts and configuration snippets
I would say, pay special attention to the following commands, and be sure you understand them fully:

--client-config-dir
--client-connect
--push
--ifconfig-push
--ifconfig
--route *network/ip [netmask] [gw] [metric]*
--iroute *network [netmask]*
--route-gateway *[gateway | dhcp]*
--redirect-gateway *[local, def1, bypass-dns, block-local]*

The routing scenarios you might encounter are:

- routing client network on the server sides and maybe to other client 
- routing server networks on the client side

Understand the implied gateway with **route** and **redirect-gateway**.
The official documentation says that is either the argument of **route-gateway**, or the second argument to **ifconfig**, and understanding this ifconfig is key, because most of the times we do not use ifconfig in server configuration.
It is automatically configured by virtute of the helper directivtive **server** which configs ifconfig-pool, ifconfig and mode server.
There also the case where route and redirect-gateway are pushed to the client, and the next-hop that the gateway understand as implied it depends on the topology. If the topology is p2p or net30 then it will use the peer ip address, but if the topology is subnet, then route-gateway should be configured on the client or pushed by the server; or you push the route with included next-hop from the begining.

### DNS Configuration


There are two methods to push these kind of client settings (dns servers, domainname, dnssearch, etc)
One of the methods is using **dhcp-option** configuration option, which will work on windows clients by default, but for linux, the pushed options will be availble as environment variables **foreign_option_{n}** in the **up script**

The second method is to use **--dns** option. The **--dns** option will eventually obsolete the **--dhcp-option** directive. 
Until then it will replace configuration at the places **--dhcp-option** puts it, so that **--dns** overrides **--dhcp-option**. 
Thus, **--dns** can be used today to migrate from **--dhcp-option**.


## Here is my proposal for openvpn config structure:


### Service Internals
*configuration options related to openvpn service in general*

- **daemon** *progname*; start the service daemonized and assign optionally a name to the daemon.
- **user** *user_name*; after starting the process drop to user_name privilege.
- **group** *group_name*; after starting the process drop to group_name privilege.
- **nice** *-0+* ;*negative values give higher priority, while positive values give lower priority to the ovpn process*.
- **writepid** *file*; Write OpenVPN's main process ID to file. 
- **chroot** */path/to/dir*; use a chroot setup for a highly secure setup.
- **persist-tun**; Don't close and reopen TUN/TAP device or run up/down scripts across SIGUSR1 or --ping-restart restarts.
- **persist-key**; Don't re-read key files across SIGUSR1 or --ping-restart.
- **persist-local-ip**; Preserve initially resolved local IP address and port number across SIGUSR1 or --ping-restart restarts.
- **persist-remote-ip**; Preserve most recently authenticated remote IP address and port number across SIGUSR1 or --ping-restart restarts.
- **max-clients** *{nr}* ; 	Limit server to a maximum of n concurrent clients.

### Logging

- **log** *file*; if file already exists it will be overwriten
- **log-append** *file*; the only difference from log is that existing file will not be trunkated
- **mute** *n*; Log at most n consecutive messages in the same category. This is useful to limit repetitive logging of similar message types.
- **verb** *n*; n can take values from 0(no output) to 11(debug)
- **status** *file n*; write operational status to file every n seconds.
- **status-version** *1|2|3* ; higher numbers give more verbose status.


### Protocol Options

- **allow-compression** *asym | no | yes*; compression is not encouraged, that's why no is the default option
- **compress {algorithm}** - algorithm can be "lzo", or "lz4"; compression is not recommended
- **data-ciphers** *cipher-list*; use this one if you want to specify encryption ciphers in tls mode; is the modern option that replaces cipher
- **auth {alg}** - alg is one of: MD5, SHA1, RSA-SHA1, DSA-SHA1, RSA-SHA256, SHA256, SHA384, SHA512
- **cipher {alg}** - the default is BF-CBC, but is legacy. AES-128-CBC, AES-128-CFB, AES-256-GCM, openvpn --show-ciphers 
- **ncp-cipher-list {cipher_list}** - restrict the automatically negotiated ciphers to the specified list; "AES-128-CBC:AES-256-GCM"


### TLS Configuration

- **ca {/path/to/ca}**; Certificate authority (CA) file in .pem format, also referred to as the root certificate.
- **cert {/path/to/server_cert}**; Directory containing trusted certificates (CAs and CRLs). Not available with mbed TLS.
- **key {/path/to/server_key}**; Local peer's private key in .pem format.
- **pkcs12 {/path/to/file}**; Specify a PKCS #12 file containing local private key, local certificate, and root CA certificate. This option can be used instead of *--ca*, *--cert*, and *--key*.
- **dh /path/to/dh**;  Diffie Hellman parameters in pem format (required for tls-server only); *openssl dhparam -out dh2048.pem 2048*
- **tls-auth {/path/to/tls_key}**;  the tls key can be generated with *easyrsa or openvpn --genkey*
- **tls-crypt {/path/to/tls_key}**; use the same type of key as for tls-auth, but provides stronger protection
- **crl-verify** *{/path/to/crl} [dir]*; we can specify either path to a crl file and omit *dir* flag, or specify path to a dir containing crls and use the *dir* flag.
- **tls-server** - Enable TLS and assume server role during TLS handshake.
- **tls-client** - Enable TLS and assume client role during TLS handshake.
- **verify-client-cert** *{require | optional | none}* - this option replaced *--client-cert-not-required*
- **verify-x509-name** *args*; Accept connections only if a host's X.509 name is equal to name.
    > verify-x509-name 'C=KG, ST=NA, L=Bishkek, CN=Server-1' subject
    > verify-x509-name Server-1 name
    > verify-x509-name Server- name-prefix
- **remote-cert-tls** *{server | client}* - check the key usage of the certificate

### Outer Network Configuration

- **local {IP}** - If specified, OpenVPN will bind to this address only. If unspecified, OpenVPN will bind to all interfaces. 
- **remote** *{IP [Port] [Proto]}* - The remote end OpenVPN
- **proto** *{udp | tcp-server | tcp-client}*
- **port** *{portNr}* - this will set lport and rport to the same number
- **lport** *{portNr}* - the local port to listen to
- **rport** *{portNr}* - the remote port to connect to
- **float** - permit dynamic ip addresses on the remote endpoint
- **keepalive** *{interval timeout}* - A helper directive designed to simplify the expression of --ping and --ping-restart.
- **ping** *{seconds}*; Ping remote over the TCP/UDP control channel if no packets have been sent for at least n seconds  
    (specify --ping on both peers to cause ping packets to be sent in both directions since OpenVPN ping packets are not echoed like IP ping packets).
- **ping-exit** *{seconds}*; Causes OpenVPN to exit after n seconds pass without reception of a ping or other packet from remote. 
- **ping-restart** *{seconds}*; Similar to --ping-exit, but trigger a SIGUSR1 restart after n seconds pass without reception of a ping or other packet from remote.
- **inactive** *args*; exit after n seconds of inactivity on the tun/tap device

### Inner Network Configuration

- **mode {server | p2p}** , p2p being the default and working as a site to site VPN
- **topology {subnet | net30 | p2p}** - This refers to layer3/IP topology, and net30 and p2p are not used so much lately. 
>Note: Using *--topology subnet* changes the interpretation of the arguments of *--ifconfig* to mean "address netmask", and no longer "*localIP remoteIP*".
- **dev {tunX | tapX}** - Specify the name of an interface that will be created by openvpn. Use tun for L3 tunneling and tap for bridge VPN
- **dev-type {tun | tap}** - Use this option only if the device names above are not starting with tun or tap.
- **dev-node {/dev/net/node_name}** - Explicitly set the device node rather than using /dev/net/tun. On Linux, tun/tap devices are created by accessing /dev/net/tun
>If OpenVPN cannot figure out whether node is a TUN or TAP device based on the name, you should also specify *--dev-type tun* or *--dev-type tap*.  
>On Windows systems, select the TAP-Win32 adapter which is named node in the Network Connections Control Panel or the raw GUID of the adapter enclosed by braces. The *--show-adapters* option under Windows can also be used to enumerate all available TAP-Win32 adapters and will show both the network connections control panel name and the GUID for each TAP-Win32 adapter.
- **ifconfig l rn** - Set TUN/TAP adapter parameters. It requires the IP address of the local VPN endpoint. For TUN devices in point-to-point mode, the next argument must be the VPN IP address of the remote VPN endpoint.
- **iroute {network [netmask]}** - generate an internal route to specific client; this directive need to be used with a client instance.
- **route {network/IP [netmask] [gateway] [metric]}** - Add route to routing table after connection is established. Multiple routes can be specified. 
- **route-gateway {gwIP|dhcp}** - Specify a default gateway gw for use with *route*.
- **redirect-gateway {flags...}** - Automatically execute routing commands to cause all outgoing IP traffic to be redirected over the VPN. This is a client-side option. 
1. Create a static route for the --remote address which forwards to the pre-existing default gateway. 
2. Delete the default gateway route.
3. Set the new default gateway to be the VPN endpoint address (derived either from --route-gateway or the second parameter to --ifconfig when --dev tun is specified).
Option flags: *local, autolocal, def1, bypass-dns, bypass-dhcp, block-local*
- **link-mtu**
- **tun-mtu** -  OpenVPN requires that packets on the control or data channels be sent unfragmented.
- **fragment {max}**
- **mssfix {max}**

### Client Options

- **allow-recursive-routing**; When this option is set, OpenVPN will not drop incoming tun packets with same destination as host.
- **auth-user-pass**; Authenticate with server using username/password.
- **auth-retry** [none|nointeract|interact]; retry user/password;
- **client** ;A helper directive designed to simplify the configuration of OpenVPN's client mode. 
	this implies config options:  pull and tls-client
- **client-nat** *snat 192.168.0.0/255.255.0.0*; local view of a resource from the client perspective
- **client-nat** *dnat 10.64.0.0/255.255.0.0*; remote view from the server perspective.
- **connect-retry** *n max*; wait n seconds between connection attempts
- **dns** *search-domains domain [domain ...]*
- **dns** *server 1 address addr[:port] [addr[:port] ...]*
- **dns** *server 1 resolve-domains domain [domain ...]*
- **dhcp-option** *type [parm]*; 
    - DOMAIN name
    - DOMAIN-SEARCH name
    - DNS address
    - NTP address
- **pull**; It indicates to OpenVPN that it should accept options pushed by the server
- **remote** *host [port] [proto]*
- **topology** *{subnet | net30 | p2p}* - This refers to layer3/IP topology, and net30 and p2p are not used so much lately.  It is usually pushed from the server.
>Note: Using *--topology subnet* changes the interpretation of the arguments of *--ifconfig* to mean "address netmask", and no longer "*localIP remoteIP*".
- **tls-client**

### Server Options

- **server {ip netmask [nopol]}** - a helper directive that will automatically configure *mode server* and *ifconfig-pool* 
- **server-bridge {gateway netmask pool-start-IP pool-end-IP}**- a helper directive similar to *--server*, which is designed to simplify the configuration of OpenVPN's server mode in ethernet bridging configurations.
- **auth-user-pass-optional** ; allow connection by clients that do not specify a username/password
- **disable** ; associate with specific client instance to disable client
- **ifconfig {l rn}** - l is the IP address of the local VPN endpoint. If topology is subnet then rn configures the mask, otherways if topology is p2p or net30, rn represents the peer IP address
- **ifconfig-pool {start_IP end_IP [netmask]}** -  Set aside a pool of subnets to be dynamically allocated to connecting clients.
- **ifconfig-pool-persist {file [seconds]}** - Persist/unpersist ifconfig-pool data to file, at seconds intervals (default=600)
- **duplicate-cn** - Allow multiple clients with the same common name to concurrently connect.
- **push {option}** - push specific configurations to the client. Client has to use *--pull* or *--client*, to be able to accept those
>push options: route, route-gateway, redirect-gateway, ping, ping-exit, ping-restart, auth-token, persist-key, persist-tun, comp-lzo
>dns, dhcp-option, topology 
- **ifconfig-push {local remote-netmask [alias]}** - Push virtual IP endpoints for client tunnel, overriding the *--ifconfig-pool* dynamic allocation.
>The parameters local and remote-netmask are set according to the *--ifconfig* directive which you want to execute on the client machine to configure the remote end of the tunnel. 
>Note that the parameters local and remote-netmask are from the perspective of the client, not the server.  
>This option must be associated with a specific client instance, which means that it must be specified either in a client instance config file using *--client-config-dir* or dynamically generated using a *--client-connect script*.   
- **client-config-dir {/path/to/dir}**
- **client-to-client** -  This flag tells OpenVPN to internally route client-to-client traffic rather than pushing all client-originating traffic to the TUN/TAP interface.
- duplicate-cn
--ccd-exclusive
--client-to-client


### Scripting

There are multiple way to run scripts on the server, triggered at different stages of the connection:

### --client-config-dir
**this is not an actual script, but rather a feature that allows for having custom configuration lines applied when specific client connects.**

The way it works is that **--client-config-dir** specifies a /path/to where you will store the client custom config files. 
OpenVPN will look in this directory for a file having the same name as the client's X509 common name. If a matching file exists, it will be opened and parsed for client-specific configuration options.  
If no matching file is found, OpenVPN will instead try to open and parse a default file called "DEFAULT", which may be provided but is not required.
The following options are legal in a client-specific context: **--push, --push-reset, --push-remove, --iroute, --ifconfig-push, --vlan-pvid and --config**.

#### Connection stages

**up**
Executed after TCP/UDP socket bind and TUN/TAP open. Used usually to configure dns on the client, or routing

--client-connect cmd
Executed in --mode server mode immediately after client authentication.
The command is passed the common name and IP address of the just-authenticated client as environmental variables (see environmental variable section below). The command is also passed the pathname of a freshly created temporary file as the last argument (after any arguments specified in cmd ), to be used by the command to pass dynamically generated config file directives back to OpenVPN.

--auth-user-pass-verify cmd method
Executed in --mode server mode on new client connections, when the client is still untrusted.
Require the client to provide a username/password (possibly in addition to a client certificate) for authentication.
method can be via-env, in which case **username** and **password** environment variables will be passed to cmd script, 
or method can be via-file, in which case OpenVPN will write the username and password to the first two lines of a temporary file. 
The filename will be passed as an argument to cmd, and the file will be automatically deleted by OpenVPN after the script returns. 

Script environment variables:

common-name
foreign_option_{n}
dev

- **script-security-level**


### Commands

- **mktun**
- **rmtun**
- **show-ciphers**
- **show-digests**
- **show-engines**
- **show-tls**
- **genkey** *secret file*



### Authentication Options


- **--duplicate-cn** - allow multiple sessions from the same client


