<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "krono";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

// Obter todas as disciplinas
$stmt = $conn->prepare("SELECT * FROM disciplina");
$stmt->execute();
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Horários pré-definidos
$horarios = [
    '7h - 08:40h',
    '08:40h - 10:20h',
    '10:30h - 12h',
    '13h - 14:40h',
    '14:40h - 16:20h',
    '16:30h - 18h'
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizador de Horários</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Organizador de Horários</h1>

<!-- Tabela de Matérias -->
<h2>Matérias</h2>
<table>
    <tr>
        <th>Matéria</th>
        <th>Sigla</th>
    </tr>
    <?php foreach ($materias as $materia): ?>
        <tr>
            <td><?php echo htmlspecialchars($materia['nome_disciplina']); ?></td>
            <td><?php echo htmlspecialchars($materia['sigla_disciplina']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Tabela de Grade Horária -->
<h2>Grade Horária</h2>
<form method="POST" action="">
    <table>
        <tr>
            <th>Horário</th>
            <th>Matéria</th>
        </tr>
        <?php foreach ($horarios as $horario): ?>
            <tr>
                <td><?php echo htmlspecialchars($horario); ?></td>
                <td>
                    <?php $horarioKey = str_replace([' ', ':', '-'], '_', $horario); ?>
                    <select name="materia_<?php echo $horarioKey; ?>">
                        <option value="">Selecione uma matéria</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?php echo htmlspecialchars($materia['id_disciplina']); ?>">
                                <?php echo htmlspecialchars($materia['nome_disciplina']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button type="submit">Salvar Grade</button>
</form>

<?php
// Processar a submissão do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<h3>Grade Horária Alocada:</h3>";
    echo "<ul>";
    foreach ($horarios as $horario) {
        $horarioKey = str_replace([' ', ':', '-'], '_', $horario);
        $materia_id = $_POST['materia_' . $horarioKey] ?? null;
        
        if (!empty($materia_id)) {
            // Buscar o nome da matéria no banco de dados
            $stmt = $conn->prepare("SELECT nome_disciplina FROM disciplina WHERE id_disciplina = ?");
            $stmt->execute([$materia_id]);
            $materia = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($materia) {
                echo "<li>Horário: $horario - Matéria: " . htmlspecialchars($materia['nome_disciplina']) . "</li>";
            }
        }
    }
    echo "</ul>";
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const materias = <?php echo json_encode($materias); ?>;
        const selects = document.querySelectorAll('select');

        selects.forEach(select => {
            select.addEventListener('change', function() {
                atualizarCargaHoraria();
            });
        });

        function atualizarCargaHoraria() {
            // Atualização da carga horária se necessário
        }
    });
</script>

</body>
</html>
