<?php
header('Content-Type: application/json');
echo json_encode([
    "hostname" => gethostname(),
    "server_addr" => $_SERVER['SERVER_ADDR'],
    "client_addr" => $_SERVER['REMOTE_ADDR'],
    "x_forwarded_for" => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
    "server_software" => $_SERVER['SERVER_SOFTWARE'],
], JSON_PRETTY_PRINT);
?>

