<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../ASSETS/conexao.php';

$idProfessor = $_GET['idProfessor'] ?? null;

if ($idProfessor) {
    // Modifiquei a consulta para incluir o nome do professor (dois primeiros nomes)
    $sql = "SELECT 
    dia_semana, 
    tempo, 
    turno,
    professor.nome_professor as nomeProfessor   
FROM grade_disp_prof 
JOIN professor ON grade_disp_prof.id_professor = professor.id_professor
WHERE grade_disp_prof.id_professor = :id_professor";
    
    $query = $conexao->prepare($sql);
    $query->bindParam(':id_professor', $idProfessor, PDO::PARAM_INT);
    $query->execute();

    $disponibilidade = $query->fetchAll(PDO::FETCH_ASSOC);

    // Inclui o nome do professor nos dados
    if (!empty($disponibilidade)) {
        echo json_encode($disponibilidade);
    } else {
        echo json_encode(['error' => 'Professor não encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID do professor não fornecido']);
}
?>
