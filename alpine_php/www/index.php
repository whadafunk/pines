<?php
// -------------------- HEADER INFO & METRICS --------------------
$hostname = gethostname();
$server_ip = $_SERVER['SERVER_ADDR'] ?? 'N/A';
$server_port = $_SERVER['SERVER_PORT'] ?? 'N/A';
$current_time = date('Y-m-d H:i:s');

$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';

session_start();
if (!isset($_SESSION['page_count'])) {
    $_SESSION['page_count'] = 0;
}
$_SESSION['page_count']++;

$memory_usage = round(memory_get_usage() / 1024 / 1024, 2) . ' MB';
$cpu_load = sys_getloadavg()[0] ?? 0;

// -------------------- SERVER INFO --------------------
$os_info = php_uname();
$uptime = @exec('uptime') ?: 'N/A';
$php_version = PHP_VERSION;
$php_modules = get_loaded_extensions();
$apache_version = function_exists('apache_get_version') ? apache_get_version() : 'N/A';

// -------------------- REQUEST INFO --------------------
$request_headers = getallheaders();
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'N/A';
$query_params = $_GET ?? [];
$cookies = $_COOKIE ?? [];

// -------------------- RESPONSE HEADERS --------------------
$response_headers = headers_list();

// -------------------- PHP CONFIG --------------------
$php_top_directives = [
    'memory_limit',
    'post_max_size',
    'upload_max_filesize',
    'max_execution_time',
    'max_input_vars',
    'display_errors',
    'error_reporting',
    'date.timezone',
    'session.gc_maxlifetime',
    'opcache.enable'
];
$php_config = [];
foreach ($php_top_directives as $dir) {
    $php_config[$dir] = ini_get($dir);
}

// -------------------- APACHE CONFIG --------------------
$apache_modules = function_exists('apache_get_modules') ? apache_get_modules() : [];
$apache_top_directives = [
    'Timeout',
    'KeepAlive',
    'MaxKeepAliveRequests',
    'KeepAliveTimeout',
    'ServerTokens',
    'ServerSignature',
    'TraceEnable',
    'MaxRequestWorkers',
    'MaxConnectionsPerChild',
    'Listen'
];

// -------------------- PROXY INFO --------------------
$proxy_headers = [
    'X-Forwarded-For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
    'X-Forwarded-Proto' => $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '',
    'X-Forwarded-Port' => $_SERVER['HTTP_X_FORWARDED_PORT'] ?? '',
    'Forwarded' => $_SERVER['HTTP_FORWARDED'] ?? ''
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PHP / HAProxy Test Page</title>
<style>
body { background: #1e1e1e; color: #c5c5c5; font-family: monospace; margin:0; padding:0; }
header { padding:20px; border-bottom:2px solid #333; background:#2b2b2b; box-shadow:0 2px 5px rgba(0,0,0,0.5); }
header h1 { margin:0; color:#00ffff; }
.header-line { margin-top:5px; }
.section { border:1px solid #444; margin:10px; border-radius:5px; box-shadow:0 2px 5px rgba(0,0,0,0.5); }
.section-header { padding:10px; cursor:pointer; background:#333; border-bottom:1px solid #444; }
.section-header:hover { background:#444; }
.section-content { padding:10px; display:none; background:#1e1e1e; }
.key { color:#ffb86c; font-weight:bold; }
.value { color:#8be9fd; }
.copy-btn { background:#6272a4; color:#f8f8f2; border:none; padding:3px 6px; margin-left:10px; cursor:pointer; font-size:0.8em; border-radius:3px; }
pre { background:#2b2b2b; padding:10px; border-radius:5px; overflow:auto; }
</style>
</head>
<body>
<header>
<h1>PHP / HAProxy Test Page</h1>
<div class="header-line"><span class="key">Server:</span> <span class="value"><?=htmlspecialchars($hostname)?> | IP: <?=htmlspecialchars($server_ip)?> | Port: <?=htmlspecialchars($server_port)?></span></div>
<div class="header-line"><span class="key">Time:</span> <span class="value"><?=$current_time?></span></div>
<div class="header-line"><span class="key">Client IP:</span> <span class="value"><?=htmlspecialchars($client_ip)?></span> | <span class="key">User-Agent:</span> <span class="value"><?=htmlspecialchars($user_agent)?></span></div>
<div class="header-line"><span class="key">Page served:</span> <span class="value"><?=$_SESSION['page_count']?> times</span> | <span class="key">Memory:</span> <span class="value"><?=$memory_usage?></span> | <span class="key">CPU load:</span> <span class="value"><?=$cpu_load?></span></div>
</header>

<?php
function render_section($title, $content_html) {
    echo '<div class="section">';
    echo '<div class="section-header">'.$title.'</div>';
    echo '<div class="section-content">'.$content_html.'</div>';
    echo '</div>';
}

// REQUEST DETAILS
$request_html = '<pre>';
$request_html .= "<strong>Method:</strong> $request_method\n";
$request_html .= "<strong>Query Params:</strong>\n".htmlspecialchars(json_encode($query_params, JSON_PRETTY_PRINT))."\n";
$request_html .= "<strong>Cookies:</strong>\n".htmlspecialchars(json_encode($cookies, JSON_PRETTY_PRINT))."\n";
$request_html .= "<strong>Request Headers:</strong>\n";
foreach ($request_headers as $k=>$v) { $request_html .= "<span class='key'>$k:</span> <span class='value'>$v</span>\n"; }
$request_html .= '</pre>';
render_section('Request Details', $request_html);

// RESPONSE HEADERS
$response_html = '<pre>';
foreach ($response_headers as $h) { $response_html .= "<span class='key'>$h</span>\n"; }
$response_html .= '</pre>';
render_section('Response Headers', $response_html);

// SERVER INFO
$server_html = '<pre>';
$server_html .= "<strong>OS:</strong> $os_info\n";
$server_html .= "<strong>Uptime:</strong> $uptime\n";
$server_html .= "<strong>PHP Version:</strong> $php_version\n";
$server_html .= "<strong>Apache Version:</strong> $apache_version\n";
$server_html .= "<strong>PHP Modules:</strong>\n".htmlspecialchars(json_encode($php_modules, JSON_PRETTY_PRINT))."\n";
$server_html .= "</pre>";
render_section('Server Info', $server_html);

// PHP CONFIG
$php_html = '<pre>';
foreach ($php_config as $k=>$v) { $php_html .= "<span class='key'>$k:</span> <span class='value'>$v</span>\n"; }
$php_html .= '</pre>';
render_section('Top PHP Directives', $php_html);

// APACHE CONFIG
$apache_html = '<pre>';
foreach ($apache_top_directives as $d) {
    $val = in_array($d, $apache_modules) ? 'enabled' : 'N/A';
    $apache_html .= "<span class='key'>$d:</span> <span class='value'>$val</span>\n";
}
$apache_html .= '</pre>';
render_section('Top Apache Directives', $apache_html);

// PROXY INFO
$proxy_html = '<pre>';
foreach ($proxy_headers as $k=>$v) { $proxy_html .= "<span class='key'>$k:</span> <span class='value'>$v</span>\n"; }
$proxy_html .= '</pre>';
render_section('Proxy Info', $proxy_html);
?>

<?php
// -------------------- CERTIFICATE INFO --------------------
$cert_html = '<pre>';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $cert_subject = $_SERVER['SSL_CLIENT_S_DN'] ?? 'N/A';
    $cert_issuer = $_SERVER['SSL_CLIENT_I_DN'] ?? 'N/A';
    $cert_valid_from = $_SERVER['SSL_CLIENT_V_START'] ?? 'N/A';
    $cert_valid_to = $_SERVER['SSL_CLIENT_V_END'] ?? 'N/A';
    $cert_cipher = $_SERVER['SSL_CIPHER'] ?? 'N/A';
    
    $cert_html .= "<span class='key'>Subject:</span> <span class='value'>$cert_subject</span>\n";
    $cert_html .= "<span class='key'>Issuer:</span> <span class='value'>$cert_issuer</span>\n";
    $cert_html .= "<span class='key'>Valid From:</span> <span class='value'>$cert_valid_from</span>\n";
    $cert_html .= "<span class='key'>Valid To:</span> <span class='value'>$cert_valid_to</span>\n";
    $cert_html .= "<span class='key'>Cipher:</span> <span class='value'>$cert_cipher</span>\n";
} else {
    $cert_html .= "Connection is not HTTPS. Certificate info not available.";
}
$cert_html .= '</pre>';
render_section('Certificate Info', $cert_html);
?>

<?php
// -------------------- ENVIRONMENT VARIABLES --------------------
$env_html = '<pre>';
foreach ($_SERVER as $key => $value) {
    $env_html .= "<span class='key'>$key:</span> <span class='value'>" . htmlspecialchars($value) . "</span>\n";
}
$env_html .= '</pre>';
render_section('Environment Variables', $env_html);

// -------------------- SESSION INFORMATION --------------------
if (session_status() === PHP_SESSION_NONE) session_start();
$session_html = '<pre>';
$session_html .= "<span class='key'>Session ID:</span> <span class='value'>" . session_id() . "</span>\n";
$session_html .= "<span class='key'>Session Variables:</span>\n" . htmlspecialchars(print_r($_SESSION, true));
$session_html .= '</pre>';
render_section('Session Information', $session_html);

// -------------------- PHP RUNTIME LIMITS --------------------
$php_limits_html = '<pre>';
$php_limits_html .= "<span class='key'>Memory Limit:</span> <span class='value'>" . ini_get('memory_limit') . "</span>\n";
$php_limits_html .= "<span class='key'>Max Execution Time:</span> <span class='value'>" . ini_get('max_execution_time') . " sec</span>\n";
$php_limits_html .= "<span class='key'>Post Max Size:</span> <span class='value'>" . ini_get('post_max_size') . "</span>\n";
$php_limits_html .= "<span class='key'>Upload Max Filesize:</span> <span class='value'>" . ini_get('upload_max_filesize') . "</span>\n";
$php_limits_html .= "<span class='key'>Max Input Vars:</span> <span class='value'>" . ini_get('max_input_vars') . "</span>\n";
$php_limits_html .= '</pre>';
render_section('PHP Runtime Limits', $php_limits_html);

// -------------------- NETWORK & ROUTING INFO --------------------
$network_html = '<pre>';
$network_html .= "<span class='key'>Server IP:</span> <span class='value'>" . ($_SERVER['SERVER_ADDR'] ?? 'N/A') . "</span>\n";
$network_html .= "<span class='key'>Server Port:</span> <span class='value'>" . ($_SERVER['SERVER_PORT'] ?? 'N/A') . "</span>\n";
$network_html .= "<span class='key'>Remote IP:</span> <span class='value'>" . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "</span>\n";
$network_html .= "<span class='key'>Remote Port:</span> <span class='value'>" . ($_SERVER['REMOTE_PORT'] ?? 'N/A') . "</span>\n";
$network_html .= "<span class='key'>Protocol:</span> <span class='value'>" . ($_SERVER['SERVER_PROTOCOL'] ?? 'N/A') . "</span>\n";
$network_html .= '</pre>';
render_section('Network & Routing Info', $network_html);

// -------------------- SECURITY / HEADERS --------------------
$security_html = '<pre>';
foreach (getallheaders() as $name => $value) {
    $security_html .= "<span class='key'>$name:</span> <span class='value'>" . htmlspecialchars($value) . "</span>\n";
}
$security_html .= '</pre>';
render_section('Security / Headers', $security_html);

// -------------------- HAPROXY-SPECIFIC INFO --------------------
// HAProxy configuration needed to populate these headers:
// http-request set-header X-HAPROXY-SERVER %[srv_name]
// http-request set-header X-HAPROXY-BACKEND %[backend]
// http-request set-header X-HAPROXY-SESSION %[src]
$haproxy_html = '<pre>';
$haproxy_html .= "<span class='key'>HAProxy Server:</span> <span class='value'>" . ($_SERVER['HTTP_X_HAPROXY_SERVER'] ?? 'N/A') . "</span>\n";
$haproxy_html .= "<span class='key'>HAProxy Backend:</span> <span class='value'>" . ($_SERVER['HTTP_X_HAPROXY_BACKEND'] ?? 'N/A') . "</span>\n";
$haproxy_html .= "<span class='key'>HAProxy Session ID:</span> <span class='value'>" . ($_SERVER['HTTP_X_HAPROXY_SESSION'] ?? 'N/A') . "</span>\n";
$haproxy_html .= '</pre>';
render_section('HAProxy Info', $haproxy_html);
?>

<script>
document.querySelectorAll('.section-header').forEach(function(header){
    header.addEventListener('click', function(){
        let content = this.nextElementSibling;
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
    });
});

document.querySelectorAll('.copy-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        navigator.clipboard.writeText(this.previousElementSibling.innerText);
    });
});
</script>

</body>
</html>

