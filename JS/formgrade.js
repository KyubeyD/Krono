document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formCadastro');
    const submitBtn = document.getElementById('submitBtn');

    const checkInputs = () => {
        const inputs = form.querySelectorAll('input[required], select[required]');
        const allFilled = Array.from(inputs).every(input => input.value.trim() !== '' && input.value !== ''); // Verifica se todos os campos estão preenchidos
        
        if (allFilled) {
            submitBtn.disabled = false;
            submitBtn.style.backgroundColor = ''; // Reseta a cor do botão
            submitBtn.style.cursor = 'pointer'; // Altera o cursor
        } else {
            submitBtn.disabled = true;
            submitBtn.style.backgroundColor = 'grey'; // Mantém a cor desabilitada
            submitBtn.style.cursor = 'not-allowed'; // Mantém o cursor
        }
    };

    // Adiciona evento de mudança nos inputs
    form.addEventListener('input', checkInputs);
    form.addEventListener('change', checkInputs); // Para os selects
});

function calcularTempos() {
    // Pegar os valores da carga horária e divisão de tempos
    let cargaHoraria = parseInt(document.getElementById('carga_diaria').value);
    let divisaoTempos = parseInt(document.getElementById('divisao').value);
    
    // Se qualquer valor for inválido, sair da função
    if (!cargaHoraria || !divisaoTempos) return;

    // Cálculo do total de minutos de carga horária
    let totalMinutos = cargaHoraria * 60;

    // Cálculo da quantidade de tempos
    let quantidadeTempos = Math.floor(totalMinutos / divisaoTempos);

    return quantidadeTempos;
}

function gerarSelectsIntervalos() {
    // Pegar a quantidade de intervalos
    let qtdIntervalos = parseInt(document.getElementById('qtd_intervalo').value);
    let quantidadeTempos = calcularTempos();

    // Pegar a div onde os selects serão inseridos
    let intervaloSelects = document.getElementById('intervalo-selects');
    intervaloSelects.innerHTML = ''; // Limpa a div atual

    // Pegar a div onde os inputs serão inseridos
    let minutosIntervaloDiv = document.getElementById('minutos-intervalo');
    minutosIntervaloDiv.innerHTML = ''; // Limpa a div atual

    // Se a quantidade de tempos for válida e houver intervalos
    if (quantidadeTempos > 0 && qtdIntervalos > 0) {
        for (let i = 1; i <= qtdIntervalos; i++) {
            // Criação dos selects
            let campoDiv = document.createElement('div');
            campoDiv.classList.add('campo');

            let label = document.createElement('label');
            label.classList.add('modal-label');
            label.innerText = "Intervalo " + i + " em qual tempo?";

            let select = document.createElement('select');
            select.name = `tempo_intervalo[]`;  // Array de selects
            select.id = `tempo_intervalo_${i}`;
            select.classList.add('modal-form');

            // Preenche o select com as opções dos tempos disponíveis
            for (let j = 1; j <= quantidadeTempos; j++) {
                let option = document.createElement('option');
                option.value = j;
                option.text = j + "º tempo";
                select.appendChild(option);
            }

            campoDiv.appendChild(label);
            campoDiv.appendChild(select);
            intervaloSelects.appendChild(campoDiv);

            // Criação dos inputs de minutos por intervalo
            let minutosDiv = document.createElement('div');
            minutosDiv.classList.add('campo');

            let minutosLabel = document.createElement('label');
            minutosLabel.classList.add('modal-label');
            minutosLabel.innerText = "Duração do intervalo " + i + " (minutos):";

            let minutosInput = document.createElement('input');
            minutosInput.type = 'number';
            minutosInput.name = `minutos_intervalo[]`;  // Array de inputs para os minutos
            minutosInput.id = `minutos_intervalo_${i}`;
            minutosInput.classList.add('modal-form');
            minutosInput.min = 1; // Definir um valor mínimo para o intervalo
            minutosInput.required = true; // Tornar o campo obrigatório

            minutosDiv.appendChild(minutosLabel);
            minutosDiv.appendChild(minutosInput);
            minutosIntervaloDiv.appendChild(minutosDiv);
        }
    }
}

// Detectar mudanças nos campos e recalcular automaticamente
document.getElementById('carga_diaria').addEventListener('input', gerarSelectsIntervalos);
document.getElementById('divisao').addEventListener('input', gerarSelectsIntervalos);
document.getElementById('qtd_intervalo').addEventListener('input', gerarSelectsIntervalos);
