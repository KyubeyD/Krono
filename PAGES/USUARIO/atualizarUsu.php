<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';


    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
        $nome_usuario = $_POST['nome_usuario'];
        $senha_usuario = $_POST['senha_usuario'];
        $dt_cadastro_usuario = date('Y-m-d');
        $status_usuario = $_POST['status_usuario'];
        $nivel_usuario = $_POST['nivel_usuario'];
        $foto_usuario = 'semfoto.png';

        $senhaAtual = "SELECT senha_usuario FROM usuario WHERE id_usuario = $id_usuario";

        if ($senha_usuario == $senhaAtual) {
            $sql = "UPDATE usuario SET nome_usuario = '$nome_usuario', senha_usuario = '$senha_usuario', dt_cadastro_usuario = '$dt_cadastro_usuario', nivel_usuario = '$nivel_usuario', status_usuario = '$status_usuario', foto_usuario = '$foto_usuario' WHERE id_usuario = $id_usuario";
        } else {
            $sql = "UPDATE usuario SET nome_usuario = '$nome_usuario', senha_usuario = SHA1('$senha_usuario'), dt_cadastro_usuario = '$dt_cadastro_usuario', nivel_usuario = '$nivel_usuario', status_usuario = '$status_usuario', foto_usuario = '$foto_usuario' WHERE id_usuario = $id_usuario";
        }

        $query = mysqli_query($conexao, $sql);

        if ($query) {
            header('location: lista_usu.php?msg=2');
        }
    }
?>