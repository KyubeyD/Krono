<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<?php 
    require_once 'COMPONENTS/sidebar.php';
?>
<div class="content">
    <?php 
        require_once 'COMPONENTS/topbar.php';
        $id_usuario = $_SESSION['id_usuario'];
    ?>

    <div class="container">
        <h1 class="title-page">Dashboard</h1>
        <div class="head-crud">
            <h2>Minhas notificações</h2>
        </div>
        <?php 
            $sql = "SELECT * FROM notificacao WHERE id_destinatario = :id_usuario";
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $query->execute();
            $notificacaoes = $query->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (count($notificacaoes) > 0): ?>
        <table class="crud">
            <tr class="campos">
                <td>Foto</td>
                <td>Nome</td>
                <td>Assunto</td>
                <td>Data de envio</td>
                <td>Controle</td>
            </tr>
            <?php foreach ($notificacaoes as $notificacao): ?>
                <tr>
                    <?php 
                        $sql = "SELECT foto_usuario, nome_usuario FROM usuario WHERE id_usuario = :id_remetente";
                        $query = $conexao->prepare($sql);
                        $query->bindValue(':id_remetente', $notificacao['id_remetente'], PDO::PARAM_INT);
                        $query->execute();
                        $usuario = $query->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <td><img class="foto_usu" src="IMG/UPLOADS/<?php echo $usuario['foto_usuario']; ?>" alt="<?php echo "Foto de " . $usuario['nome_usuario']; ?>"></td>
                    <td><?php echo $usuario['nome_usuario']; ?></td>
                    <td><?php echo $notificacao['mensagem']; ?></td>
                    <td><?php echo $notificacao['data_envio']; ?></td>
                    <td><a class="btn-make" href="PAGES/PROFESSOR/vincularEscProf.php?id_remetente=<?php echo $notificacao['id_remetente'];?>&id_usuario=<?php echo $id_usuario; ?>&id_notificacao=<?php echo $notificacao['id_notificacao']; ?>">Aceitar</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p class="aviso">Nenhuma mensagem foi enviada a você.</p>
        <?php endif; ?>
    </div>
</div>