<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['nivel_usuario'])) {
        $nome_usuario = $_POST['nome_usuario'];
        $senha_usuario = $_POST['senha_usuario'];
        $dt_cadastro_usuario = date('Y-m-d');
        $status_usuario = 1; // Sempre ativo inicialmente
        $nivel_usuario = $_POST['nivel_usuario'];

        // Lógica de upload da foto
        if (isset($_FILES['foto_usuario']) && $_FILES['foto_usuario']['error'] == 0) {
            $extensao = strtolower(pathinfo($_FILES['foto_usuario']['name'], PATHINFO_EXTENSION));
            $nome_foto = uniqid() . '.' . $extensao;
            $caminho_foto = '../../IMG/UPLOADS/' . $nome_foto;

            // Verifica se é uma imagem válida
            $tipos_permitidos = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($extensao, $tipos_permitidos)) {
                // Move o arquivo para o diretório correto
                if (move_uploaded_file($_FILES['foto_usuario']['tmp_name'], $caminho_foto)) {
                    $foto_usuario = $nome_foto;
                } else {
                    echo "Erro ao fazer upload da imagem.";
                    exit;
                }
            } else {
                echo "Formato de imagem não permitido.";
                exit;
            }
        } else {
            // Caso não tenha upload de foto, usa uma padrão
            $foto_usuario = 'semfoto.png';
        }

        // Preparando a consulta para inserir os dados
        $sql = "INSERT INTO usuario (nome_usuario, senha_usuario, dt_cadastro_usuario, status_usuario, nivel_usuario, foto_usuario) 
                VALUES (:nome, SHA1(:senha), :dt_cadastro, :status, :nivel, :foto)";
        
        // Preparar a execução da consulta
        $stmt = $conexao->prepare($sql);
        
        // Atribuindo valores aos placeholders
        $stmt->bindParam(':nome', $nome_usuario);
        $stmt->bindParam(':senha', $senha_usuario);
        $stmt->bindParam(':dt_cadastro', $dt_cadastro_usuario);
        $stmt->bindParam(':status', $status_usuario);
        $stmt->bindParam(':nivel', $nivel_usuario);
        $stmt->bindParam(':foto', $foto_usuario);
        
        // Executando a consulta
        if ($stmt->execute()) {
            header('location: index.php?page=users&msg=1');
            exit;
        } else {
            echo "Erro ao cadastrar o usuário.";
        }
    }
?>
