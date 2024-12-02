<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/modal.css">
<link rel="stylesheet" href="CSS/cards.css">
<?php 
    require_once 'PAGES/COMPONENTS/sidebar.php';
?>
<div class="content">
    <?php 
        require_once 'PAGES/COMPONENTS/topbar.php';
        $id_escola = $_GET['id_escola'];
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=disponibilidade_professor">Disponibilidade</a> > <a href="?page=orientacoes">Orientações</a></h1>
        <div class="head-crud">
            <h2>Orientações e regras</h2>
        </div>
        <div id="modalCadastro" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalCadastro').style.display='none'">&times;</span>
                <h2 class="modal-title">Adicionar regra</h2>
                <form action="PAGES/REGRA/inserirRegra.php" method="post">
                    <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
                    <div class="campo">
                        <label for="nome_regra">Nome da regra: </label>
                        <input type="text" name="nome_turma" id="nome_turma" class="modal-form" required>
                    </div>
                    <div class="campo">
                        <label for="descricao">Descrição: </label>
                        <textarea name="descricao" id="descricao" class="modal-form"></textarea>
                    </div>
                    <div class="campo">
                        <label for="importante">Importante: </label>
                        <input type="text" name="importante" id="importante" class="modal-form" required>
                    </div>
                    <input type="submit" class="btn-modal" value="Adicionar">
                </form>
            </div>
        </div>

        <?php 
            $sql = "SELECT COUNT(*) AS total FROM regra_disp WHERE id_escola = :id_escola";
            $verificar_regra = $conexao->prepare($sql);
            $verificar_regra->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $verificar_regra->execute();
            $resultado_regra = $verificar_regra->fetch(PDO::FETCH_ASSOC);

            if ($resultado_regra['total'] > 0):
        ?>
        <div class="content-cards">
            <?php 
                $sql = "SELECT * FROM regra_disp WHERE id_escola = :id_escola";
                $query = $conexao->prepare($sql);
                $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                $query->execute();
                $regras = $query->fetchAll(PDO::FETCH_ASSOC);

                foreach ($regras as $regra):
            ?>
            <div class="card">
                <div class="head-card">
                    <h5><?php echo $regra['nome_regra'] ?></h5>
                    <div class="visible" id="visible"><i class='bx bx-show'></i></div>
                <!-- <i class='bx bx-low-vision'></i> -->
                </div>
                <div class="body-card">
                    <p><?php echo $regra['descricao']; ?></p>
                    <p class="importante"><?php echo $regra['importante']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="aviso">Nenhuma grade foi inserida para a escola.</p>
        <?php endif; ?>
        <a href="?page=minha_disponibilidade&id_escola=<?php echo $id_escola; ?>" class="btn-next">Seguir</a>
    </div>
</div>
<script src="JS/modal.js"></script>
<script src="JS/card.js"></script>