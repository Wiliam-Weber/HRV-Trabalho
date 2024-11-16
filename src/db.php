<?php
$host = 'localhost';
$port = '5432';
$dbname = 'HRV-Trabalho'; // Nome do banco de dados
$user = 'Wiliam';    // Usuário do PostgreSQL
$password = 'postgres';  // Senha do PostgreSQL

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>

