<?php
// Conectar ao banco de dados
include(__DIR__ . '/../../src/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $texto_pergunta = $_POST['texto_pergunta'];


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