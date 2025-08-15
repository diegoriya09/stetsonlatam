<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();
http_response_code(200);
?>