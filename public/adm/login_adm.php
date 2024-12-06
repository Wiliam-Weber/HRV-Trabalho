<?php
session_start();

// Verifica se já está logado, se sim, redireciona para o painel
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header("Location: painel_adm.php");
    exit();
}

// Exibe erros apenas em ambiente de desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../src/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : null;

    if (!empty($usuario) && !empty($senha)) {
        try {
            // Consulta no banco de dados
            $sql = "SELECT * FROM usuarios_administrativos WHERE login = :login";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':login', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $usuario_banco = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica usuário e senha
            if ($usuario_banco && $senha === $usuario_banco['senha']) { // Sem hash
                $_SESSION['logado'] = true;
                $_SESSION['usuario_adm'] = $usuario_banco['login'];
                header("Location: painel_adm.php");
                exit();
            } else {
                // Erro de autenticação
                $erro = "Usuário ou senha inválidos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao acessar o banco de dados: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
?>
