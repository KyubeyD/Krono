document.addEventListener('DOMContentLoaded', () => {
    const cargaHorariaSelect = document.getElementById('carga_horaria_semanal');
    const selectElements = document.querySelectorAll('.horario-select');
    const temposRestantesElement = document.querySelectorAll('.restante');
    
    let totalSlots = { 'P': 0, 'D': 0, 'C': 0 };

    // Função para definir os slots totais
    function setTotalSlots() {
        switch (cargaHorariaSelect.value) {
            case '20':
                totalSlots = { 'P': 12, 'D': 4, 'C': 4 };
                break;
            case '40':
                totalSlots = { 'P': 24, 'D': 4, 'C': 12 };
                break;
            case '60':
                totalSlots = { 'P': 36, 'D': 8, 'C': 16 };
                break;
            default:
                totalSlots = { 'P': 0, 'D': 0, 'C': 0 };
        }
        updateRemainingSlots();
        console.log(totalSlots)
    }

    // Função para atualizar os slots restantes
    function updateRemainingSlots() {
        let usedSlots = { 'P': 0, 'D': 0, 'C': 0 };

        // Contar os slots usados
        selectElements.forEach(select => {
            const selectedValue = select.value;
            if (selectedValue) {
                const tipo = selectedValue.split('_')[4]; // P, D ou C
                usedSlots[tipo]++;
            }
        });

        console.log(usedSlots)

        // Atualizar porcentagens restantes
        Object.keys(usedSlots).forEach(key => {
            let percentage = 100;
            if (totalSlots[key] > 0) {
                percentage = Math.max(0, ((totalSlots[key] - usedSlots[key]) / totalSlots[key]) * 100);
            }

            // Corrigir a exibição das porcentagens
            temposRestantesElement[key === 'P' ? 0 : key === 'C' ? 1 : 2].textContent = `${percentage.toFixed(0)}%`;
        });

        // Gerenciar a visibilidade das opções nos selects
        selectElements.forEach(select => {
            const options = select.querySelectorAll('option:not([value=""])');
            options.forEach(option => {
                const tipo = option.value.split('_')[4];
                const isSelectedElsewhere = Array.from(selectElements).some(sel => 
                    sel !== select && sel.value === option.value
                );

                // Ocultar ou mostrar opções com base nos slots
                if (usedSlots[tipo] >= totalSlots[tipo] || isSelectedElsewhere) {
                    option.style.display = 'none'; // Esconde a opção se os slots estiverem completos
                } else {
                    option.style.display = ''; // Exibe a opção
                }
            });
        });
    }

    // Função para tratar a mudança de seleção
    function handleSelectChange(event) {
        const selectedValue = event.target.value;
        const cell = event.target.closest('td');

        // Resetar cor do fundo
        cell.style.backgroundColor = '';
        if (selectedValue.includes('_P')) cell.style.backgroundColor = '#4CAF50';
        if (selectedValue.includes('_D')) cell.style.backgroundColor = '#e49a23';
        if (selectedValue.includes('_C')) cell.style.backgroundColor = '#D32F2F';

        updateRemainingSlots();
    }

    // Função para desabilitar os selects
    function disableSelects(disable) {
        selectElements.forEach(select => select.disabled = disable);
    }

    cargaHorariaSelect.addEventListener('change', () => {
        setTotalSlots();
        disableSelects(false);
    });

    selectElements.forEach(select => {
        // Adicionar a opção vazia se não existir
        if (!select.querySelector('option[value=""]')) {
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = 'Selecione...';
            select.prepend(emptyOption);
        }

        select.addEventListener('change', handleSelectChange);
    });

    // Inicializa desabilitando os selects
    disableSelects(true); // Inicialmente desativa todos os selects
});