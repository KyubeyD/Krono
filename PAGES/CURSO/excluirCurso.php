<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_curso'])) {
        $id_curso = $_GET['id_curso'];

        $sql = "DELETE FROM curso WHERE id_curso = :id_curso";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_curso', $id_curso, PDO::PARAM_INT);
            if ($query->execute()) {
                header('location: ../../index.php?page=cursos');
            }
        } catch (PDOException $e) {
            echo "Erro ao excluir curso: " . $e->getMessage();
        }
    }
?>