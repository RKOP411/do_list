<?php
echo "Testing ENVIRONMENT constant...<br>";
echo "ENVIRONMENT: " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'NOT DEFINED') . "<br>";

// Try to load the framework
try {
    require_once '../app/Config/Constants.php';
    echo "Constants.php loaded<br>";
    echo "ENVIRONMENT after load: " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'STILL NOT DEFINED') . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>