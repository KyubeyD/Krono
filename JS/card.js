// Seleciona todos os botões "visible"
const visibleButtons = document.querySelectorAll('.visible');

// Adiciona um evento de clique a cada botão "visible"
visibleButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Seleciona o card pai do botão clicado
        const card = this.closest('.card');
        
        // Adiciona ou remove a classe 'open' no card
        card.classList.toggle('open');
    });
});
