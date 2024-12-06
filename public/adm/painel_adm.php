<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para o login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login_adm.php");
    exit();
}

include(__DIR__ . '/../../src/db.php');

// Consulta para obter as perguntas ativas
$query_perguntas = "SELECT id, texto_pergunta, status FROM perguntas WHERE status = 'ativa'";
$stmt_perguntas = $conn->prepare($query_perguntas);
$stmt_perguntas->execute();
$perguntas = $stmt_perguntas->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter as respostas agrupadas por pergunta
$respostas_query = "SELECT id_pergunta, resposta, COUNT(*) as total_respostas, feedback_textual 
                    FROM avaliacoes 
                    GROUP BY id_pergunta, resposta, feedback_textual";
$stmt_respostas = $conn->prepare($respostas_query);
$stmt_respostas->execute();
$respostas = $stmt_respostas->fetchAll(PDO::FETCH_ASSOC);

// Organiza as respostas por pergunta
$respostas_por_pergunta = [];
foreach ($respostas as $resposta) {
    $respostas_por_pergunta[$resposta['id_pergunta']]['respostas'][$resposta['resposta']] = $resposta['total_respostas'];
    if ($resposta['feedback_textual']) {
        $respostas_por_pergunta[$resposta['id_pergunta']]['feedback'][] = $resposta['feedback_textual'];
    }
}

// Passa os dados para o HTML
include('../html/painel_adm.html');
?>


