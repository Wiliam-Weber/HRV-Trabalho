<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
include('C:\xampp\htdocs\HRV_Trabalho\src');

// Adicionar uma nova pergunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $texto_pergunta = $_POST['texto_pergunta'];
    $status = $_POST['status'];

    try {
        $sql = "INSERT INTO perguntas (texto_pergunta, status) VALUES (:texto_pergunta, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':texto_pergunta', $texto_pergunta, PDO::PARAM_STR);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        $mensagem = "Pergunta adicionada com sucesso!";
    } catch (PDOException $e) {
        $erro = "Erro ao adicionar a pergunta: " . $e->getMessage();
    }
}

// Desativar ou ativar pergunta
if (isset($_GET['acao']) && isset($_GET['id'])) {
    $acao = $_GET['acao'];
    $id_pergunta = $_GET['id'];

    try {
        if ($acao === 'desativar') {
            $sql = "UPDATE perguntas SET status = 'inativa' WHERE id = :id";
        } elseif ($acao === 'ativar') {
            $sql = "UPDATE perguntas SET status = 'ativa' WHERE id = :id";
        } else {
            $sql = "DELETE FROM perguntas WHERE id = :id";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id_pergunta, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar a pergunta: " . $e->getMessage();
    }
}

// Buscar todas as perguntas
$sql = "SELECT * FROM perguntas";
$stmt = $conn->prepare($sql);
$stmt->execute();
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
</head>
<body>
    <h1>Painel Administrativo</h1>

    <p><a href="logout.php">Sair</a></p>

    <h2>Adicionar Nova Pergunta</h2>
    <?php if (isset($mensagem)): ?>
        <p style="color: green;"><?= $mensagem; ?></p>
    <?php endif; ?>
    <?php if (isset($erro)): ?>
        <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="texto_pergunta">Texto da Pergunta:</label>
        <textarea name="texto_pergunta" required></textarea>
        <br><br>
        <label for="status">Status:</label>
        <select name="status" required>
            <option value="ativa">Ativa</option>
            <option value="inativa">Inativa</option>
        </select>
        <br><br>
        <button type="submit" name="adicionar">Adicionar Pergunta</button>
    </form>

    <h2>Perguntas Ativas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Texto</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($perguntas as $pergunta): ?>
            <tr>
                <td><?= $pergunta['id']; ?></td>
                <td><?= $pergunta['texto_pergunta']; ?></td>
                <td><?= $pergunta['status']; ?></td>
                <td>
                    <?php if ($pergunta['status'] == 'ativa'): ?>
                        <a href="painel.php?acao=desativar&id=<?= $pergunta['id']; ?>">Desativar</a>
                    <?php else: ?>
                        <a href="painel.php?acao=ativar&id=<?= $pergunta['id']; ?>">Ativar</a>
                    <?php endif; ?>
                    <a href="painel.php?acao=remover&id=<?= $pergunta['id']; ?>" onclick="return confirm('Tem certeza que deseja remover?')">Remover</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Respostas dos Usuários</h2>
    <?php
    // Buscar respostas
    $sql = "SELECT * FROM avaliacoes";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Pergunta</th>
            <th>Resposta</th>
            <th>Feedback</th>
        </tr>
        <?php foreach ($respostas as $resposta): ?>
            <tr>
                <td><?= $resposta['id']; ?></td>
                <td><?= $resposta['id_pergunta']; ?></td>
                <td><?= $resposta['resposta']; ?></td>
                <td><?= $resposta['feedback_textual']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
