<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_regra'])) {
        $id_regra = $_GET['id_regra'];

        $sql = "DELETE FROM regra_disp WHERE id_regra = :id_regra";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_regra', $id_regra, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            echo "Erro ao deletar regra: " . $e->getMessage();
        }

        if ($query) {
            header('location: ../../index.php?page=minhasOrientacoes');
        }
    }
?>