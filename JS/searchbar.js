var data = [
    { name: 'Grades horárias', url: 'grades.html' },
    { name: 'Professor', url: '/project-krono/index.php?page=professores' },
    { name: 'Turmas', url: '/project-krono/index.php?page=turmas' },
    { name: 'Cursos', url: '/project-krono/index.php?page=cursos' },
    { name: 'Disciplinas', url: '/project-krono/index.php?page=disciplinas' },
    { name: 'Suporte', url: 'suporte.html' },
    { name: 'Logout', url: '/project-krono/index.php?page=logout' },
    { name: 'Cadastrar Grade', url: '?page=meu_horario_escola' },
    { name: 'Meu horário', url: '/project-krono/index.php?page=meu_horario_escola' },
];

const searchInput = document.getElementById('search');
const suggestionsContainer = document.getElementById('suggestions');

searchInput.addEventListener('input', function() {
    const query = searchInput.value.toLowerCase();
    suggestionsContainer.innerHTML = '';
    suggestionsContainer.style.display = 'none';

    if (query) {
        const filteredData = data.filter(item => item.name.toLowerCase().includes(query));
        filteredData.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.name;
            li.addEventListener('click', () => {
                redirectTo(item.url); // Redireciona ao clicar no item
            });
            suggestionsContainer.appendChild(li);
        });
        
        if (filteredData.length > 0) {
            suggestionsContainer.style.display = 'block'; // Exibe sugestões se houver
        }
    }
});

// Função para redirecionar
function redirectTo(url) {
    window.location.href = url; // Redireciona para a URL correspondente
}

// Redirecionar ao pressionar a tecla Enter
searchInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        const query = searchInput.value.toLowerCase();
        const selectedItem = data.find(item => item.name.toLowerCase() === query);
        if (selectedItem) {
            redirectTo(selectedItem.url); // Redireciona se o item corresponder
        }
    }
});
