<?php
// Iniciar a sessão (caso queira usar sessões mais tarde para outras funcionalidades)
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura a resposta selecionada e o feedback textual
    $resposta = $_POST['resposta']; // Resposta do usuário (0-10)
    $feedback = $_POST['feedback']; // Feedback textual (opcional)

    // Se você estiver usando um banco de dados, essa parte seria responsável por inserir os dados no banco
    // Aqui você pode incluir o arquivo de conexão e o código para inserir a avaliação no banco de dados
    include('.../src/db.php'); // Supondo que o arquivo db.php contenha a conexão com o banco

    // Exemplo de inserção no banco de dados (substitua os valores reais de acordo com seu banco de dados)
    $sql = "INSERT INTO avaliacoes (id_setor, id_pergunta, id_dispositivo, resposta, feedback_textual, data_hora_avaliacao) 
            VALUES (1, 1, 1, ?, ?, NOW())"; // Valores exemplo para setor, pergunta, dispositivo

    // Preparar a consulta
    if ($stmt = $conn->prepare($sql)) {
        // Associar os parâmetros
        $stmt->bind_param("is", $resposta, $feedback); // O tipo do parâmetro pode ser alterado conforme o banco de dados
        $stmt->execute(); // Executar a consulta
        $stmt->close(); // Fechar a declaração
    }

    // Redirecionar para a página de agradecimento
    header("Location: obrigado.php");
    exit(); // Garantir que o script pare após o redirecionamento
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
        <p class="question">Com base na sua experiência em nossa instituição, em uma escala de 0 a 10, o quão provável 
            você recomendaria nossos serviços a um amigo e/ou familiar?</p>
        
        <form action="index.php" method="POST"> <!-- O formulário agora envia os dados para o próprio index.php -->
            <div class="rating-scale">
                <div class="label-left">Improvável</div>
                <div class="buttons">
                    <button type="button" class="rating-button" data-value="0">0</button>
                    <button type="button" class="rating-button" data-value="1">1</button>
                    <button type="button" class="rating-button" data-value="2">2</button>
                    <button type="button" class="rating-button" data-value="3">3</button>
                    <button type="button" class="rating-button" data-value="4">4</button>
                    <button type="button" class="rating-button" data-value="5">5</button>
                    <button type="button" class="rating-button" data-value="6">6</button>
                    <button type="button" class="rating-button" data-value="7">7</button>
                    <button type="button" class="rating-button" data-value="8">8</button>
                    <button type="button" class="rating-button" data-value="9">9</button>
                    <button type="button" class="rating-button" data-value="10">10</button>
                </div>
                <div class="label-right">Muito Provável</div>
            </div>
            
            <input type="hidden" name="resposta" id="resposta" value="0"> <!-- Campo oculto para enviar a resposta -->
            
            <textarea name="feedback" class="feedback" placeholder="Escreva aqui seus comentários"></textarea>
            <button type="submit" class="submit-button">Enviar Avaliação</button>
        </form>
    </div>

    <script src="js/script.js"></script> <!-- Linkando o arquivo JavaScript externo -->
</body>
</html>

