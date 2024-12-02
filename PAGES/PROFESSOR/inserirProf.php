<?php
require_once __DIR__ . '/../../ASSETS/conexao.php'; // Verifique se a conexão é via PDO

if (isset($_POST['senha'])) {
    try {
        // Recebendo os dados do formulário
        $nome_professor = $_POST['nome_professor'];
        $senha = $_POST['senha'];
        $email_professor = $_POST['email_professor'];
        $telefone_professor = $_POST['telefone_professor'];
        $cpf_professor = $_POST['cpf_professor'];
        $foto_professor = "semfoto.png";
        $dt_cadastro_professor = date('Y-m-d');
        
        // Iniciando a transação
        $pdo->beginTransaction();
        
        // Inserção na tabela usuario
        $sqlUsu = "INSERT INTO usuario (nome_usuario, senha_usuario, dt_cadastro_usuario, status_usuario, nivel_usuario, foto_usuario) 
                   VALUES (:nome_professor, SHA1(:senha), :dt_cadastro_professor, '1', '1', :foto_professor)";
        
        $stmtUsu = $pdo->prepare($sqlUsu);
        $stmtUsu->bindParam(':nome_professor', $nome_professor);
        $stmtUsu->bindParam(':senha', $senha);
        $stmtUsu->bindParam(':dt_cadastro_professor', $dt_cadastro_professor);
        $stmtUsu->bindParam(':foto_professor', $foto_professor);
        $stmtUsu->execute();
        
        // Obtendo o ID do último usuário inserido
        $idUsu = $pdo->lastInsertId();
        
        // Inserção na tabela professor
        $sqlProf = "INSERT INTO professor (nome_professor, email_professor, telefone_professor, cpf_professor, id_usuario) 
                    VALUES (:nome_professor, :email_professor, :telefone_professor, :cpf_professor, :id_usuario)";
        
        $stmtProf = $pdo->prepare($sqlProf);
        $stmtProf->bindParam(':nome_professor', $nome_professor);
        $stmtProf->bindParam(':email_professor', $email_professor);
        $stmtProf->bindParam(':telefone_professor', $telefone_professor);
        $stmtProf->bindParam(':cpf_professor', $cpf_professor);
        $stmtProf->bindParam(':id_usuario', $idUsu);
        $stmtProf->execute();
        
        // Confirmando a transação
        $pdo->commit();
        
        // Redirecionando após o sucesso
        header('Location: lista_prof.php?msg=1');
    } catch (Exception $e) {
        // Revertendo a transação em caso de erro
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
?>
