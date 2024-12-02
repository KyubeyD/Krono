<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';


    if (isset($_POST['id_professor'])) {
        $id_professor = $_POST['id_professor'];
        $id_usuario = $_POST['id_usuario'];
        $nome_professor = $_POST['nome_professor'];
        $email_professor = $_POST['email_professor'];
        $senha = $_POST['senha'];
        $telefone_professor = $_POST['telefone_professor'];
        $cpf_professor = $_POST['cpf_professor'];

        $sqlProf = "UPDATE professor SET nome_professor = '$nome_professor', email_professor = '$email_professor', telefone_professor = '$telefone_professor', cpf_professor = '$cpf_professor' WHERE id_professor = $id_professor";
        $queryProf = mysqli_query($conexao, $sqlProf);

        $senhaAtual = "SELECT senha_usuario FROM usuario WHERE id_usuario = $id_usuario";

        if ($senha == $senhaAtual) {
            $sqlUsu = "UPDATE usuario SET senha_usuario = '$senha' WHERE id_usuario = $id_usuario";
        } else {
            $sqlUsu = "UPDATE usuario SET senha_usuario = SHA1('$senha') WHERE id_usuario = $id_usuario";
        }

        $queryUsu = mysqli_query($conexao, $sqlUsu);

        if ($queryProf && $queryUsu) {
            header('location: lista_prof.php?msg=2');
        }
    }
?>