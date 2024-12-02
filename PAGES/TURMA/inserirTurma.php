<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_curso'])) {
        $nome_turma = $_POST['nome_turma'];
        $serie_turma = $_POST['serie_turma'];
        $id_curso = $_POST['id_curso'];

        try {
            $sql = "INSERT INTO turma (nome_turma, serie_turma, id_escola, id_curso) VALUES (:nome_turma, :serie_turma, 1, :id_curso)";
            $stmt = $conexao->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':nome_turma', $nome_turma);
            $stmt->bindParam(':serie_turma', $serie_turma);
            $stmt->bindParam(':id_curso', $id_curso);

            // Execute the statement
            if ($stmt->execute()) {
                header('location: ../../index.php?page=turmas&msg=1');
                exit();
            }
        } catch (PDOException $e) {
            // Handle errors
            echo "Erro: " . $e->getMessage();
        }
    }
?>
