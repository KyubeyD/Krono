<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['nome_curso'])) {
        $id_escola = $_POST['id_escola'];
        $nome_curso = $_POST['nome_curso'];
        $sigla_curso = $_POST['sigla_curso'];

        $sql = "INSERT INTO curso (id_escola, nome_curso, sigla_curso) VALUES (:id_escola,:nome_curso,:sigla_curso)";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $query->bindValue(':nome_curso', $nome_curso, PDO::PARAM_STR);
            $query->bindValue(':sigla_curso', $sigla_curso, PDO::PARAM_STR);
            
            if ($query->execute()) {
                header('location: ../../index.php?page=cursos');
                exit();
            }
        } catch (PDOException $e) {
            echo "Erro ao inserir curso: " . $e->getMessage();
        }
    }
?>