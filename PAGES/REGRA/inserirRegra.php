<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_escola'])) {
        $nome_regra = $_POST['nome_turma'];  // Usando 'nome_turma' como no formulário
        $descricao = $_POST['descricao'];
        $importante = $_POST['importante'];
        $id_escola = $_POST['id_escola'];

        try {
            $sql = "INSERT INTO regra_disp (nome_regra, descricao, importante, id_escola) 
                    VALUES (:nome_regra, :descricao, :importante, :id_escola)";
            $stmt = $conexao->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':nome_regra', $nome_regra);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':importante', $importante);
            $stmt->bindParam(':id_escola', $id_escola);

            // Execute the statement
            if ($stmt->execute()) {
                header('location: ../../index.php?page=minhasOrientacoes');
                exit();
            }
        } catch (PDOException $e) {
            // Handle errors
            echo "Erro: " . $e->getMessage();
        }
    }
?>
