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
        $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
        $queryUsu = $conexao->prepare($sql);
        $queryUsu->bindValue(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
        $queryUsu->execute();
        $dadosUsu = $queryUsu->fetch(PDO::FETCH_ASSOC);
        $id_escola = $dadosUsu['id_escola'];
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=regra">Regras</a></h1>
        <div class="head-crud">
            <h2>Minhas regras de disponibilidade</h2>
            <div class="func-head">
                <div class="add-user"  onclick="document.getElementById('modalCadastroRegra').style.display='block'">
                    <span>Adicionar regra</span>  
                    <i class='bx bx-plus'></i>
                </div>
                <div class="add-user"  onclick="document.getElementById('modalCadastroCarga').style.display='block'">
                    <span>Adicionar carga horária</span>  
                    <i class='bx bx-plus'></i>
                </div>
            </div>
        </div>
        <div id="modalCadastroRegra" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalCadastroRegra').style.display='none'">&times;</span>
                <h2 class="modal-title">Adicionar regra</h2>
                <form action="PAGES/REGRA/inserirRegra.php" method="post">
                    <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
                    <div class="campo">
                        <label class="modal-label" for="nome_regra">Nome da regra: </label>
                        <input type="text" name="nome_turma" id="nome_turma" class="modal-form" required>
                    </div>
                    <div class="campo">
                        <label class="modal-label" for="descricao">Descrição: </label>
                        <textarea name="descricao" id="descricao" class="modal-form"></textarea>
                    </div>
                    <div class="campo">
                        <label class="modal-label" for="importante">Importante: </label>
                        <input type="text" name="importante" id="importante" class="modal-form" required>
                    </div>
                    <input type="submit" class="btn-modal" value="Adicionar">
                </form>
            </div>
        </div>
        <div id="modalCadastroCarga" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalCadastroCarga').style.display='none'">&times;</span>
                <h2 class="modal-title">Adicionar carga horária</h2>
                <form action="PAGES/REGRA/inserirCarga.php" method="post">
                    <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
                    <div class="campo">
                        <label class="modal-label" for="horas_semanais">Horas semanais: </label>
                        <input type="number" name="horas_semanais" id="horas_semanais" class="modal-form" required>
                    </div>
                    <input type="submit" class="btn-modal" value="Adicionar">
                </form>
            </div>
        </div>
        <div id="modalEdit" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalEdit').style.display='none'">&times;</span>
                <h2 class="modal-title"></h2>
                <form action="" method="post">

                </form>
            </div>
        </div>

        <?php 
            $sql = "SELECT COUNT(*) AS total FROM regra_disp WHERE id_escola = :id_escola";
            $verificar_regra = $conexao->prepare($sql);
            $verificar_regra->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $verificar_regra->execute();
            $resultado_regra = $verificar_regra->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT COUNT(*) AS total FROM carga_horaria_esc WHERE id_escola = :id_escola";
            $verificar_carga = $conexao->prepare($sql);
            $verificar_carga->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $verificar_carga->execute();
            $resultado_carga = $verificar_carga->fetch(PDO::FETCH_ASSOC);

            if ($resultado_regra['total'] > 0 || $resultado_carga['total'] > 0):
        ?>
        <div class="content-cards" id="content-regra">
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
                    <div class="icons">
                        <i class='bx bx-pin'></i>
                        <div class="visible" id="visible"><i class='bx bx-show'></i></div>
                    </div>
                </div>
                <div class="body-card">
                    <div class="content-text">
                        <p><?php echo $regra['descricao']; ?></p>
                        <p class="importante"><?php echo $regra['importante']; ?></p>
                    </div>
                    <div class="actions">
                        <span data-tipo="regra_disp" data-id="<?php echo $regra['id_regra']; ?>" class="btn-crud edit"> <i class='bx bxs-edit-alt'></i></span>
                        <a href="PAGES/REGRA/excluirRegra.php?id_regra=<?php echo $regra['id_regra']; ?>" class="btn-crud delete"><i class='bx bxs-trash'></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="content-cards" id="content-carga">
            <?php 
                $sql = "SELECT * FROM carga_horaria_esc WHERE id_escola = :id_escola";
                $query = $conexao->prepare($sql);
                $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                $query->execute();
                $cargas = $query->fetchAll(PDO::FETCH_ASSOC);

                foreach ($cargas as $carga):
            ?>
            <div class="card-carga">
                <div class="text-carga">
                    <h2><?php echo $carga['horas_semanais'] . " horas"; ?></h2>
                    <p>Semanais</p>
                </div>
                <div class="actions">
                    <span data-tipo="carga_horaria_esc" data-id="<?php echo $carga['id_carga_horaria_esc']; ?>" class='btn-crud edit'><i class='bx bxs-edit-alt' ></i></span>
                    <a href="PAGES/REGRA/excluirCarga.php?id_carga_horaria_esc=<?php echo $carga['id_carga_horaria_esc']; ?>" class='btn-crud delete' ><i class='bx bxs-trash'></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="aviso">Nenhuma orientação foi adicionada.</p>
            <?php endif; ?>
        </div>
</div>
<script src="JS/modals.js"></script>
<script src="JS/card.js"></script>
<script src="JS/viewedit.js"></script>