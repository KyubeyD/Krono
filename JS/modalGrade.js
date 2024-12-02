function openModal(button) {
    const modal = document.getElementById('modalCadastro');
    modal.style.display = 'block';

    const horarioId = button.parentElement.id;

    // Atualiza o título do modal
    document.querySelector('.modal-title').innerText = `Selecione um professor para o horário: ${horarioId}`;

    // Requisição AJAX para buscar professores disponíveis
    fetch(`PAGES/GRADE/fetchProfessores.php?tempo=${horarioId}`)
        .then(response => response.json())
        .then(professores => {
            const select = document.getElementById('professorSelect');
            select.innerHTML = ''; // Limpa as opções anteriores

            // Adiciona as opções dos professores disponíveis
            professores.forEach(professor => {
                const option = document.createElement('option');
                option.value = professor.id_professor;
                option.textContent = professor.nome_professor;
                select.appendChild(option);
            });
        })
        .catch(err => console.error('Erro ao buscar professores:', err));
}
