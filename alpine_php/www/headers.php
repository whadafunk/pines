<?php
header('Content-Type: application/json');

// If "response" mode, just simulate common response headers
if (isset($_GET['mode']) && $_GET['mode'] === 'response') {
    echo json_encode([
        "content-type" => "text/html; charset=UTF-8",
        "cache-control" => "no-cache, no-store, must-revalidate",
        "date" => gmdate("D, d M Y H:i:s") . " GMT",
        "server" => $_SERVER['SERVER_SOFTWARE'],
        "etag" => md5(uniqid())
    ], JSON_PRETTY_PRINT);
    exit;
}

// Default: request headers
echo json_encode(getallheaders(), JSON_PRETTY_PRINT);
?>

