<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_usuario'])) {
        $id_usuario = $_GET['id_usuario'];
        $id_remetente = $_GET['id_remetente'];
        $id_notificacao = $_GET['id_notificacao'];

        $sql = "SELECT id_professor FROM professor WHERE id_usuario = :id_usuario";
        try {            
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $query->execute();
            $professor = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar professor: " . $e;
        }

        $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";

        try {            
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_usuario', $id_remetente, PDO::PARAM_INT);
            $query->execute();
            $escola = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar escola: " . $e;
        }

        if ($professor && $escola) {
            $sql = "INSERT INTO vinculo_prof_esc (id_escola,id_professor) VALUES (:id_escola,:id_professor)";

            try {            
                $query = $conexao->prepare($sql);
                $query->bindValue(':id_escola', $escola['id_escola'], PDO::PARAM_INT);
                $query->bindValue(':id_professor', $professor['id_professor'], PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                echo "Erro ao registrar vinculo: " . $e;
            }

            $sql = "DELETE FROM notificacao WHERE id_notificacao = :id_notificacao";

            try {            
                $query = $conexao->prepare($sql);
                $query->bindValue(':id_notificacao', $id_notificacao, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                echo "Erro ao deletar notificacao: " . $e;
            }

            header('location: ../../index.php?page=notificacao');
        }
    }
?>