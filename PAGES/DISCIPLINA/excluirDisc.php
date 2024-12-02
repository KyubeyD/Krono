<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_disciplina'])) {
        $id_disciplina = $_GET['id_disciplina'];

        $sql = "DELETE FROM disciplina WHERE id_disciplina = :id_disciplina";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_disciplina', $id_disciplina, PDO::PARAM_INT);
            if ($query->execute()) {
                header('location: ../../index.php?page=disciplinas');
            }
        } catch (PDOException $e) {
            echo "Erro ao excluir curso: " . $e->getMessage();
        }
    }
?>