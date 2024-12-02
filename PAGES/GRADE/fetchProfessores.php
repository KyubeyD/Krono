<?php
require_once __DIR__ . '/../../ASSETS/conexao.php'; 

if (isset($_GET['dia'], $_GET['tempo'], $_GET['disciplinaId'], $_GET['idEscola'])) {
    $dia = $_GET['dia'];
    $tempo = $_GET['tempo'];
    $disciplinaId = $_GET['disciplinaId'];
    $idEscola = $_GET['idEscola'];

    $sql = "
        SELECT DISTINCT p.id_professor, p.nome_professor
        FROM professor p
        JOIN vinculo_prof_disc vpd 
            ON p.id_professor = vpd.id_professor
        JOIN grade_disp_prof gdp
            ON gdp.id_professor = p.id_professor
        JOIN vinculo_prof_esc vpe
            ON p.id_professor = vpe.id_professor
        WHERE vpd.id_disciplina = :disciplinaId
            AND gdp.dia_semana = :dia
            AND gdp.tempo = :tempo
            AND vpe.id_escola = :idEscola
    ";

    try {
        $query = $conexao->prepare($sql);
        $query->bindParam(':disciplinaId', $disciplinaId, PDO::PARAM_INT);
        $query->bindParam(':dia', $dia, PDO::PARAM_INT);
        $query->bindParam(':tempo', $tempo, PDO::PARAM_INT);
        $query->bindParam(':idEscola', $idEscola, PDO::PARAM_INT);

        $query->execute();
        $professores = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($professores);
    } catch (PDOException $e) {
        // Erro na execução da consulta
        error_log("Erro no banco de dados: " . $e->getMessage());
        echo json_encode(['error' => 'Erro ao buscar professores.']);
    }
}
?>