<?php
require_once('../ASSETS/conexao.php'); // Certifique-se de que o caminho está correto

// Consulta para obter as disciplinas e seus respectivos professores
try {
    // Consulta para obter os nomes dos professores e disciplinas
    $query = "SELECT 
                p.id_professor AS id_professor,
                p.nome_professor, 
                d.id_disciplina AS id_disciplina,
                d.nome_disciplina 
              FROM 
                vinculo_prof_disc v
              JOIN 
                professor p ON v.id_professor = p.id_professor
              JOIN 
                disciplina d ON v.id_disciplina = d.id_disciplina";

    $stmt = $conexao->prepare($query);
    $stmt->execute();
    
    // Buscar os resultados
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($materias);
    
    // Agrupar matérias por professor
    $disciplinasPorProfessor = [];
    foreach ($materias as $materia) {
        $disciplinasPorProfessor[$materia['id_professor']]['nome_professor'] = $materia['nome_professor'];
        $disciplinasPorProfessor[$materia['id_professor']]['disciplinas'][] = [
            'id' => $materia['id_disciplina'],
            'nome' => $materia['nome_disciplina']
        ];
    }

    // Para preencher o select, pegamos todas as disciplinas disponíveis
    $queryDisciplinas = "SELECT id_disciplina AS id_disciplina, nome_disciplina FROM disciplina";
    $stmtDisciplinas = $conexao->prepare($queryDisciplinas);
    $stmtDisciplinas->execute();
    $todasDisciplinas = $stmtDisciplinas->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizador de Horários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
        }
        .left-panel {
            width: 200px;
            padding: 10px;
            background-color: #f0f0f0;
            border-right: 2px solid #ccc;
        }
        .left-panel h3 {
            text-align: center;
        }
        .left-panel ul {
            list-style: none;
            padding: 0;
        }
        .left-panel ul li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            border-radius: 4px;
        }
        .schedule-table {
            margin-left: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
        }
        .schedule-table th {
            background-color: #6c3bd3;
            color: white;
        }
        .schedule-table td {
            background-color: #f9f9f9;
        }
        .schedule-table button {
            background-color: #6c3bd3;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 20px;
            cursor: pointer;
        }
        /* Modal Style */
        #modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        #modal h3 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Painel Esquerdo com as disciplinas -->
    <div class="left-panel">
        <h3>Disciplinas</h3>
        <ul>
            <?php foreach ($disciplinasPorProfessor as $professor): ?>
                <li>
                    <?php echo htmlspecialchars($professor['nome_professor']); ?>
                    <span><?php echo count($professor['disciplinas']); ?> disciplinas</span>
                    <ul>
                        <?php foreach ($professor['disciplinas'] as $disciplina): ?>
                            <li><?php echo htmlspecialchars($disciplina['nome']); ?> <span>60h</span></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Tabela de Horários -->
    <table class="schedule-table">
        <thead>
            <tr>
                <th>Horários</th>
                <th>Segunda</th>
                <th>Terça</th>
                <th>Quarta</th>
                <th>Quinta</th>
                <th>Sexta</th>
                <th>Sábado</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    $horarios = [
        '07:00 - 08:40',
        '08:40 - 10:20',
        '10:30 - 12:00',
        '13:00 - 14:40',
        '14:40 - 16:20',
        '16:30 - 18:00'
    ];
    foreach ($horarios as $horario): ?>
    <tr>
        <td><?php echo $horario; ?></td>
        <?php for ($dia = 0; $dia < 6; $dia++): ?>
            <td id="cell_<?php echo $horario . '_' . $dia; ?>">
                <button type="button" id="button_<?php echo $horario . '_' . $dia; ?>" onclick="openModal('<?php echo $horario; ?>', '<?php echo $dia; ?>')">+</button>
            </td>
        <?php endfor; ?>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>

<!-- Modal para Seleção de Disciplina -->
<div id="modal">
    <h3>Selecione a Disciplina para o horário</h3>
    <select id="disciplinaSelect">
        <!-- Opções de disciplinas -->
        <?php foreach ($todasDisciplinas as $disciplina): ?>
            <option value="<?php echo $disciplina['id_disciplina']; ?>"><?php echo htmlspecialchars($disciplina['nome_disciplina']); ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <button type="button" onclick="saveSelection()">Salvar</button>
    <button type="button" onclick="closeModal()">Cancelar</button>
</div>

<script>
    let selectedHorario = '';
    let selectedDia = '';

    function openModal(horario, dia) {
        selectedHorario = horario;
        selectedDia = dia;
        document.getElementById('modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function saveSelection() {
    const disciplinaId = document.getElementById('disciplinaSelect').value;
    const disciplinaNome = document.getElementById('disciplinaSelect').options[document.getElementById('disciplinaSelect').selectedIndex].text;

    // Atualiza a célula da tabela com a disciplina selecionada
    const cellId = `cell_${selectedHorario}_${selectedDia}`;
    const cell = document.getElementById(cellId);
    cell.innerHTML = disciplinaNome;
    
    // Remova o botão da célula
    const buttonId = `button_${selectedHorario}_${selectedDia}`;
    const button = document.getElementById(buttonId);
    button.style.display = 'none'; // Opcional: oculta o botão, se desejar

    // Salvar no banco de dados via AJAX ou formulário escondido
    const formData = new FormData();
    formData.append('horario', selectedHorario);
    formData.append('dia', selectedDia);
    formData.append('disciplina_id', disciplinaId);

    fetch('save_grade.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(data => console.log(data));

    closeModal();
}
</script>

</body>
</html>
