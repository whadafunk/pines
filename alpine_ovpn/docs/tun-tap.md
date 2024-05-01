# Notes about tun/tap interfaces in Linux

>These notes represent my understanding about this technology, and they  
>might be very well wrong. They are based partly on some assumptions and  
>some documentation pages I could find online.


Tun/Tap interfaces can be opened as a file descriptor from userspace applications. 
That is different from the normal way of interacting with the networking stack from an application.  
In the traditional way, an app would register a tcp/udp socket and use that to send and receive data from the networking stack.  
With Tun/Tap, an application doesn't need to understand tcp or udp, or to know about a specific port. It will send data to the  
tun/tap interface encapsulated either with IP, either with ethernet header, depending on the type of interface: tun or tap.  
Tun interfaces need an IP header when they are receiving data from the application.
Tap interfaces need a full ethernet header, but here is something I couldn't understand right away:  
Most of the tutorials about openvpn with tap interface (bridging vpn), show that you configure an ip for the tap interface. 
You can indeed configure an ip on a tap interface, and that simply means that the ethernet frame received by the interface,  
can be forwarded to the upper layer, that being the IP and then routed to other interface or passed to the upper layers tcp, etc...
In a way a tap interface works more like a normal ethernet interface which receives ethernet frames and it can decapsulate them and  
access the ip layer and so on.

Let's picture very high level  how openvpn uses all this


app_tx -> tun0 -> ovpn -> encrypt/ssl -> eth0 ----wire ---- -> eth1 -> ovpn -> decrypt -> tun1 -> app_rx

The tap interface can receive from the application only ethernet and will send ethernet to the application,   
but I think that it can receive IP from the kernel network stack, and then encapsulate with ethernet and send it to the app.


>Bridged mode means that the VPN tunnel encapsulates full ethernet frames (up to 1514 bytes long), rather than IP packets (up to 1500 bytes). In itself, this would just add some overhead to the VPN traffic; but in practice, together with some special configuration in the OS (described later), this allows to connect the VPN and its users to a real, physical ethernet network at the data-link level, effectively turning the whole system (ethernet network + VPN) into a single broadcast domain. 

