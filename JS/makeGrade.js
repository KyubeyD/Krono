// Seleciona todos os botões na grade
const gradeButtons = document.querySelectorAll('.btn-make-modal');
const gradeState = {}; 

// Função para abrir o modal e personalizar seu conteúdo
function openModal(button) {
    const modal = document.getElementById('modalCadastro');
    modal.style.display = 'block';

    // Obtém o horário a partir do ID do botão
    const horarioId = button.parentElement.id;
    const horario = horarioId.split('_');

    // Atualiza o título do modal com o horário
    document.querySelector('.modal-title').innerText = `Selecione a disciplina e o professor para o horário`;

    // Atualiza o data-tempo no botão de salvar para usar depois
    const saveButton = document.getElementById('btnSalvar');
    saveButton.setAttribute('data-tempo', horarioId); 

    // Atualiza os selects de disciplinas e professores
    const disciplinaSelect = document.getElementById('disciplinaSelect');
    const professorSelect = document.getElementById('professorSelect');
    
    // Limpa os selects anteriores
    professorSelect.innerHTML = '<option value="">Selecione o professor</option>';
    professorSelect.disabled = true;

    // Remove eventListeners antigos para evitar duplicação
    const oldDisciplinaSelect = disciplinaSelect.cloneNode(true);
    disciplinaSelect.parentNode.replaceChild(oldDisciplinaSelect, disciplinaSelect);

    // Adiciona novo listener para buscar professores
    oldDisciplinaSelect.addEventListener('change', () => {
        const disciplinaId = oldDisciplinaSelect.value;

        if (disciplinaId) {
            professorSelect.disabled = false; // Habilita o select de professores
            fetch(`PAGES/GRADE/fetchProfessores.php?idEscola=${idEscola}&dia=${horario[3]}&tempo=${horario[1]}&disciplinaId=${disciplinaId}`)
                .then(response => response.json())
                .then(professores => {
                    professorSelect.innerHTML = '<option value="">Selecione o professor</option>';
                    professores.forEach(professor => {
                        const option = document.createElement('option');
                        option.value = professor.id_professor;
                        option.textContent = professor.nome_professor;
                        professorSelect.appendChild(option);
                    });
                })
                .catch(err => {
                    console.error('Erro ao buscar professores:', err);
                    professorSelect.disabled = true;
                });
        } else {
            professorSelect.disabled = true; // Reabilita caso não haja disciplina selecionada
        }
    });
}

const tabelaDisciplinas = document.querySelectorAll('.disciplinas');
const tabelaProfessores = document.querySelectorAll('.professores'); 

function saveSelection() {
    const disciplinaSelect = document.getElementById('disciplinaSelect');
    const professorSelect = document.getElementById('professorSelect');
    const selectedDisciplina = disciplinaSelect.value; // ID da disciplina
    const selectedProfessor = professorSelect.value;

    if (!selectedDisciplina || !selectedProfessor) {
        alert("Selecione uma disciplina e um professor!");
        return;
    }

    const horarioSelecionado = document.getElementById('btnSalvar').getAttribute('data-tempo');
    const targetCell = document.getElementById(horarioSelecionado);

    if (targetCell) {
        const minutos = parseInt(targetCell.getAttribute('data-minutos'), 10); // Minutos da célula
        const disciplinaText = disciplinaSelect.options[disciplinaSelect.selectedIndex].text;
        const fullProfessorName = professorSelect.options[professorSelect.selectedIndex].text;
        const firstTwoNames = fullProfessorName.split(' ').slice(0, 2).join(' ');
        const inputHorario = horarioSelecionado.split('_');

        // Atualiza o conteúdo da célula
        targetCell.innerHTML = `
            <div class="disciplina-grade">${disciplinaText}</div>
            <div class="professor-grade">${firstTwoNames}</div>
            <input type="hidden" name="grade[]" value="${selectedDisciplina}_${selectedProfessor}_${inputHorario[1]}_${inputHorario[3]}_${inputHorario[5]}">
        `;

        // Atualiza a carga horária da disciplina e do professor
        atualizarCargaHoraria(selectedDisciplina, minutos, false); // Decrementa a carga horária da disciplina
        atualizarCargaHorariaProfessor(selectedProfessor, minutos, false); // Decrementa a carga horária do professor

        // Salva o estado atual da célula
        gradeState[horarioSelecionado] = {
            disciplina: disciplinaText,
            professor: firstTwoNames,
            inputValue: `${selectedDisciplina}_${selectedProfessor}_${horarioSelecionado}`
        };
    } else {
        alert("Erro ao localizar a célula do horário.");
    }

    document.getElementById('modalCadastro').style.display = 'none';
}

// Função para remover um professor atribuído a uma célula
function removeProfessor(cell) {
    const disciplinaDiv = cell.querySelector('.disciplina-grade');
    const professorDiv = cell.querySelector('.professor-grade');

    if (disciplinaDiv && professorDiv) {
        const disciplinaId = cell.querySelector('input[type="hidden"]').value.split('_')[0]; // Extrai o ID da disciplina
        const professorId = cell.querySelector('input[type="hidden"]').value.split('_')[1]; // Extrai o ID do professor
        const minutos = parseInt(cell.getAttribute('data-minutos'), 10); // Minutos da célula

        // Incrementa a carga horária ao remover a disciplina e o professor
        atualizarCargaHoraria(disciplinaId, minutos, true); // Incrementa a carga horária da disciplina
        atualizarCargaHorariaProfessor(professorId, minutos, true); // Incrementa a carga horária do professor

        // Remove os elementos da célula
        disciplinaDiv.remove();
        professorDiv.remove();

        // Exibe novamente o ícone de adição
        const addButton = document.createElement('div');
        addButton.className = 'btn-make-modal';
        addButton.innerHTML = "<i class='bx bx-plus'></i>";
        cell.appendChild(addButton);

        // Adiciona o evento de clique ao novo botão
        addButton.addEventListener('click', () => openModal(addButton));

        // Remove os inputs ocultos (se houver)
        const hiddenInput = cell.querySelector('input[type="hidden"]');
        if (hiddenInput) {
            hiddenInput.remove();
        }

        // Atualiza o estado da grade para refletir a remoção
        const horarioId = cell.id;
        if (gradeState[horarioId]) {
            delete gradeState[horarioId]; // Remove o estado do horário do objeto gradeState
        }
    }
}

// Função para atualizar a carga horária de uma disciplina
function atualizarCargaHoraria(disciplinaId, minutos, incrementar) {
    tabelaDisciplinas.forEach(row => {
        const id = row.getAttribute('data-disciplina'); // Obtém o ID da disciplina na tabela
        if (disciplinaId === id) {
            const cargaHorariaCell = row.children[2]; // Célula com a carga horária
            const porcentagemCell = row.children[3]; // Célula com a porcentagem

            const cargaHorariaTotal = parseFloat(row.getAttribute('data-ch')); // Total de horas da disciplina

            if (isNaN(cargaHorariaTotal) || cargaHorariaTotal <= 0) {
                console.error("A carga horária total não é válida!");
                return; // Se o total de horas não for válido, não faz o cálculo
            }

            const cargaHorariaAtual = parseFloat(cargaHorariaCell.textContent.replace('h', '').trim());
            const horas = minutos / 60; // Converte minutos para horas

            // Atualiza a carga horária com base na operação (incrementar ou decrementar)
            let novaCargaHoraria = incrementar
                ? cargaHorariaAtual + horas
                : cargaHorariaAtual - horas;

            // Limita a carga horária para não ultrapassar a carga horária total
            novaCargaHoraria = Math.min(novaCargaHoraria, cargaHorariaTotal);
            novaCargaHoraria = Math.max(0, novaCargaHoraria); // Garante que a carga horária não seja negativa

            // Remove a parte decimal ".0" se a carga horária for um número inteiro
            cargaHorariaCell.textContent = `${novaCargaHoraria % 1 === 0 ? novaCargaHoraria.toFixed(0) : novaCargaHoraria.toFixed(1)}h`;

            // Regra de 3 para calcular a porcentagem
            let novaPorcentagem = (novaCargaHoraria * 100) / cargaHorariaTotal;

            // Limita a porcentagem a no máximo 100%
            novaPorcentagem = Math.min(novaPorcentagem, 100);

            // Remove a parte decimal ".0" se a porcentagem for um número inteiro
            porcentagemCell.textContent = `${novaPorcentagem % 1 === 0 ? novaPorcentagem.toFixed(0) : novaPorcentagem.toFixed(1)}%`;

            return; // Sai do loop após encontrar e atualizar a disciplina
        }
    });
}

function atualizarCargaHorariaProfessor(professorId, minutos, incrementar) {
    tabelaProfessores.forEach(row => {
        const id = row.getAttribute('data-professor'); // Obtém o ID do professor
        if (professorId === id) {
            const cargaHorariaCell = row.children[1]; // A célula com a carga horária do professor (ajuste conforme necessário)
            const porcentagemCell = row.children[2]; // A célula com a porcentagem da carga horária

            const cargaHorariaTotal = parseFloat(row.getAttribute('data-hs')); // Total de horas que o professor pode dar

            if (isNaN(cargaHorariaTotal) || cargaHorariaTotal <= 0) {
                console.error("A carga horária total do professor não é válida!");
                return; // Se a carga horária total do professor não for válida, não faz o cálculo
            }

            const cargaHorariaAtual = parseFloat(cargaHorariaCell.textContent.replace('h', '').trim());
            const horas = minutos / 60; // Converte minutos para horas

            // Atualiza a carga horária com base na operação (incrementar ou decrementar)
            let novaCargaHoraria = incrementar
                ? cargaHorariaAtual + horas
                : cargaHorariaAtual - horas;

            // Garante que a carga horária do professor não ultrapasse a carga horária total
            novaCargaHoraria = Math.min(novaCargaHoraria, cargaHorariaTotal);
            novaCargaHoraria = Math.max(0, novaCargaHoraria); // Garante que a carga horária não seja negativa

            // Remove a parte decimal ".0" se a carga horária for um número inteiro
            cargaHorariaCell.textContent = `${novaCargaHoraria % 1 === 0 ? novaCargaHoraria.toFixed(0) : novaCargaHoraria.toFixed(1)}h`;

            // Regra de 3 para calcular a porcentagem
            let novaPorcentagem = (novaCargaHoraria * 100) / cargaHorariaTotal;

            // Limita a porcentagem a no máximo 100%
            novaPorcentagem = Math.min(novaPorcentagem, 100);

            // Remove a parte decimal ".0" se a porcentagem for um número inteiro
            porcentagemCell.textContent = `${novaPorcentagem % 1 === 0 ? novaPorcentagem.toFixed(0) : novaPorcentagem.toFixed(1)}%`;

            return; // Sai do loop após encontrar e atualizar o professor
        }
    });
}

function restaurarEstadoInicial(grade, button) {
    grade.querySelectorAll('.input-tempo').forEach(cell => {
        const cellId = cell.id;

        if (gradeState[cellId]) {
            // Restaura o estado salvo
            const { disciplina, professor, inputValue } = gradeState[cellId];
            cell.innerHTML = `
                <div class="disciplina-grade">${disciplina}</div>
                <div class="professor-grade">${professor}</div>
                <input type="hidden" name="grade[]" value="${inputValue}">
            `;
        } else {
            // Reseta células sem estado
            cell.innerHTML = '';
            const addButton = document.createElement('div');
            addButton.className = 'btn-make-modal';
            addButton.innerHTML = "<i class='bx bx-plus'></i>";
            addButton.addEventListener('click', () => openModal(addButton));
            cell.appendChild(addButton);
        }
        cell.style.backgroundColor = ''; // Remove cores de destaque
        cell.style.color = '#03060f';
        cell.style.fontWeight = '400';
    });

    button.textContent = 'Ver';
}
// Função que mostra a disponibilidade do professor
//antes
let ultimoBotaoVoltar = null; // Variável global para armazenar o último botão "Voltar" clicado
// Variável global para armazenar o último botão "Voltar" clicado

function mostrarDisponibilidadeProfessor(idProfessor, button) {
    const grade = document.querySelector('.grade');
    const maxProfessores = 3; // Limitar ao máximo de 3 professores por vez

    // Armazena os botões originais de professores para restaurá-los depois
    const botoesProfessores = grade.querySelectorAll('.btn-make-modal');

    // Função para restaurar todos os botões para "Ver"
    function restaurarBotoes() {
        botoesProfessores.forEach(btn => {
            btn.classList.remove('btn-hidden');
            btn.textContent = 'Ver'; // Garante que todos os botões sejam "Ver"
        });
    }

    // Função para restaurar o estado inicial da grade
    function restaurarEstadoGrade() {
        grade.querySelectorAll('.input-tempo').forEach(cell => {
            cell.style.backgroundColor = '';
            cell.style.color = '#03060f';
            cell.style.fontWeight = '400';
            cell.textContent = '';
        });
    }

    // Se o botão for "Voltar", ele vai restaurar o estado da grade e dos botões
    if (button.textContent === 'Voltar') {
        restaurarBotoes();
        restaurarEstadoGrade();
        button.textContent = 'Ver'; // Troca o "Voltar" de volta para "Ver"
        ultimoBotaoVoltar = null; // Limpa a variável global
        return;
    }

    // Esconde todos os botões de grade e altera o texto deles para "Ver"
    botoesProfessores.forEach(btn => {
        btn.classList.add('btn-hidden');
        btn.textContent = 'Ver'; // Garante que todos os botões sejam "Ver"
    });

    // Se houver um botão "Voltar" anterior, restaura-o para "Ver"
    if (ultimoBotaoVoltar) {
        ultimoBotaoVoltar.textContent = 'Ver';
    }

    // Altera o texto do botão atual para "Voltar"
    button.textContent = 'Voltar';
    ultimoBotaoVoltar = button; // Armazena o botão "Voltar" atual

    // Mostra a disponibilidade do professor
    fetch(`PAGES/GRADE/fetchDispProf.php?idProfessor=${idProfessor}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Objeto para armazenar as disponibilidades dos professores por tempo/dia/turno
            const disponibilidades = {};

            // Preenche o objeto com as disponibilidades agrupadas
            data.forEach(item => {
                const key = `tempo_${item.tempo}_dia_${item.dia_semana}_turno_${item.turno}`;
                if (!disponibilidades[key]) {
                    disponibilidades[key] = [];
                }
                // Adiciona o nome do professor ao grupo de disponibilidades
                if (disponibilidades[key].length < maxProfessores) {
                    disponibilidades[key].push(item.nomeProfessor);
                }
            });

            // Limpa a grade antes de adicionar os novos professores
            restaurarEstadoGrade();

            // Agora adiciona os nomes dos professores
            Object.keys(disponibilidades).forEach(key => {
                const cell = document.getElementById(key);
                if (cell) {
                    const professores = disponibilidades[key].join(' / '); // Junta os nomes dos professores com barra
                    cell.style.backgroundColor = '#F39F1B';
                    cell.style.color = '#FFF';
                    cell.style.fontWeight = '500';
                    cell.textContent = professores;
                }
            });
        })
        .catch(err => console.error('Erro ao buscar disponibilidade:', err));
}
document.querySelectorAll('.viewDispBtn').forEach(button => {
    button.addEventListener('click', () => {
        const idProfessor = button.getAttribute('data-id-professor');

        // Verifica o texto atual do botão
        if (button.textContent === 'Ver') {
            mostrarDisponibilidadeProfessor(idProfessor, button);
        } else if (button.textContent === 'Voltar') {
            // Se o botão estiver em "Voltar", apenas reverte a grade
            restaurarEstadoInicial(document.querySelector('.grade'), button);
        }
    });
});

// Adiciona um evento de clique para cada botão da grade
gradeButtons.forEach(button => {
    button.addEventListener('click', () => openModal(button));
});

// Evento para fechar o modal ao clicar fora dele
window.onclick = function(event) {
    const modal = document.getElementById('modalCadastro');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

// Botão de fechar modal
const closeModal = document.querySelector('.close');
closeModal.addEventListener('click', () => {
    document.getElementById('modalCadastro').style.display = 'none';
});

document.querySelectorAll('.input-tempo').forEach(cell => {
    cell.addEventListener('click', function () {
        removeProfessor(this);
    });
});

