function redirectToPage() {
    const select = document.getElementById('emissao');
    const selectedValue = select.value;

    if (selectedValue) {
        window.location.href = selectedValue; // Redireciona para a página selecionada
    }
}