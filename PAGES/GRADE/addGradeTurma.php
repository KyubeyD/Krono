<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php'; 

    if (isset($_POST['id_turma'])) {
        $id_turma = $_POST['id_turma'];

        if (isset($_POST['grade'])) {
            $tempos_professores_disciplinas = $_POST['grade'];

            foreach ($tempos_professores_disciplinas as $tempo_professor_disciplina) {
                $partes = explode('_', $tempo_professor_disciplina);
                
                var_dump($tempo_professor_disciplina); 
                if (count($partes) === 5) { 
                    list($disciplina, $professor, $tempo, $dia_semana, $turno) = $partes;
                    
                    // Verifica se as variáveis não estão vazias
                    if (!empty($dia_semana) && !empty($tempo) && !empty($turno) && !empty($professor) && !empty($disciplina)) {
                        $sql = "INSERT INTO grade_hora_turma (dia_semana, id_disciplina, id_turma, id_professor, tempo, turno) VALUES (:dia_semana, :id_disciplina, :id_turma, :id_professor, :tempo, :turno)";

                        try {
                            $query = $conexao->prepare($sql);
                            $query->bindValue(':dia_semana', $dia_semana, PDO::PARAM_INT);
                            $query->bindValue(':id_disciplina', $disciplina, PDO::PARAM_INT);
                            $query->bindValue(':id_turma', $id_turma, PDO::PARAM_INT);
                            $query->bindValue(':id_professor', $professor, PDO::PARAM_INT);
                            $query->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                            $query->bindValue(':turno', $turno, PDO::PARAM_STR);
                            $query->execute();
                        } catch (PDOException $e) {
                            echo "Erro ao inserir horario da turma: " . $e->getMessage();
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

        header('Location: ../../index.php?page=grades_horarias');
    }
?>