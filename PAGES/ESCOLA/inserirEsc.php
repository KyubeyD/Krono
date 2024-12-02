<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['cnpj'])) {
        $nome_escola = $_POST['nome_escola'];
        $nome_responsavel = $_POST['nome_responsavel'];
        $senha = $_POST['senha'];
        $cnpj = $_POST['cnpj'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $logradouro = $_POST['logradouro'];
        $municipio = $_POST['municipio'];
        $UF = $_POST['UF'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $nome_regra = $_POST['nome_regra'];
        $descricao = $_POST['descricao'];
        $foto_escola = "semfoto.png";
        $dt_cadastro_escola = date('Y-m-d');

        $sqlUsu = "INSERT INTO usuario (nome_usuario, senha_usuario, dt_cadastro_usuario, status_usuario, nivel_usuario, foto_usuario) VALUES ('$nome_escola',SHA1('$senha'),'$dt_cadastro_escola','1','2','$foto_escola')";
        $queryUsu = mysqli_query($conexao, $sqlUsu);
        $idUsu = mysqli_insert_id($conexao);

        $sqlCep = "INSERT INTO localidade VALUES ('$cep','$cidade','$bairro','$logradouro','$municipio','$UF')";
        $queryCep = mysqli_query($conexao, $sqlCep);

        $sqlRegra = "INSERT INTO regra_disp (nome_regra,descricao) VALUES ('$nome_regra','$descricao')";
        $queryRegra = mysqli_query($conexao, $sqlRegra);
        $idRegra = mysqli_insert_id($conexao);

        if ($queryCep && $queryRegra && $queryUsu) {
            $sqlEscola = "INSERT INTO escola (nome_escola,nome_responsavel,cnpj,email,telefone,cep,numero,complemento,id_regra,id_usuario) VALUES ('$nome_escola','$nome_responsavel','$cnpj','$email','$telefone','$cep','$numero','$complemento',$idRegra,$idUsu)";
            $queryEscola = mysqli_query($conexao, $sqlEscola);

            if ($queryEscola) {
                header('location: lista_esc.php?msg=1');
            }
        }
    }
?>