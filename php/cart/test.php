<?php
// Simple test endpoint to verify access to php/cart/* from the web server
header('Content-Type: application/json; charset=utf-8');
http_response_code(200);

$out = [
    'ok' => true,
    'time' => date('c'),
    'script' => __FILE__,
    'dir' => __DIR__,
    'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
    'method' => $_SERVER['REQUEST_METHOD'] ?? null,
    'authorization' => $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null),
    'all_server' => array_intersect_key($_SERVER, array_flip(['SERVER_SOFTWARE','DOCUMENT_ROOT','SCRIPT_FILENAME','REQUEST_URI','PHP_SELF']))
];

echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
