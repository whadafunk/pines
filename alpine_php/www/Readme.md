
# PHP Server Diagnostics Page

This project is a **self-contained PHP diagnostics and monitoring tool** that displays detailed information about the server, client, and request/response flow.  
It is styled with a **dark, easy-on-the-eyes theme** and organized into **collapsible sections** for clarity.

---

## Features

### 🔹 Header Summary (always visible)
- **Server Info:** hostname, backend IP & port
- **Client Info:** IP address, User-Agent
- **Dynamic Metrics:** current server time, number of times page was served, memory usage, CPU load

### 🔹 Collapsible Sections
1. **Request Details**
   - Request method
   - Query parameters
   - Request headers
   - Cookies

2. **Response Headers**  
   - All headers returned by the server

3. **Server Info**
   - Web server version
   - PHP version
   - Installed PHP modules
   - OS version
   - Kernel version
   - Uptime

4. **Certificate Info**
   - Subject, Issuer, Validity dates, Cipher (if HTTPS)
   - Displays a message if connection is not HTTPS

5. **PHP Config (Top 10 Directives)**  
   - memory_limit, max_execution_time, upload_max_filesize, post_max_size, etc.

6. **Apache Config (Top 10 Directives)**  
   - ServerRoot, DocumentRoot, Timeout, KeepAlive, MaxKeepAliveRequests, etc.

7. **Additional Sections (collapsible)**  
   - Environment Variables  
   - Session Information  
   - PHP Runtime Limits  
   - Network & Routing Info  
   - Security / Headers  
   - HAProxy-Specific Info (*requires HAProxy forwarding config*)

---

## HAProxy Support

If the server is behind HAProxy, you may want to expose **X-Forwarded-For** and **X-Forwarded-Proto** headers.  
Example config snippet for HAProxy:

```haproxy
frontend http-in
    bind *:80
    default_backend servers

backend servers
    server web1 127.0.0.1:81 check
    http-request set-header X-Forwarded-For %[src]
    http-request set-header X-Forwarded-Proto https if { ssl_fc }
```

The PHP script will display this info under **HAProxy-Specific Info**.

---

## Styling

- **Dark theme** with soft contrast
- **Bold key labels** with color distinction from values
- **Collapsible panels** with smooth toggle animations
- **Copy-to-clipboard** buttons for headers and code blocks

---

## Requirements

- PHP 7.4+ (tested with PHP 8.3)
- Apache / Nginx with PHP enabled
- Optional: HAProxy, HTTPS with SSL termination

---

## Usage

1. Place the `index.php` file on your server (e.g., `/var/www/html/diagnostics/index.php`).
2. Open it in your browser: `http://yourserver/diagnostics/`
3. Navigate through collapsible sections to explore details.

---

## Notes

- Memory usage and CPU load are calculated at runtime (specific to PHP process).
- Apache directives may be available only if `apache_get_version()` and related functions are supported.
- SSL details require HTTPS context.

---

## License

Free to use and modify for personal or enterprise diagnostics purposes.

