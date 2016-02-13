<?php
require_once __DIR__ . "/data/authorization.class.php";
$system = new Authorization();
if(!$system->isAuthorized()) {
    header("Location: /403.php");
    exit;
}
echo "<h3>Powered by Vladislav Kolodka</h3>";