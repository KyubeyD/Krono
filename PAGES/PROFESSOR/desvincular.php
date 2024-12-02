<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_professor'])) {
        $id_professor = $_GET['id_professor'];

        $sql = "DELETE FROM vinculo_prof_esc WHERE id_professor = :id_professor";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
            if ($query->execute()) {
                header('location: ../../index.php?page=professores');
            }
        } catch (PDOException $e) {
            echo "Erro ao excluir curso: " . $e->getMessage();
        }
    }
?>