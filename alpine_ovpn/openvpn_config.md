# OpenVPN Configuration Parameters


>Two of the most important decisions about openvpn are the mode and the topology.
>Mode configures site2site or remoteVPN
>The interface type tun or tap, configures L3 tunneling or bridge tunneling

--mode *server | p2p* , p2p being the default
--server *ip netmask [nopol]* - a helper directive that will automatically configure mode server and ifconfig-pool 
--server-bridge *gateway netmask pool-start-IP pool-end-IP* - a helper directive similar to --server, which is designed to simplify the configuration of OpenVPN's server mode in ethernet bridging configurations.
--topology *subnet | net30 | p2p* - net30 and p2p are not used so much lately. 
 *Note: Using --topology subnet changes the interpretation of the arguments of --ifconfig to mean "address netmask", no longer "local remote".*
--local
--remote
--proto
--port
--lport
--rport
--dev *tunX | tapX* - Specify the name of an interface that will be created by openvpn. Use tun for L3 tunneling and tap for bridge VPN
--dev-type *tun | tap* - Use this option only if the device names above are not starting with tun or tap
--dev-node */dev/net/node_name* - Explicitly set the device node rather than using /dev/net/tun, /dev/tun, /dev/tap, etc. If OpenVPN cannot figure out whether node is a TUN or TAP device based on the name, you should also specify --dev-type tun or --dev-type tap.  
>On Windows systems, select the TAP-Win32 adapter which is named node in the Network Connections Control Panel or the raw GUID of the adapter enclosed by braces. The --show-adapters option under Windows can also be used to enumerate all available TAP-Win32 adapters and will show both the network connections control panel name and the GUID for each TAP-Win32 adapter.
--ca */path/to/ca*
--cert */path/to/server_cert*
--key */path/to/server_key*
--pkcs12 */path/to/file* - Specify a PKCS #12 file containing local private key, local certificate, and root CA certificate. This option can be used instead of --ca, --cert, and --key.
--dh */path/to/dh* - Diffie Hellman parameters in pem format (required for tls-server only); openssl dhparam -out dh2048.pem 2048
--tls-auth */path/to/tls_key* - the tls key can be generated with easyrsa or openvpn --genkey
--tls-crypt */path/to/tls_key* 
--crl-verify */path/to/crl*
--tls-server
--tls-client
--secret *file [direction]* - The direction parameter should always be complementary on either side of the connection, i.e. one side should use "0" and the other should use "1", or both sides should omit it altogether.
--auth *alg* - alg is one of: MD5, SHA1, RSA-SHA1, DSA-SHA1, RSA-SHA256, SHA256, SHA384, SHA512
--cipher *alg* - the default is BF-CBC, but is legacy. AES-128-CBC, AES-128-CFB, AES-256-GCM, openvpn --show-ciphers 
--ncp-ciphers *cipher_list* - restrict the automatically negotiated ciphers to the specified list

--ifconfig *l rn* - l is the IP address of the local VPN endpoint. If topology is subnet then rn configures the mask, otherways if topology is p2p or net30, rn represents the peer IP address
--ifconfig-pool *start_IP end_IP [netmask]* -  Set aside a pool of subnets to be dynamically allocated to connecting clients.
--ifconfig-pool-persist file [seconds]
--ifconfig-push *local remote-netmask [alias]* - Push virtual IP endpoints for client tunnel, overriding the --ifconfig-pool dynamic allocation.The parameters local and remote-netmask are set according to the --ifconfig directive which you want to execute on the client machine to configure the remote end of the tunnel. Note that the parameters local and remote-netmask are from the perspective of the client, not the server.  
This option must be associated with a specific client instance, which means that it must be specified either in a client instance config file using --client-config-dir or dynamically generated using a --client-connect script.   
--iroute *network [netmask]* - generate an internal route to specific client
--route *network/IP [netmask] [gateway] [metric]* - Add route to routing table after connection is established. Multiple routes can be specified. 
--route-gateway *gw*
--redirect-gateway *flags...* - Automatically execute routing commands to cause all outgoing IP traffic to be redirected over the VPN. This is a client-side option. 
1. Create a static route for the --remote address which forwards to the pre-existing default gateway. 
2. Delete the default gateway route.
3. Set the new default gateway to be the VPN endpoint address (derived either from --route-gateway or the second parameter to --ifconfig when --dev tun is specified).
Option flags:

- local
- autolocal
- def1
- bypass-dns

--client-config-dir */path/to/dir*
--client-to-client -  The --client-to-client flag tells OpenVPN to internally route client-to-client traffic rather than pushing all client-originating traffic to the TUN/TAP interface.

--duplicate-cn - allow multiple sessions from the same client
--keepalive *interval timeout* - A helper directive designed to simplify the expression of --ping and --ping-restart.
--ping *n*
--ping-exit *n*
--ping-restart *n*
--persist-tun
--persist-key
--persist-local-ip
--persist-remote-ip
--user *nobody*
--group *nobody*
--chroot *dir*
--status *openvpn_status.log*
--log *file*
--log-append *file*
--verb *1 to 12*


