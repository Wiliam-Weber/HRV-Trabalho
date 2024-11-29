<?php
$host = 'localhost';
$port = '5432';
$dbname = 'HRV_Trabalho'; 
$user = 'postgres';    
$password = 'postgres';  

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>

