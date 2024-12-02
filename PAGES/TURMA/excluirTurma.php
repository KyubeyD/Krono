<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_turma'])) {
        $id_turma = $_GET['id_turma'];

        $sql = "DELETE FROM turma WHERE id_turma = $id_turma";
        $query = mysqli_query($conexao, $sql);

        if ($query) {
            header('location: lista_turma.php?msg=3');
        }
    }
?>