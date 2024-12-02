<?php 
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_POST['id_professor'])) {
    $id_professor = $_POST['id_professor'];
    $id_escola = $_POST['id_escola'];

    if (isset($_POST['tempo_disponivel'])) {
        $disponibilidade = $_POST['tempo_disponivel'];

        foreach ($disponibilidade as $horario) {
            $partes = explode('_', $horario);
            
            if (count($partes) === 5) { 
                list($dia_semana, $tempo, $turno, $duracao, $tipo_disponibilidade) = $partes;
                
                // Verifica se as variáveis não estão vazias
                if (!empty($dia_semana) && !empty($tempo) && !empty($turno) && !empty($tipo_disponibilidade)) {
                    $sql = "INSERT INTO grade_disp_prof (id_professor,id_escola,dia_semana,tempo,turno,duracao,tipo_disp, ativo, dt_cadastro) VALUES (:id_professor,:id_escola,:dia_semana,:tempo,:turno,:duracao,:tipo_disp, 1, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'))";
                    
                    try {
                        $query = $conexao->prepare($sql);
                        $query->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
                        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                        $query->bindValue(':dia_semana', $dia_semana, PDO::PARAM_INT);
                        $query->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                        $query->bindValue(':turno', $turno, PDO::PARAM_STR);
                        $query->bindValue(':duracao', $duracao, PDO::PARAM_STR);
                        $query->bindValue(':tipo_disp', $tipo_disponibilidade, PDO::PARAM_STR);
                        $query->execute();

                        echo "Dados inseridos com sucesso: " . htmlspecialchars($horario);
                    } catch (PDOException $e) {
                        echo "Erro ao inserir horário: " . $e->getMessage();
                    }
                } else {
                    echo "Alguma variável está vazia para o horário: " . htmlspecialchars($horario);
                }
            } else {
                echo "Formato inválido para o horário: " . htmlspecialchars($horario);
                continue; 
            }
        }
    }

    header('Location: ../../index.php?page=disponibilidade_professor');
}
?>
