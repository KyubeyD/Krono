<?php 
require_once '../ASSETS/conexao.php';

if (isset($_POST['cnpj'])) {
    $nome_escola = $_POST['nome_escola'];
    $nome_responsavel = $_POST['nome_responsavel'];
    $senha = $_POST['senha_escola'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $cidade = $_POST['cidade']; // Novo campo para a cidade
    $bairro = $_POST['bairro']; // Novo campo para o bairro
    $logradouro = $_POST['logradouro']; // Novo campo para o logradouro
    $municipio = $_POST['estado']; // Novo campo para o município
    $uf = $_POST['uf']; // Novo campo para a UF
    $foto_escola = "semfoto.png";
    $dt_cadastro_escola = date('Y-m-d');

    try {
        // Inicia a transação
        $conexao->beginTransaction();

        // Verifica se o CEP já existe na tabela localidade
        $sqlCep = "SELECT cep FROM localidade WHERE cep = :cep";
        $stmtCep = $conexao->prepare($sqlCep);
        $stmtCep->bindParam(':cep', $cep);
        $stmtCep->execute();

        // Se o CEP não existir, insere na tabela localidade
        if ($stmtCep->rowCount() == 0) {
            $sqlInsertCep = "INSERT INTO localidade (cep, cidade, bairro, logradouro, municipio, UF) 
                             VALUES (:cep, :cidade, :bairro, :logradouro, :municipio, :uf)";
            $stmtInsertCep = $conexao->prepare($sqlInsertCep);
            $stmtInsertCep->bindParam(':cep', $cep);
            $stmtInsertCep->bindParam(':cidade', $cidade);
            $stmtInsertCep->bindParam(':bairro', $bairro);
            $stmtInsertCep->bindParam(':logradouro', $logradouro);
            $stmtInsertCep->bindParam(':municipio', $municipio);
            $stmtInsertCep->bindParam(':uf', $uf);
            $stmtInsertCep->execute();
        }


        // Inserir usuário
        $sqlUsu = "INSERT INTO usuario (nome_usuario, senha_usuario, dt_cadastro_usuario, status_usuario, nivel_usuario, foto_usuario) 
                   VALUES (:nome_usuario, SHA1(:senha), :dt_cadastro_usuario, '1', '2', :foto_usuario)";
        $stmtUsu = $conexao->prepare($sqlUsu);
        $stmtUsu->bindParam(':nome_usuario', $nome_escola);
        $stmtUsu->bindParam(':senha', $senha);
        $stmtUsu->bindParam(':dt_cadastro_usuario', $dt_cadastro_escola);
        $stmtUsu->bindParam(':foto_usuario', $foto_escola);
        $stmtUsu->execute();
        
        // Obter o ID do último usuário inserido
        $idUsu = $conexao->lastInsertId();

        // Inserir escola
        $sqlEscola = "INSERT INTO escola (nome_escola, nome_responsavel, cnpj, email, telefone, cep, numero, complemento, id_usuario) 
                      VALUES (:nome_escola, :nome_responsavel, :cnpj, :email, :telefone, :cep, :numero, :complemento, :id_usuario)";
        $stmtEscola = $conexao->prepare($sqlEscola);
        $stmtEscola->bindParam(':nome_escola', $nome_escola);
        $stmtEscola->bindParam(':nome_responsavel', $nome_responsavel);
        $stmtEscola->bindParam(':cnpj', $cnpj);
        $stmtEscola->bindParam(':email', $email);
        $stmtEscola->bindParam(':telefone', $telefone);
        $stmtEscola->bindParam(':cep', $cep);
        $stmtEscola->bindParam(':numero', $numero);
        $stmtEscola->bindParam(':complemento', $complemento);
        $stmtEscola->bindParam(':id_usuario', $idUsu);
        $stmtEscola->execute();

        // Se tudo ocorrer bem, confirma a transação
        $conexao->commit();
        header('location: login.php');
        exit();
    } catch (Exception $e) {
        // Se algo der errado, reverte a transação
        $conexao->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
?>
