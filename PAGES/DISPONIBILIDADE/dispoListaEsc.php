<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/mensagens.css">
<link rel="stylesheet" href="CSS/cards.css">

<?php 
require_once 'PAGES/COMPONENTS/sidebar.php';

// Definir o número de escolas por página
?>

<div class="content">
    <?php 
        require_once 'PAGES/COMPONENTS/topbar.php';
        $sql = "SELECT id_professor FROM professor WHERE id_usuario = :id_usuario";
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
        $query->execute();
        $dadosUsu = $query->fetch(PDO::FETCH_ASSOC);
        $id_professor = $dadosUsu['id_professor'];
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=disponibilidade_professor">Disponibilidade</a></h1>
        <div class="head-crud">
            <h2>Escolas vinculadas</h2>
        </div>
        <div class="content-cards">
            <?php 
                $sql = "SELECT id_escola FROM vinculo_prof_esc WHERE id_professor = :id_professor";
                $query = $conexao->prepare($sql);
                $query->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
                $query->execute();
                $escolas = $query->fetchAll(PDO::FETCH_ASSOC);

                foreach ($escolas as $escola):
                      // Verificar se a disponibilidade já foi entregue para essa escola
                      $sql = "SELECT COUNT(*) AS total FROM grade_disp_prof WHERE id_professor = :id_professor AND id_escola = :id_escola";
                      $query = $conexao->prepare($sql);
                      $query->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
                      $query->bindValue(':id_escola', $escola['id_escola'], PDO::PARAM_INT);
                      $query->execute();
                      $resultado = $query->fetch(PDO::FETCH_ASSOC);
                      $disponibilidadeEntregue = $resultado['total'] > 0;
            ?>
            <div class="card-esc">
                <?php 
                    $sql = "SELECT nome_escola, id_usuario FROM escola WHERE id_escola = :id_escola";
                    $query = $conexao->prepare($sql);
                    $query->bindValue(':id_escola', $escola['id_escola'], PDO::PARAM_INT);
                    $query->execute();
                    $dadosEsc = $query->fetch(PDO::FETCH_ASSOC);

                    if (isset($dadosEsc['id_usuario'])) {
                        $sql = "SELECT foto_usuario FROM usuario WHERE id_usuario = :id_usuario";
                        $query = $conexao->prepare($sql);
                        $query->bindValue(':id_usuario', $dadosEsc['id_usuario'], PDO::PARAM_INT);
                        $query->execute();
                        $dadosUsu = $query->fetch(PDO::FETCH_ASSOC);
                        $foto_escola = $dadosUsu['foto_usuario'];
                    }
                
                ?>
                <figure>
                    <img src="IMG/UPLOADS/<?php echo $foto_escola; ?>" alt="<?php echo $dadosEsc['nome_escola']; ?>">
                </figure>
                <h3><?php echo $dadosEsc['nome_escola'] ?></h3>
                <?php if ($disponibilidadeEntregue): ?>
                    <a href="?page=orientacoes&id_escola=<?php echo $escola['id_escola']; ?>" class="btn-card">Refazer disponibilidade</a>
                <?php else: ?>
                    <a href="?page=orientacoes&id_escola=<?php echo $escola['id_escola']; ?>" class="btn-card">Entregar disponibilidade</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>    
</div>
<script src="JS/msg.js"></script>
