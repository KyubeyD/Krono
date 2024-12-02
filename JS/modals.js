window.onclick = function(event) {
    var modalRegra = document.getElementById('modalCadastroRegra');
    var modalCarga = document.getElementById('modalCadastroCarga');

    if (event.target == modalRegra) {
        modalRegra.style.display = "none";
    } else if (event.target == modalCarga) {
        modalCarga.style.display = "none";
    }
}