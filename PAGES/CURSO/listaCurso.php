<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/modal.css">
<link rel="stylesheet" href="CSS/mensagens.css">

<?php 
require_once 'PAGES/COMPONENTS/sidebar.php';

$nivel_usuario = $_SESSION['nivel_usuario'];

// Nível 1: Não tem acesso
if ($nivel_usuario == 1) {
    header("Location: logout.php");
    exit;
}

// Configurar a visibilidade dos cursos com base no nível
if ($nivel_usuario == 2) {
    // Obter a escola vinculada ao usuário
    $id_usuario = $_SESSION['id_usuario'];
    $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $query->execute();
    $escola = $query->fetch(PDO::FETCH_ASSOC);
    $id_escola = $escola['id_escola'];
}

// Definir o número de cursos por página
$cursos_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;
$offset = ($pagina_atual - 1) * $cursos_por_pagina;

// Consulta de cursos ajustada com base no nível
if ($nivel_usuario == 2) {
    $sql = "SELECT * FROM curso WHERE id_escola = :id_escola LIMIT :limite OFFSET :offset";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql = "SELECT * FROM curso LIMIT :limite OFFSET :offset";
    $query = $conexao->prepare($sql);
}
$query->bindValue(':limite', $cursos_por_pagina, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$cursos = $query->fetchAll(PDO::FETCH_ASSOC);

// Consulta para o total de cursos (apenas nível 2 ou 3)
if ($nivel_usuario == 2) {
    $sql_total = "SELECT COUNT(*) as total FROM curso WHERE id_escola = :id_escola";
    $query_total = $conexao->prepare($sql_total);
    $query_total->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql_total = "SELECT COUNT(*) as total FROM curso";
    $query_total = $conexao->query($sql_total);
}
$query_total->execute();
$total_cursos = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

$total_paginas = ceil($total_cursos / $cursos_por_pagina);
?>

<div class="content">
    <?php require_once 'PAGES/COMPONENTS/topbar.php'; ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=cursos">Cursos</a></h1>
        <?php require_once 'mensagens.php'; ?>

        <div class="head-crud">
            <h2>Lista de Cursos</h2>
            <div class="func-head">
                <?php if ($nivel_necessario == 3): ?>
                <a href="?page=relatorioCurso" target="_blank" class="add-user">
                <?php elseif ($nivel_necessario == 2): ?>
                <a href="?page=relatorioCurso&id_escola=<?php echo $id_escola; ?>" target="_blank" class="add-user">
                <?php endif; ?>
                    <span>Relatório</span>
                    <i class='bx bx-receipt'></i>
                </a>
                <div class="add-user" onclick="document.getElementById('modalCadastro').style.display='block'">
                    <span>Adicionar curso</span>  
                    <i class='bx bx-plus'></i>
                </div>
            </div>
        </div>
        
        <?php if (empty($cursos)): ?>
            <p class="aviso">Nenhum curso foi adicionado.</p>
        <?php else: ?>
            
            <table class="crud">
                <tr class="campos">
                    <td>ID</td>
                    <td>Nome</td>
                    <td>Sigla</td>
                <td>Controle</td>
            </tr>
            <?php foreach ($cursos as $dados): ?>
                <tr>
                    <td><?= $dados['id_curso'] ?></td>
                    <td><?= $dados['nome_curso'] ?></td>
                    <td><?= $dados['sigla_curso'] ?></td>
                    <td class='controle'>
                        <span class="btn-crud view" data-action="view" data-tipo="curso" data-id="<?= $dados['id_curso'] ?>"><i class='bx bx-file'></i></span>
                        <span class="btn-crud edit" data-action="edit" data-tipo="curso" data-id="<?= $dados['id_curso'] ?>"><i class='bx bxs-edit-alt'></i></span>
                        <a class='btn-crud delete' href='PAGES/CURSO/excluirCurso.php?id_curso=<?= $dados['id_curso'] ?>'><i class='bx bxs-trash'></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>    
            
            <!-- Botões de paginação -->
            <div class="paginacao">
                <?php if ($pagina_atual > 1): ?>
                    <a href="?page=cursos&pagina=<?= $pagina_atual - 1 ?>" class="btn-paginacao">Anterior</a>
                    <?php endif; ?>
                    
                    <?php if ($pagina_atual < $total_paginas): ?>
                        <a href="?page=cursos&pagina=<?= $pagina_atual + 1 ?>" class="btn-paginacao">Próxima</a>
                        <?php endif; ?> 
                    </div>
                </div>    
            </div>
            
            <?php endif; ?>
            <div id="modalEdit" class="modal" style="display:none;">
                <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalEdit').style.display='none'">&times;</span>
                <h2 class="modal-title"></h2>
                <form action="" method="post">
                    
                    </form>
                </div>
            </div>
            
<div id="modalCadastro" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modalCadastro').style.display='none'">&times;</span>
        <h2 class="modal-title">Adicionar Curso</h2>
        <form action="PAGES/CURSO/inserirCurso.php" method="post">
            <?php if ($nivel_necessario == 2): ?>
                <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
            <?php endif; ?>   
            <div class="campo">
                <label class="modal-label" for="nome_curso">Nome do Curso:</label>
                <input type="text" name="nome_curso" id="nome_curso" class="modal-form" required>
            </div>
            <div class="campo">
                <label class="modal-label" for="sigla_curso">Sigla:</label>
                <input type="text" name="sigla_curso" id="sigla_curso" class="modal-form" required>
            </div>
            <?php if ($nivel_necessario == 3): ?>
                <div class="campo">
                    <label class="modal-label" for="id_escola">Escola:</label>
                    <select name="id_escola" id="id_escola" required>
                        <option value="">Selecione uma escola...</option>
                        <?php 
                            $sql = "SELECT nome_escola, id_escola FROM escola";
                            $query = $conexao->prepare($sql);
                            $query->execute();
                            $escolas = $query->fetchAll(PDO::FETCH_ASSOC);
    
                            foreach ($escolas as $escola):
                        ?>
                        <option value="<?php echo $escola['id_escola']; ?>"><?php echo $escola['nome_escola']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <input type="submit" value="Adicionar" class="btn-modal">
        </form>
    </div>
</div>


<script src="JS/msg.js"></script>
<script src="JS/modal.js"></script>
<script src="JS/viewedit.js"></script>
