<?php
// Exibir todas as respostas no painel administrativo
include('HRV_Trabalho/src/db.php'); // Conectar ao banco de dados

$sql = "SELECT * FROM avaliacoes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " - Resposta: " . $row['resposta'] . " - Feedback: " . $row['feedback_textual'] . "<br>";
    }
} else {
    echo "Nenhuma avaliação encontrada.";
}
?>
