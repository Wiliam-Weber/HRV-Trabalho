<?php
// Conectar ao banco de dados
include(__DIR__ . '/../../src/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegar o texto da pergunta
    $texto_pergunta = $_POST['texto_pergunta'];

    // Inserir a nova pergunta no banco de dados
    $query = "INSERT INTO perguntas (texto_pergunta, status) VALUES (:texto_pergunta, 'ativa')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':texto_pergunta', $texto_pergunta);

    if ($stmt->execute()) {
        header("Location: painel_adm.php"); // Redireciona para o painel administrativo
        exit;
    } else {
        echo "Erro ao adicionar a pergunta!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">

    <title>Adicionar Pergunta</title>
</head>
<body>
    <h1>Adicionar Pergunta</h1>

    <!-- Formulário para adicionar pergunta -->
    <form action="adicionar_pergunta.php" method="POST">
        <label for="texto_pergunta">Texto da Pergunta</label>
        <input type="text" name="texto_pergunta" required>
        <button type="submit">Adicionar Pergunta</button>
    </form>

    <a href="painel_adm.php">Voltar ao Painel Administrativo</a>
</body>
</html>
