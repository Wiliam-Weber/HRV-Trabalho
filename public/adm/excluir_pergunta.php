<?php
// Conectar ao banco de dados
include(__DIR__ . '/../../src/db.php');

if (isset($_GET['id'])) {
    $id_pergunta = $_GET['id'];

    // Verificar se existem respostas associadas a essa pergunta
    $query_check_respostas = "SELECT COUNT(*) FROM avaliacoes WHERE id_pergunta = :id_pergunta";
    $stmt_check_respostas = $conn->prepare($query_check_respostas);
    $stmt_check_respostas->bindParam(':id_pergunta', $id_pergunta);
    $stmt_check_respostas->execute();
    $count_respostas = $stmt_check_respostas->fetchColumn();

    if ($count_respostas > 0) {
        echo "Não é possível excluir a pergunta, pois ela possui respostas associadas.";
        exit;
    }

    // Excluir a pergunta
    $query = "DELETE FROM perguntas WHERE id = :id_pergunta";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_pergunta', $id_pergunta);

    if ($stmt->execute()) {
        header("Location: painel_adm.php"); // Redireciona para o painel administrativo
        exit;
    } else {
        echo "Erro ao excluir a pergunta!";
    }
} else {
    echo "ID da pergunta não fornecido!";
}
?>
