<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/../src/db.php');

echo "Conexão bem-sucedida com o banco de dados!";
?>
