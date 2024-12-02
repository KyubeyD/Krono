<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/mensagens.css">
<link rel="stylesheet" href="CSS/modal.css">

<?php 
require_once 'PAGES/COMPONENTS/sidebar.php';

// Definir o número de professores por página
if ($_SESSION['nivel_usuario'] == 1) {
    // Redireciona para logout se for professor
    header("Location: logout.php");
    exit;
}

$professores_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;
$offset = ($pagina_atual - 1) * $professores_por_pagina;

// Consulta de total de professores para paginação
$sql_total = "SELECT COUNT(*) as total FROM professor";
$query_total = $conexao->query($sql_total);
$total_professores = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

// Ajustar consulta para Nível 2 (Escola)
if ($_SESSION['nivel_usuario'] == 2) {
    $usuario = $_SESSION['id_usuario'];  // Considerando que o ID da escola está na sessão
    $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_usuario', $usuario, PDO::PARAM_INT);
    $query->execute();
    $escola = $query->fetch(PDO::FETCH_ASSOC);
    $id_escola = $escola['id_escola'];
    $sql = "SELECT professor.*, usuario.foto_usuario, usuario.status_usuario 
    FROM professor 
    INNER JOIN usuario ON professor.id_usuario = usuario.id_usuario
    WHERE professor.id_professor IN (
        SELECT id_professor 
        FROM vinculo_prof_esc 
        WHERE id_escola = :id_escola
    )
    LIMIT :limite OFFSET :offset";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql = "SELECT * FROM professor LIMIT :limite OFFSET :offset";
    $query = $conexao->prepare($sql);
}
$query->bindValue(':limite', $professores_por_pagina, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$professores = $query->fetchAll(PDO::FETCH_ASSOC); // Obtém os resultados da página atual

// Calcular o número total de páginas
$total_paginas = ceil($total_professores / $professores_por_pagina);
?>

<div class="content">
    <?php require_once 'PAGES/COMPONENTS/topbar.php'; ?>

    <div class="container">
        <h1 class="title-page">
            <a href="?page=dashboard">Dashboard</a> > 
            <a href="?page=professores">Professores</a>
        </h1>

        <?php require_once 'mensagens.php'; ?>
        
        <div class="head-crud">
            <h2>Lista de Professores</h2>
            <div class="func-head">
                <?php if ($nivel_necessario == 3): ?>
                <a href="?page=relatorioProfessor" target="_blank" class="add-user">
                <?php elseif ($nivel_necessario == 2): ?>
                <a href="?page=relatorioProfessor&id_escola=<?php echo $id_escola; ?>" target="_blank" class="add-user">
                <?php endif; ?>
                    <span>Relatório</span>
                    <i class='bx bx-receipt'></i>
                </a>
                <div class="add-user" onclick="document.getElementById('modalCadastro').style.display='block'">
                    <?php if ($nivel_necessario == 3): ?>
                        <span>Cadastrar professor</span> 
                    <?php elseif ($nivel_necessario == 2): ?>
                        <span>Vincular professor</span>  
                    <?php endif; ?>
                    <i class='bx bx-plus'></i>
                </div>
            </div>
        </div>
        
        <div id="modalCadastro" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalCadastro').style.display='none'">&times;</span>
                <h2 class="modal-title">Convite para vinculação</h2>
                <form action="PAGES/PROFESSOR/convite.php" method="post" id="formCadastro">
                    <div class="form-modal" id="formVincProf">
                        <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
                        <div class="campo">
                            <label for="email_docente" class="modal-label">E-mail do docente: </label>
                            <input type="email" name="email_docente" id="email_docente" class="modal-form" required>
                        </div>
                    </div>
                    <input type="submit" class="btn-modal" id="submitBtn" value="Convidar">
                </form>
            </div>
        </div>

        <?php if (count($professores) > 0): ?>
            <table class="crud">
                <tr class="campos">
                    <td>Foto</td>
                    <td>Nome</td>
                    <td>E-mail</td>
                    <td>Telefone</td>
                    <td>Controle</td>
                </tr>
                <?php 
                // Itera sobre os resultados utilizando PDO
                foreach ($professores as $dados): ?>
                    <tr>
                        <td><img src="IMG/UPLOADS/<?php echo $dados['foto_usuario']; ?>" class="foto_usu" alt="<?php echo $dados['nome_professor']; ?>"></td>
                        <td><?php echo $dados['nome_professor']; ?></td>
                        <td><?php echo $dados['email_professor']; ?></td>
                        <td><?php echo $dados['telefone_professor']; ?></td>
                        <td class="controle">
                            <?php if ($nivel_necessario == 3):  ?>
                                <span data-id="<?php echo $dados['id_professor']; ?>" data-tipo="professor" data-action="view" class='btn-crud view'><i class='bx bx-file'></i></span>
                                <span data-id="<?php echo $dados['id_professor']; ?>" data-tipo="professor" data-action="edit" class='btn-crud edit'><i class='bx bxs-edit-alt' ></i></span>
                                <a class='btn-crud delete'><i class='bx bx-block'></i></a>
                            <?php elseif ($nivel_necessario == 2): ?>
                                <span data-id="<?php echo $dados['id_professor']; ?>" data-tipo="professor" data-action="view" class='btn-crud view'><i class='bx bx-file'></i></span>
                                <a href="PAGES/PROFESSOR/desvincular.php?id_professor=<?php echo $dados['id_professor']; ?>" class='btn-crud delete'><i class='bx bxs-trash' ></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($nivel_necessario == 3): ?>
            <p class="aviso">Nenhum professor cadastrado.</p>
        <?php elseif ($nivel_necessario == 2): ?>
            <p class="aviso">Nenhum professor vinculado.</p>
        <?php endif; ?>

        <!-- Modal -->
        <div id="modalEdit" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalEdit').style.display='none'">&times;</span>
                <h2 class="modal-title"></h2>
                <form action="" method="post">

                </form>
            </div>
        </div>
        <!-- Fim do Modal -->

        <!-- Botões de paginação -->
        <div class="paginacao">
            <?php if ($pagina_atual > 1): ?>
                <a class="btn-paginacao" href="?page=professores&pagina=<?= $pagina_atual - 1 ?>">Anterior</a>
            <?php endif; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a class="btn-paginacao" href="?page=professores&pagina=<?= $pagina_atual + 1 ?>">Próxima</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="JS/modal.js"></script>
<script src="JS/viewedit.js"></script>

