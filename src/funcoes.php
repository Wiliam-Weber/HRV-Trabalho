<?php
// funcoes.php - Funções auxiliares

function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}
?>
