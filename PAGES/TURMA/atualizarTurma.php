<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_turma'])) {
        $id_turma = $_POST['id_turma'];
        $nome_turma = $_POST['nome_turma'];
        $serie_turma = $_POST['serie_turma'];
        $id_curso = $_POST['id_curso'];

        $sql = "UPDATE turma SET nome_turma = '$nome_turma', serie_turma = '$serie_turma', id_curso = $id_curso WHERE id_turma = $id_turma";
        $query = mysqli_query($conexao, $sql);

        if ($query) {
            header('location: lista_turma.php?msg=2');
        }
    }
?>