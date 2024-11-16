// Função para capturar a avaliação (0-10) e armazenar no campo oculto
function setRating(value) {
    document.getElementById('resposta').value = value;
    // Alterar a aparência dos botões (opcional) para refletir a seleção
    var buttons = document.querySelectorAll('.rating-button');
    buttons.forEach(function(button) {
        button.classList.remove('selected');
    });
    var selectedButton = document.querySelector('[data-value="' + value + '"]');
    selectedButton.classList.add('selected');
}

// Script para adicionar a seleção visual dos botões (manter o comportamento de "selected")
document.querySelectorAll('.rating-button').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.rating-button').forEach(btn => btn.classList.remove('selected'));
        button.classList.add('selected');
        setRating(button.dataset.value); // Atualiza o campo oculto com o valor selecionado
    });
});
