<?php
session_start();

// Usuário e senha predefinidos para autenticação simples
$usuario_correto = "admin";
$senha_correta = "senha123";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verifica se o usuário e senha estão corretos
    if ($usuario === $usuario_correto && $senha === $senha_correta) {
        $_SESSION['logado'] = true;
        header("Location: painel.php");
        exit();
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo</title>
    <link rel="stylesheet" href="css/style-login.css">
</head>
<body>
    <div class="login-container">
        <form action="login_adm.php" method="POST" class="login-form">
            <h1>Login Administrativo</h1>
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" name="username" id="username" placeholder="Digite seu usuário" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>
</html>
