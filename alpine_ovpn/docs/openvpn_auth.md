# OpenVPN Alternative Authentication Methods



OpenVPN 2.0 and later include a feature that allows the OpenVPN server to securely obtain a username and password from a connecting client, and to use that information as a basis for authenticating the client.  

To use this authentication method, first add the auth-user-pass directive to the client configuration.   
It will direct the OpenVPN client to query the user for a username/password, passing it on to the server over the secure TLS channel.  


### Using Script Plugins

Script plugins can be used by adding the auth-user-pass-verify directive to the server-side configuration file. For example:

> auth-user-pass-verify auth-pam.pl via-file

will use the auth-pam.pl perl script to authenticate the username/password of connecting clients.  
See the description of auth-user-pass-verify in the manual page for more information.  

### Using Shared Object or DLL Plugins

Shared object or DLL plugins are usually compiled C modules which are loaded by the OpenVPN server at run time.  
For example if you are using an RPM-based OpenVPN package on Linux, the openvpn-auth-pam plugin should be already built.  
To use it, add this to the server-side config file:

> plugin /usr/share/openvpn/plugin/lib/openvpn-auth-pam.so login

This will tell the OpenVPN server to validate the username/password entered by clients using the loginPAM module.  

Such configurations should usually also set:  

> username-as-common-name

which will tell the server to use the username for indexing purposes as it would use the Common Name of a client which was authenticating via a client certificate.
