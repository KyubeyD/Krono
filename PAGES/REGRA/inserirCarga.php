<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_escola'])) {
        $hora_semanais = $_POST['horas_semanais'];
        $id_escola = $_POST['id_escola'];

        try {
            $sql = "INSERT INTO carga_horaria_esc (id_escola, horas_semanais) 
                    VALUES (:id_escola, :horas_semanais)";
            $stmt = $conexao->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':id_escola', $id_escola);
            $stmt->bindParam(':horas_semanais', $hora_semanais);

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
