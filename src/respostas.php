<?php
// Processar a resposta e feedback enviado pelo formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resposta = $_POST['resposta']; // Resposta do usuário
    $feedback = $_POST['feedback']; // Feedback textual

    include('.../src/db.php'); // Conectar ao banco de dados

    $sql = "INSERT INTO avaliacoes (id_setor, id_pergunta, id_dispositivo, resposta, feedback_textual, data_hora_avaliacao) 
            VALUES (1, 1, 1, ?, ?, NOW())"; // Insira dados com valores fixos (ajuste conforme necessário)

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $resposta, $feedback); // Bind da resposta e feedback
        $stmt->execute();
        $stmt->close();
    }

    // Redirecionar para página de agradecimento
    header("Location: obrigado.php");
    exit();
}
