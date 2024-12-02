<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_curso'])) {
        $id_curso = $_POST['id_curso'];
        $nome_curso = $_POST['nome_curso'];
        $sigla_curso = $_POST['sigla_curso'];

        $sql = "UPDATE curso SET nome_curso = '$nome_curso', sigla_curso = '$sigla_curso' WHERE id_curso = $id_curso";
        $query = mysqli_query($conexao, $sql);

        if ($query) {
            header('location: lista_curso.php?msg=2');
        }
    }
?>