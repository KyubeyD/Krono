document.addEventListener('DOMContentLoaded', () => {
    const availabilityBlocks = document.querySelectorAll('.availability-block');

    availabilityBlocks.forEach(block => {
        const originalText = block.innerText; // Armazena o conteúdo original do bloco
        const checkbox = block.nextElementSibling; // Seleciona o checkbox que fica logo após o bloco

        block.addEventListener('click', function() {
            const allBlocks = this.parentNode.querySelectorAll('.availability-block');
            
            if (this.classList.contains('expanded')) {
                // Retorna ao estado inicial, mostra todos os blocos e desmarca o checkbox
                allBlocks.forEach(sibling => {
                    sibling.classList.remove('expanded');
                    sibling.style.display = 'inline-block';
                    sibling.innerText = originalText; // Volta ao conteúdo original
                });
                checkbox.checked = false;
            } else {
                // Oculta os outros blocos, expande o selecionado e muda o texto para o conteúdo por extenso
                allBlocks.forEach(sibling => {
                    sibling.style.display = 'none';
                });
                this.style.display = 'inline-block';
                this.classList.add('expanded');

                // Define o texto por extenso para cada tipo de bloco
                if (originalText === 'P') {
                    this.innerText = 'Primeira Opção';
                } else if (originalText === 'C') {
                    this.innerText = 'C. Estudos';
                } else if (originalText === 'D') {
                    this.innerText = 'Demais';
                }

                checkbox.checked = true;
            }
        });
    });
});
