<?php

require_once '../config.php';

header('Content-Type: application/json');

// Simplemente devolvemos la clave en un formato JSON
echo json_encode([
    'apiKey' => GOOGLE_MAPS_API_KEY
]);