<?php
// perguntas.php - Funções de gerenciamento de perguntas

include 'src/db.php';

function getPerguntas() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM perguntas WHERE status = 'ativa'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
