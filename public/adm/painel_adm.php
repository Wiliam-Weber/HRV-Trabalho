<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para o login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login_adm.php");
    exit();
}

// Aqui vai o código do painel administrativo
include(__DIR__ . '/../../src/db.php');

// Exemplo de como você pode buscar perguntas e respostas no banco
$query_perguntas = "SELECT id, texto_pergunta, status FROM perguntas WHERE status = 'ativa'";
$stmt_perguntas = $conn->prepare($query_perguntas);
$stmt_perguntas->execute();
$perguntas = $stmt_perguntas->fetchAll(PDO::FETCH_ASSOC);

// Exibir perguntas e respostas
$respostas_query = "SELECT id_pergunta, resposta, COUNT(*) as total_respostas, feedback_textual FROM avaliacoes GROUP BY id_pergunta, resposta, feedback_textual";
$stmt_respostas = $conn->prepare($respostas_query);
$stmt_respostas->execute();
$respostas = $stmt_respostas->fetchAll(PDO::FETCH_ASSOC);

// Organizar as respostas por pergunta
$respostas_por_pergunta = [];
foreach ($respostas as $resposta) {
    $respostas_por_pergunta[$resposta['id_pergunta']]['respostas'][$resposta['resposta']] = $resposta['total_respostas'];
    if ($resposta['feedback_textual']) {
        $respostas_por_pergunta[$resposta['id_pergunta']]['feedback'][] = $resposta['feedback_textual'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Painel Administrativo</h1>

    <!-- Formulário para adicionar pergunta -->
    <h2>Adicionar Pergunta</h2>
    <form action="adicionar_pergunta.php" method="POST">
        <input type="text" name="texto_pergunta" placeholder="Texto da Pergunta" required>
        <button type="submit">Adicionar Pergunta</button>
    </form>

    <!-- Tabela para gerenciar perguntas -->
    <h2>Gerenciar Perguntas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pergunta</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perguntas as $pergunta): ?>
            <tr>
                <td><?php echo $pergunta['id']; ?></td>
                <td><?php echo $pergunta['texto_pergunta']; ?></td>
                <td><?php echo $pergunta['status']; ?></td>
                <td>
                    <a href="editar_pergunta.php?id=<?php echo $pergunta['id']; ?>">Editar</a> | 
                    <a href="excluir_pergunta.php?id=<?php echo $pergunta['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta pergunta?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Exibir Respostas -->
    <h2>Respostas</h2>
    <table>
        <thead>
            <tr>
                <th>Pergunta</th>
                <th>Respostas (0 a 10)</th>
                <th>Total de Respostas</th>
                <th>Feedbacks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perguntas as $pergunta): ?>
            <tr>
                <td><?php echo $pergunta['texto_pergunta']; ?></td>
                <td>
                    <?php 
                    if (isset($respostas_por_pergunta[$pergunta['id']]['respostas'])) {
                        foreach ($respostas_por_pergunta[$pergunta['id']]['respostas'] as $resposta => $total) {
                            echo "Resposta $resposta: $total respostas <br>";
                        }
                    } else {
                        echo "Nenhuma resposta ainda.";
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if (isset($respostas_por_pergunta[$pergunta['id']]['respostas'])) {
                        $total_respostas = array_sum($respostas_por_pergunta[$pergunta['id']]['respostas']);
                        echo $total_respostas;
                    } else {
                        echo "0";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (isset($respostas_por_pergunta[$pergunta['id']]['feedback'])) {
                        foreach ($respostas_por_pergunta[$pergunta['id']]['feedback'] as $feedback) {
                            echo "<p><strong>Feedback:</strong> $feedback</p>";
                        }
                    } else {
                        echo "Nenhum feedback ainda.";
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>


