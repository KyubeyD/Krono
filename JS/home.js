window.addEventListener("scroll", function() {
    var header = document.getElementById("header");
    if (window.scrollY > 450) { // Verifica se o scroll é maior que 50px
        header.classList.add("scrolled"); // Adiciona a classe "scrolled"
    } else {
        header.classList.remove("scrolled"); // Remove a classe quando volta ao topo
    }
});


const funcItem = document.querySelectorAll('.func-item');

// Adiciona a classe "visible" com delay para cada card
funcItem.forEach((card, index) => {
    setTimeout(() => {
        card.classList.add('visible');
    }, index * 300); // Atraso de 300ms entre cada card
});
