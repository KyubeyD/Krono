// Fechar o modal quando clicar fora dele
window.onclick = function(event) {
    var modal = document.getElementById('modalCadastro');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }