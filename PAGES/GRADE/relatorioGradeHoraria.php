<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id_escola'])) {
    $id_escola = $_GET['id_escola'];

    // Consultar as informações da escola
    $sql = "SELECT escola.nome_escola, escola.email, escola.nome_responsavel, usuario.foto_usuario
            FROM escola
            INNER JOIN usuario ON escola.id_usuario = usuario.id_usuario
            WHERE escola.id_escola = :id_escola";
    
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
        $query->execute();
        $escola = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar escola: " . $e->getMessage();
    }

    // Consultar as grades de horário
    $sql = "SELECT * FROM grade_hora_turma WHERE id_escola = :id_escola ORDER BY tempo, dia_semana";
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
        $query->execute();
        $grades = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar grades: " . $e->getMessage();
    }

    $html = '
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório de Grade Horária</title>
        <link href="https://fonts.cdnfonts.com/css/rubik" rel="stylesheet">
        <link rel="shortcut icon" href="IMG/icon-krono.png" type="image/x-icon">
        <style>
            body {
                font-family: "Rubik", sans-serif;
            }
            h1, h2 {
                text-align: center;
                margin-top: 50px;
            }
            .headerInfo {
                margin-bottom: 30px;
            }
            .info {
                float: left;
                width: 60%;
            }
            .icon {
                width: 100px;
                height: 100px;
                text-align: right;
                float: right;
                margin-top: 12px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #3955A0;
                color: white;
            }
            tr:nth-child(odd) {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h1>Relatório de Grade Horária</h1>
        <div class="headerInfo">
            <div class="info">
                <h3>Escola: ' . $escola['nome_escola'] . '</h3>
                <h3>Responsável: ' . $escola['nome_responsavel'] . '</h3>
                <h3>E-mail: ' . $escola['email'] . '</h3>
            </div>
            <div class="icon">
                <img src="IMG/UPLOADS/' . $escola['foto_usuario'] . '" alt="Foto da Escola">
            </div>
        </div>

        <h2>Grade Horária</h2>
        <table>
            <tr>
                <th>Tempo</th>
                <th>Dia da Semana</th>
                <th>Turno</th>
                <th>Horário</th>
                <th>Disciplina</th>
                <th>Professor</th>
            </tr>';

    foreach ($grades as $grade) {
        // Consultar a disciplina e o professor
        $sqlDiscProf = "
            SELECT d.nome_disciplina, p.nome_professor 
            FROM grade_hora_turma ght
            INNER JOIN disciplina d ON ght.id_disciplina = d.id_disciplina
            INNER JOIN professor p ON ght.id_professor = p.id_professor
            WHERE ght.id_escola = :id_escola
            AND ght.tempo = :tempo 
            AND ght.dia_semana = :dia_semana";

        try {
            $queryDiscProf = $conexao->prepare($sqlDiscProf);
            $queryDiscProf->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $queryDiscProf->bindValue(':tempo', $grade['tempo'], PDO::PARAM_INT);
            $queryDiscProf->bindValue(':dia_semana', $grade['dia_semana'], PDO::PARAM_INT);
            $queryDiscProf->execute();
            $discProf = $queryDiscProf->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar disciplina e professor: " . $e->getMessage();
        }

        $html .= '
        <tr>
            <td>' . $grade['tempo'] . '</td>
            <td>' . $grade['dia_semana'] . '</td>
            <td>' . $grade['turno'] . '</td>
            <td>' . $grade['horario_inicio'] . ' - ' . $grade['horario_final'] . '</td>
            <td>' . $discProf['nome_disciplina'] . '</td>
            <td>' . $discProf['nome_professor'] . '</td>
        </tr>';
    }

    $html .= '
        </table>
    </body>
    </html>';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output();
} else {
    echo "Escola não encontrada.";
}

?>
