<?php 
    require_once '../ASSETS/conexao.php';

if (isset($_POST['senha_professor'])) {
    $nome_professor = $_POST['nome_professor'];
    $usuario_professor = $_POST['usuario_professor'];
    $senha = $_POST['senha_professor'];
    $email_professor = $_POST['email_professor'];
    $telefone_professor = $_POST['telefone_professor'];
    $cpf_professor = $_POST['cpf_professor'];
    $foto_professor = "semfoto.png";
    $dt_cadastro_professor = date('Y-m-d');

    try {
        // Inicia a transação
        $conexao->beginTransaction();

        // Inserir usuário
        $sqlUsu = "INSERT INTO usuario (nome_usuario, senha_usuario, dt_cadastro_usuario, status_usuario, nivel_usuario, foto_usuario) 
                   VALUES (:nome_usuario, SHA1(:senha), :dt_cadastro_usuario, '1', '1', :foto_usuario)";
        $stmtUsu = $conexao->prepare($sqlUsu);
        $stmtUsu->bindParam(':nome_usuario', $usuario_professor);
        $stmtUsu->bindParam(':senha', $senha);
        $stmtUsu->bindParam(':dt_cadastro_usuario', $dt_cadastro_professor);
        $stmtUsu->bindParam(':foto_usuario', $foto_professor);
        $stmtUsu->execute();
        
        // Obter o ID do último usuário inserido
        $idUsu = $conexao->lastInsertId();

        // Inserir professor
        $sqlProf = "INSERT INTO professor (nome_professor, email_professor, telefone_professor, cpf_professor, id_usuario) 
                    VALUES (:nome_professor, :email_professor, :telefone_professor, :cpf_professor, :id_usuario)";
        $stmtProf = $conexao->prepare($sqlProf);
        $stmtProf->bindParam(':nome_professor', $nome_professor);
        $stmtProf->bindParam(':email_professor', $email_professor);
        $stmtProf->bindParam(':telefone_professor', $telefone_professor);
        $stmtProf->bindParam(':cpf_professor', $cpf_professor);
        $stmtProf->bindParam(':id_usuario', $idUsu);
        $stmtProf->execute();

        // Se tudo ocorrer bem, confirma a transação
        $conexao->commit();
        header('location: login.php');
    } catch (Exception $e) {
        // Se algo der errado, reverte a transação
        $conexao->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
?>
