<?php
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_GET['id_regra'])) {
    $id_regra = $_GET['id_regra'];
    $nome_regra = $_POST['nome_regra'];
    $descricao = $_POST['descricao'];
    $importante = $_POST['importante'];

    $sql = "UPDATE regra_disp SET nome_regra = :nome_regra, descricao = :descricao, importante = :importante WHERE id_regra = :id_regra";
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_regra', $id_regra, PDO::PARAM_INT);
        $query->bindValue(':nome_regra', $nome_regra, PDO::PARAM_STR);
        $query->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        $query->bindValue(':importante', $importante, PDO::PARAM_STR);

        if ($query->execute()) {
            header('location: ../../index.php?page=minhasOrientacoes');
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
