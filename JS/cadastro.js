// Mudança de conteúdo entre Professor e Instituição
const quadro = document.querySelector('.quadro');
const btnChange = document.querySelector('#btn-change');
const icon = document.querySelector('.conteudo i');
const title = document.querySelector('.conteudo h1');
const paragraph = document.querySelector('.conteudo p');

btnChange.addEventListener('click', () => {
    quadro.classList.toggle('active');  

    if (quadro.classList.contains('active')) {
        // Alterar para conteúdo de professores
        icon.classList.replace('bxs-school', 'bxs-graduation');
        title.textContent = 'Professores';
        paragraph.textContent = 'Gostaria de criar ou gerenciar o seu horário como professor?';
    } else {
        // Alterar para conteúdo de instituição
        icon.classList.replace('bxs-graduation', 'bxs-school');
        title.textContent = 'Escolas';
        paragraph.textContent = 'Quer organizar as grades escolares de sua Instituição?';
    }
});

// Paginação do cadastro da escola
const btnProximoEscola = document.getElementById('btn-proximo');
const btnVoltarEscola = document.getElementById('btn-voltar');
const pagesEscola = [
    document.getElementById('page-1'),
    document.getElementById('page-2'),
    document.getElementById('page-3'),
    document.getElementById('page-4')
];
const progressBarEscola = document.getElementById('progress-escola');
const circlesEscola = [
    document.getElementById('circle1-escola'),
    document.getElementById('circle2-escola'),
    document.getElementById('circle3-escola'),
    document.getElementById('circle4-escola')
];

function disableButton(button) {
    button.classList.add('disabled');
    button.disabled = true;
}

function enableButton(button) {
    button.classList.remove('disabled');
    button.disabled = false;
}

let currentPageEscola = 0;

btnProximoEscola.addEventListener('click', () => {
    if (currentPageEscola < pagesEscola.length - 1) {
        // Ir para a próxima página
        pagesEscola[currentPageEscola].style.display = 'none';
        currentPageEscola++;
        pagesEscola[currentPageEscola].style.display = 'flex';
        btnVoltarEscola.style.display = 'block';
        enableButton(btnVoltarEscola);
        if (currentPageEscola === pagesEscola.length - 1) {
            btnProximoEscola.style.display = 'none'; // Oculta o botão "Próximo"
            document.getElementById('btn-cadastro').style.display = 'block'; // Exibe o botão "Cadastrar"
            progressBarEscola.style.width = '100%';
        } else {
            btnProximoEscola.textContent = 'Próximo';
            progressBarEscola.style.width = ((currentPageEscola + 1) / pagesEscola.length) * 100 + '%';
        }
        circlesEscola[currentPageEscola - 1].style.backgroundColor = '#F39F1B';
        circlesEscola[currentPageEscola - 1].style.color = '#f5f8ff';
    } else {
        // Aqui seria o submit do formulário
        document.getElementById('form-escola').submit();
    }
});

btnVoltarEscola.addEventListener('click', () => {
    if (currentPageEscola > 0) {
        // Voltar para a página anterior
        pagesEscola[currentPageEscola].style.display = 'none';
        currentPageEscola--;
        pagesEscola[currentPageEscola].style.display = 'flex';
        if (currentPageEscola === 0) {
            disableButton(btnVoltarEscola);
        }
        btnProximoEscola.style.display = 'block'; // Mostra o botão "Próximo" novamente
        document.getElementById('btn-cadastro').style.display = 'none'; // Oculta o botão "Cadastrar"
        progressBarEscola.style.width = ((currentPageEscola + 1) / pagesEscola.length) * 100 + '%';
        circlesEscola[currentPageEscola + 1].style.backgroundColor = '#e0e0e0';
        circlesEscola[currentPageEscola + 1].style.color = '#03060f';
    }
});

// Paginação do cadastro do professor
const btnProximoProfessor = document.getElementById('btn-proximo-professor');
const btnVoltarProfessor = document.getElementById('btn-voltar-professor');
const page1Professor = document.getElementById('page-1-professor');
const page2Professor = document.getElementById('page-2-professor');
const progressBarProfessor = document.getElementById('progress-professor');
const circlesProfessor = [document.getElementById('circle1-professor'), document.getElementById('circle2-professor')];

btnProximoProfessor.addEventListener('click', () => {
    if (page1Professor.style.display !== 'none') {
        // Ir para a segunda página
        page1Professor.style.display = 'none';
        page2Professor.style.display = 'flex';
        btnVoltarProfessor.style.display = 'block';
        enableButton(btnVoltarProfessor);
        btnProximoProfessor.style.display = 'none'; // Oculta o botão "Próximo"
        document.getElementById('btn-cadastro-prof').style.display = 'block'; // Exibe o botão "Cadastrar"
        progressBarProfessor.style.width = '100%';
        circlesProfessor[0].style.backgroundColor = '#F39F1B';
        circlesProfessor[0].style.color = '#f5f8ff';
        circlesProfessor[1].style.backgroundColor = '#F39F1B';
        circlesProfessor[1].style.color = '#f5f8ff';
    } else {
        // Aqui seria o submit do formulário
        document.getElementById('form-professor').submit();
    }
});

btnVoltarProfessor.addEventListener('click', () => {
    if (page2Professor.style.display !== 'none') {
        // Voltar para a primeira página
        page2Professor.style.display = 'none';
        page1Professor.style.display = 'flex';
        disableButton(btnVoltarProfessor);
        btnProximoProfessor.style.display = 'block'; // Mostra o botão "Próximo" novamente
        document.getElementById('btn-cadastro-prof').style.display = 'none'; // Oculta o botão "Cadastrar"
        progressBarProfessor.style.width = '50%';
        circlesProfessor[1].style.backgroundColor = '#e0e0e0';
        circlesProfessor[1].style.color = '#03060f';
    }
});
