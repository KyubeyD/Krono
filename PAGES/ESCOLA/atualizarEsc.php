<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_escola'])) {
        $id_escola = $_POST['id_escola'];
        $id_usuario = $_POST['id_usuario'];
        $nome_escola = $_POST['nome_escola'];
        $nome_responsavel = $_POST['nome_responsavel'];
        $senha = $_POST['senha'];
        $cnpj = $_POST['cnpj'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $id_regra = $_POST['id_regra'];
        $nome_regra = $_POST['nome_regra'];
        $descricao = $_POST['descricao'];

        $sqlEsc = "UPDATE escola SET nome_escola = '$nome_escola', nome_responsavel = '$nome_responsavel', cnpj = '$cnpj', email = '$email', telefone = '$telefone', numero = '$numero', complemento = '$complemento' WHERE id_escola = $id_escola";
        $queryEsc = mysqli_query($conexao, $sqlEsc);

        $senhaAtual = "SELECT senha_usuario FROM usuario WHERE id_usuario = $id_usuario";

        if ($senha == $senhaAtual) {
            $sqlUsu = "UPDATE usuario SET senha_usuario = '$senha' WHERE id_usuario = $id_usuario";
        } else {
            $sqlUsu = "UPDATE usuario SET senha_usuario = SHA1('$senha') WHERE id_usuario = $id_usuario";
        }

        $queryUsu = mysqli_query($conexao, $sqlUsu);
        
        $sqlRegra = "UPDATE regra_disp SET nome_regra = '$nome_regra', descricao = '$descricao' WHERE id_regra = $id_regra";
        $queryRegra = mysqli_query($conexao, $sqlRegra);

        if ($queryEsc && $queryUsu && $queryUsu) {
            header('location: lista_esc.php?msg=2');
        }
    }
?>