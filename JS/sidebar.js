document.addEventListener('DOMContentLoaded', function() {
    const tabelasToggle = document.querySelector('.sidebar-toggle[data-toggle="tabelas"]');
    const subSide = document.querySelector('#sub-side');
    
    if (tabelasToggle) {
        tabelasToggle.addEventListener('click', function() {
            subSide.classList.toggle('open');
            tabelasToggle.classList.toggle('active');
        });
    }
});
