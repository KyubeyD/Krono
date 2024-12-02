<?php 
// Verificação de preenchimento de campos
if (!empty($_POST) && (empty($_POST['nome_usuario']) || empty($_POST['senha_usuario']))) {
    header("Location: index.php"); 
    exit;
}

require_once '../ASSETS/conexao.php'; // Arquivo que contém a conexão com PDO

// Utilizando prepared statements para evitar SQL injection
$nome_usuario = $_POST['nome_usuario'];
$senha_usuario = $_POST['senha_usuario'];

// Preparando a consulta
$sql = "SELECT id_usuario, nome_usuario, nivel_usuario, foto_usuario 
        FROM usuario 
        WHERE nome_usuario = :nome_usuario 
        AND senha_usuario = :senha_usuario 
        AND status_usuario = 1 
        LIMIT 1";

$stmt = $conexao->prepare($sql);
$stmt->bindParam(':nome_usuario', $nome_usuario);
$stmt->bindParam(':senha_usuario', sha1($senha_usuario)); // Usando SHA1 na senha
$stmt->execute();

// Verificando se o usuário foi encontrado
if ($stmt->rowCount() != 1) {
    header('Content-Type: text/html; charset=utf-8');
    echo "Login inválido!"; 
    exit;
} else {
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!isset($_SESSION)) {
        session_start();
    }

    // Armazenando dados na sessão
    $_SESSION['id_usuario'] = $resultado['id_usuario'];
    $_SESSION['nome_usuario'] = $resultado['nome_usuario'];
    $_SESSION['nivel_usuario'] = $resultado['nivel_usuario'];
    $_SESSION['foto_usuario'] = $resultado['foto_usuario']; // Armazenando a foto do usuário

    // Redirecionamento com base no nível do usuário
    switch($_SESSION['nivel_usuario']) {
        case 1: 
        case 2: 
        case 3: 
            header("Location: ../index.php?page=dashboard"); 
            exit;
    }
}
?>
