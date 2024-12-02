<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/mensagens.css">
<link rel="stylesheet" href="CSS/modal.css">
<?php 
require_once 'PAGES/COMPONENTS/sidebar.php';

$nivel_usuario = $_SESSION['nivel_usuario'];

// Nível 1: Não tem acesso
if ($nivel_usuario == 1) {
    header("Location: logout.php");
    exit;
}

// Configurar a visibilidade das disciplinas com base no nível
if ($nivel_usuario == 2) {
    $id_usuario = $_SESSION['id_usuario'];
    $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $query->execute();
    $escola = $query->fetch(PDO::FETCH_ASSOC);
    $id_escola = $escola['id_escola'];
}

// Definir o número de disciplinas por página
$disciplinas_por_pagina = 15;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_atual = max(1, $pagina_atual); // Garante que não seja menor que 1
$offset = ($pagina_atual - 1) * $disciplinas_por_pagina;

// Consulta otimizada de disciplinas
if ($nivel_usuario == 2) {
    $sql = "
        SELECT d.id_disciplina, d.nome_disciplina, d.sigla_disciplina, carga_horaria
        FROM disciplina d
        WHERE d.id_escola = :id_escola
        LIMIT :limite OFFSET :offset
    ";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql = "
        SELECT d.id_disciplina, d.nome_disciplina, d.sigla_disciplina
        FROM disciplina d
        LIMIT :limite OFFSET :offset
    ";
    $query = $conexao->prepare($sql);
}

$query->bindValue(':limite', $disciplinas_por_pagina, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$disciplinas = $query->fetchAll(PDO::FETCH_ASSOC);

// Consulta para o total de disciplinas
if ($nivel_usuario == 2) {
    $sql_total = "
        SELECT COUNT(*) as total 
        FROM disciplina d 
        WHERE d.id_escola = :id_escola
    ";
    $query_total = $conexao->prepare($sql_total);
    $query_total->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql_total = "SELECT COUNT(*) as total FROM disciplina";
    $query_total = $conexao->prepare($sql_total);
}

$query_total->execute();
$total_disciplinas = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

$total_paginas = ceil($total_disciplinas / $disciplinas_por_pagina);
?>
<div class="content">
    <?php 
    require_once 'PAGES/COMPONENTS/topbar.php';
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=disciplinas">Disciplinas</a></h1>
        <?php require_once 'mensagens.php'; ?>
        <div class="head-crud">
            <h2>Lista de Disciplinas</h2>
            <div class="func-head">
                <?php if ($nivel_necessario == 3): ?>
                <a href="?page=relatorioDisciplina" target="_blank" class="add-user">
                <?php elseif ($nivel_necessario == 2): ?>
                <a href="?page=relatorioDisciplina&id_escola=<?php echo $id_escola; ?>" target="_blank" class="add-user">
                <?php endif; ?>
                    <span>Relatório</span>
                    <i class='bx bx-receipt'></i>
                </a>
                <div class="add-user" onclick="document.getElementById('modalCadastro').style.display='block'">
                    <span>Adicionar disciplina</span>  
                    <i class='bx bx-plus'></i>
                </div>
            </div>
        </div>

        <?php if (empty($disciplinas)): ?>
            <p class="aviso">Nenhuma disciplinada foi adicionada.</p>
        <?php else: ?>

            
            <table class="crud">
                <tr class="campos">
                <td>ID</td>
                <td>Nome</td>
                <td>Sigla</td>
                <td>Carga horária</td>
                <td>Controle</td>
            </tr>
            <?php foreach ($disciplinas as $dados): ?>
                <tr>
                    <td><?= $dados['id_disciplina']; ?></td>
                    <td><?= $dados['nome_disciplina']; ?></td>
                    <td><?= $dados['sigla_disciplina']; ?></td>
                    <td><?= $dados['carga_horaria']; ?> horas</td>
                    <td class='controle'>
                        <span class="btn-crud view" data-action="view" data-tipo="disciplina" data-id="<?= $dados['id_disciplina'] ?>"><i class='bx bx-file'></i></span>
                        <span class="btn-crud edit" data-action="edit" data-tipo="disciplina" data-id="<?= $dados['id_disciplina'] ?>"><i class='bx bxs-edit-alt'></i></span>
                        <a class='btn-crud delete' href='PAGES/DISCIPLINA/excluirDisc.php?id_disciplina=<?= $dados['id_disciplina'] ?>'><i class='bx bxs-trash'></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>    
            
            <!-- Botões de paginação -->
            <div class="paginacao">
            <?php if ($pagina_atual > 1): ?>
                <a href="?page=disciplinas&pagina=<?= $pagina_atual - 1 ?>" class="btn-paginacao">Anterior</a>
                <?php endif; ?>
                
                <?php if ($pagina_atual < $total_paginas): ?>
                    <a href="?page=disciplinas&pagina=<?= $pagina_atual + 1 ?>" class="btn-paginacao">Próxima</a>
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
        <h2 class="modal-title">Adicionar disciplina</h2>
        <form action="PAGES/DISCIPLINA/inserirDisc.php" method="post">
            <?php if ($nivel_necessario == 2): ?>
                <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
            <?php endif; ?>   
            <div class="campo">
                <label class="modal-label" for="nome_curso">Nome da disciplina:</label>
                <input type="text" name="nome_disciplina" id="nome_disciplina" class="modal-form" required>
            </div>
            <div class="campo">
                <label class="modal-label" for="sigla_curso">Sigla:</label>
                <input type="text" name="sigla_disciplina" id="sigla_disciplina" class="modal-form" required>
            </div>
            <div class="campo">
                <label class="modal-label" for="carga_horaria">Carga horária:</label>
                <input type="number" name="carga_horaria" id="carga_horaria" class="modal-form" required>
            </div>
            <?php if ($nivel_necessario == 2): ?>
            <div class="campo">
                <label class="modal-label">Cursos: </label>
                <div class="checkbox-modal">
                    <?php 
                        $sql = "SELECT id_curso, nome_curso FROM curso WHERE id_escola = :id_escola";
                        $query = $conexao->prepare($sql);
                        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                        $query->execute();
                        $cursos = $query->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($cursos as $curso):
                    ?>
                        <div class="campo">
                            <input class="checkbox" type="checkbox" name="cursos[]" value="<?php echo $curso['id_curso']; ?>" id="<?php echo $curso['nome_curso']; ?>">
                            <label class="modal-label" for="<?php echo $curso['nome_curso']; ?>"><?php echo $curso['nome_curso']; ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </div>
            <?php endif; ?>
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


