<?php
// Incluir o arquivo de conexão ao banco
include(__DIR__ . '/../src/db.php');

// Recupera o número da pergunta atual a partir do parâmetro GET ou define como 1 se não existir
$pergunta_atual = isset($_GET['pergunta_atual']) ? (int)$_GET['pergunta_atual'] : 1;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura a resposta e o feedback enviados pelo formulário
    $resposta = isset($_POST['resposta']) ? (int)$_POST['resposta'] : null;
    $feedback = isset($_POST['feedback']) ? $_POST['feedback'] : null;

    try {
        // Verifica se a pergunta atual existe e está ativa
        $sql = "SELECT id FROM perguntas WHERE id = :id_pergunta AND status = 'ativa'";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_pergunta', $pergunta_atual, PDO::PARAM_INT);
        $stmt->execute();
        $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pergunta) {
            // Caso a pergunta não exista ou não esteja ativa, buscar a próxima pergunta ativa
            $sql = "SELECT id FROM perguntas WHERE status = 'ativa' AND id > :id_pergunta ORDER BY id LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id_pergunta', $pergunta_atual, PDO::PARAM_INT);
            $stmt->execute();
            $proxima_pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($proxima_pergunta) {
                // Atualiza o número da pergunta na URL
                $pergunta_atual = $proxima_pergunta['id'];
            } else {
                // Se não houver mais perguntas ativas, redireciona para a página de agradecimento
                header("Location: obrigado.php");
                exit();
            }
        }

        // Inserir a resposta e o feedback no banco de dados
        $sql = "INSERT INTO avaliacoes (id_setor, id_pergunta, id_dispositivo, resposta, feedback_textual, data_hora_avaliacao) 
                VALUES (:id_setor, :id_pergunta, :id_dispositivo, :resposta, :feedback_textual, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_setor', 1, PDO::PARAM_INT); 
        $stmt->bindValue(':id_pergunta', $pergunta_atual, PDO::PARAM_INT); 
        $stmt->bindValue(':id_dispositivo', 1, PDO::PARAM_INT); 
        $stmt->bindValue(':resposta', $resposta, PDO::PARAM_INT);
        $stmt->bindValue(':feedback_textual', $feedback, PDO::PARAM_STR);
        $stmt->execute();

        // Após salvar a resposta, busca a próxima pergunta
        $sql = "SELECT id, texto_pergunta FROM perguntas WHERE status = 'ativa' AND id > :id_pergunta ORDER BY id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_pergunta', $pergunta_atual, PDO::PARAM_INT);
        $stmt->execute();
        $proxima_pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se houver uma próxima pergunta, mantém o valor na URL
        if ($proxima_pergunta) {
            $pergunta_atual = $proxima_pergunta['id'];
        } else {
            // Se não houver mais perguntas ativas, redireciona para a página de agradecimento
            header("Location: obrigado.php");
            exit();
        }
    } catch (PDOException $e) {
        // Exibir erro em caso de falha
        echo "Erro ao salvar a avaliação: " . $e->getMessage();
        exit();
    }
}

// Buscar a pergunta atual do banco de dados
$sql = "SELECT id, texto_pergunta FROM perguntas WHERE id = :id_pergunta AND status = 'ativa' LIMIT 1"; // Limita a uma pergunta ativa
$stmt = $conn->prepare($sql);
$stmt->bindValue(':id_pergunta', $pergunta_atual, PDO::PARAM_INT);
$stmt->execute();
$pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não houver perguntas ativas, exibe uma mensagem
if (!$pergunta) {
    // Buscar a próxima pergunta ativa
    $sql = "SELECT id, texto_pergunta FROM perguntas WHERE status = 'ativa' ORDER BY id ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pergunta) {
        $pergunta_atual = $pergunta['id'];
    } else {
        // Se não houver perguntas ativas, redireciona para a página de agradecimento
        header("Location: obrigado.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação Hospitalar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Sistema de Avaliação Hospitalar</h1>

        <!-- Exibe a pergunta dinamicamente -->
        <?php if ($pergunta): ?>
            <p class="question"><?= htmlspecialchars($pergunta['texto_pergunta']) ?></p>
        <?php else: ?>
            <p class="question">Nenhuma pergunta ativa no momento.</p>
        <?php endif; ?>

        <form action="index.php?pergunta_atual=<?= $pergunta_atual ?>" method="POST">
            <div class="rating-scale">
                <div class="label-left">Improvável</div>
                <div class="buttons">
                    <?php for ($i = 0; $i <= 10; $i++): ?>
                        <button type="button" class="rating-button" data-value="<?= $i ?>"><?= $i ?></button>
                    <?php endfor; ?>
                </div>
                <div class="label-right">Muito Provável</div>
            </div>
            
            <input type="hidden" name="resposta" id="resposta" value="0"> 
            
            <textarea name="feedback" class="feedback" placeholder="Escreva aqui seus comentários"></textarea>

            <button type="submit" class="submit-button">
                <?= $pergunta ? 'Próxima Pergunta' : 'Enviar Avaliação' ?>
            </button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>