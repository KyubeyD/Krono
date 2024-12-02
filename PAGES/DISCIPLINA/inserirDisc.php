<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['nome_disciplina'])) {
        $id_escola = $_POST['id_escola'];
        $nome_disciplina = $_POST['nome_disciplina'];
        $sigla_disciplina = $_POST['sigla_disciplina'];
        $carga_horaria = $_POST['carga_horaria'];
        $cursos = $_POST['cursos'];

        $sql = "INSERT INTO disciplina (id_escola, nome_disciplina, sigla_disciplina, carga_horaria) VALUES (:id_escola,:nome_disciplina,:sigla_disciplina,:carga_horaria)";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $query->bindValue(':nome_disciplina', $nome_disciplina, PDO::PARAM_STR);
            $query->bindValue(':sigla_disciplina', $sigla_disciplina, PDO::PARAM_STR);
            $query->bindValue(':carga_horaria', $carga_horaria, PDO::PARAM_INT);
            
            if ($query->execute()) {
                $id_disciplina = $conexao->lastInsertId();
                $sql = "INSERT INTO vinculo_curso_disc (id_curso,id_disciplina) VALUES (:id_curso,:id_disciplina)";

                foreach ($cursos as $curso) {
                    $query = $conexao->prepare($sql);
                    $query->bindValue(':id_curso',$curso,PDO::PARAM_INT);
                    $query->bindValue(':id_disciplina',$id_disciplina,PDO::PARAM_INT);
                    $query->execute();
                }
                header('location: ../../index.php?page=disciplinas&msg=1');
                exit();
            }
        } catch (PDOException $e) {
            echo "Erro ao inserir curso: " . $e->getMessage();
        }
    }
?>