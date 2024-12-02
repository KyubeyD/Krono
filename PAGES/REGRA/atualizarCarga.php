<?php
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_GET['id_carga_horaria_esc'])) {
    $id_carga_horaria_esc = $_GET['id_carga_horaria_esc'];
    $horas_semanais = $_POST['horas_semanais'];

    $sql = "UPDATE carga_horaria_esc SET horas_semanais = :horas_semanais WHERE id_carga_horaria_esc = :id_carga_horaria_esc";
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_carga_horaria_esc', $id_carga_horaria_esc, PDO::PARAM_INT);
        $query->bindValue(':horas_semanais', $horas_semanais, PDO::PARAM_INT);

        if ($query->execute()) {
            header('location: ../../index.php?page=minhasOrientacoes');
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
