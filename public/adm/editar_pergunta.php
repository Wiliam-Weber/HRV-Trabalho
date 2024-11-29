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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Editar Pergunta</title>
</head>
<body>
    <h1>Editar Pergunta</h1>

    <!-- Formulário para editar pergunta -->
    <form action="editar_pergunta.php" method="POST">
        <input type="hidden" name="id_pergunta" value="<?php echo $pergunta['id']; ?>">
        <label for="texto_pergunta">Texto da Pergunta</label>
        <input type="text" name="texto_pergunta" value="<?php echo $pergunta['texto_pergunta']; ?>" required>
        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="painel_adm.php">Voltar ao Painel Administrativo</a>
</body>
</html>
