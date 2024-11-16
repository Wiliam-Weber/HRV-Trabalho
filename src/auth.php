<?php
// auth.php - Sistema de autenticação para painel administrativo

include '.../src/db.php';

function login($usuario, $senha) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios_admin WHERE login = ? AND senha = ?");
    $stmt->execute([$usuario, $senha]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
