<?php
// Conectar ao banco de dados
include(__DIR__ . '/../../src/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obter o ID da pergunta a ser editada
    $id_pergunta = $_GET['id'];

    // Buscar a pergunta pelo ID
    $query = "SELECT * FROM perguntas WHERE id = :id_pergunta";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_pergunta', $id_pergunta);
    $stmt->execute();
    $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pergunta) {
        echo "Pergunta não encontrada!";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizar a pergunta
    $id_pergunta = $_POST['id_pergunta'];
    $texto_pergunta = $_POST['texto_pergunta'];

    $query = "UPDATE perguntas SET texto_pergunta = :texto_pergunta WHERE id = :id_pergunta";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':texto_pergunta', $texto_pergunta);
    $stmt->bindParam(':id_pergunta', $id_pergunta);

    if ($stmt->execute()) {
        header("Location: painel_adm.php"); // Redireciona para o painel administrativo
        exit;
    } else {
        echo "Erro ao atualizar a pergunta!";
    }
}

// Inclui o HTML para exibição
include('../html/editar_pergunta.html');
?>
