<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_usuario'])) {
        $id_usuario = $_GET['id_usuario'];

        // Preparar a consulta SQL com placeholders
        $sql = "UPDATE usuario SET status_usuario = :status WHERE id_usuario = :id";

        // Preparar a execução da consulta
        $stmt = $conexao->prepare($sql);

        // Atribuir valores aos placeholders
        $status_usuario = 1;
        $stmt->bindParam(':status', $status_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

        // Executar a consulta
        if ($stmt->execute()) {
            // Redirecionar em caso de sucesso
            header('Location: ../../index.php?page=users&msg=4');
            exit;
        } else {
            // Em caso de erro
            echo "Erro ao atualizar o status do usuário.";
        }
    }
?>
