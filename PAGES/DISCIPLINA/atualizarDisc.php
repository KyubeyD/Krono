<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_disciplina'])) {
        $id_disciplina = $_POST['id_disciplina'];
        $nome_disciplina = $_POST['nome_disciplina'];
        $sigla_disciplina = $_POST['sigla_disciplina'];
        $id_curso = $_POST['id_curso'];

        $sql = "UPDATE disciplina SET nome_disciplina = '$nome_disciplina', sigla_disciplina = '$sigla_disciplina', id_curso = $id_curso WHERE id_disciplina = $id_disciplina";
        $query = mysqli_query($conexao, $sql);

        if ($query) {
            header('location: lista_disc.php?msg=2');
        }
    }
?>