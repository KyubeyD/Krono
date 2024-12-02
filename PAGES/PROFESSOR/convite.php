<?php 
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_POST['id_escola'])) {
    $id_escola = $_POST['id_escola'];
    $email_professor = $_POST['email_docente'];

    // Buscando dados da escola
    $sql = "SELECT nome_escola, id_usuario FROM escola WHERE id_escola = :id_escola";
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
        $query->execute();
        $escola = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar escola: " . $e->getMessage();
    }

    // Buscando dados do professor
    $sql = "SELECT id_usuario FROM professor WHERE email_professor = :email_professor";
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':email_professor', $email_professor, PDO::PARAM_STR);
        $query->execute();
        $professor = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar professor: " . $e->getMessage();
    }

    // Preparando a mensagem e inserindo a notificação
    $mensagem = "Convite enviado por " . $escola['nome_escola'];

    var_dump($professor);

    if ($escola && $professor) {
        $sql = "INSERT INTO notificacao (id_remetente, id_destinatario, mensagem) VALUES (:id_remetente, :id_destinatario, :mensagem)";
        try {
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_remetente', $escola['id_usuario'], PDO::PARAM_INT);
            $query->bindValue(':id_destinatario', $professor['id_usuario'], PDO::PARAM_INT);
            $query->bindValue(':mensagem', $mensagem, PDO::PARAM_STR);
            $query->execute();
        } catch (PDOException $e) {
            echo "Erro ao enviar convite: " . $e->getMessage();
        }
        
        header('location: ../../index.php?page=professores');
        exit(); // Sempre bom incluir o exit() após um header para redirecionamento.
    }
}
?>
