<?php
session_start();

// Verifica se já está logado, se sim, redireciona para o painel
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header("Location: painel_adm.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../src/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : null;

    if ($usuario && $senha) {
        try {
            $sql = "SELECT * FROM usuarios_administrativos WHERE login = :login";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':login', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $usuario_banco = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario_banco && password_verify($senha, $usuario_banco['senha'])) {
                // Login bem-sucedido
                $_SESSION['logado'] = true;
                $_SESSION['usuario_adm'] = $usuario_banco['login']; 
                header("Location: painel_adm.php");  
                exit();
            } else {
                // Senha ou usuário incorretos
                $erro = "Usuário ou senha inválidos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro no banco de dados: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_login.css">
    <title>Login Administrativo</title>
</head>
<body>
    <div class="container">
        <h1>Login Administrativo</h1>

        <?php if (isset($erro)): ?>
            <p class="error"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="POST" action="login_adm.php">
            <div class="form-group">
                <label for="usuario">Usuário</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
