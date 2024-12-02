<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_carga_horaria_esc'])) {
        $id_carga_horaria_esc = $_GET['id_carga_horaria_esc'];

        $sql = "DELETE FROM carga_horaria_esc WHERE id_carga_horaria_esc = :id_carga_horaria_esc";

        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_carga_horaria_esc', $id_carga_horaria_esc, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            echo "Erro ao deletar carga horária: " . $e->getMessage();
        }

        if ($query) {
            header('location: ../../index.php?page=minhasOrientacoes');
        }
    }
?>