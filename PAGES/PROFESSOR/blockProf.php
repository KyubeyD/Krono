<?php
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_GET['id_professor'])) {
    $id_professor = $_GET['id_professor'];

    try {
        // Preparar e executar a consulta para obter o id_usuario
        $sqlProf = "SELECT id_usuario FROM professor WHERE id_professor = :id_professor";
        $queryProf = $conexao->prepare($sqlProf);
        $queryProf->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
        $queryProf->execute();
        $dadosProf = $queryProf->fetch(PDO::FETCH_ASSOC);

        // Verifica se encontrou o professor
        if ($dadosProf) {
            // Preparar e executar a atualização do status do usuário
            $sqlUsu = "UPDATE usuario SET status_usuario = '0' WHERE id_usuario = :id_usuario";
            $queryUsu = $conexao->prepare($sqlUsu);
            $queryUsu->bindValue(':id_usuario', $dadosProf['id_usuario'], PDO::PARAM_INT);
            $queryUsu->execute();

            // Se a atualização foi bem-sucedida, redireciona
            if ($queryUsu->rowCount() > 0) {
                header('location: /../../index.php?page=professores&msg=3');
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
