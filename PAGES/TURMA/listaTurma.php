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

// Configurar a visibilidade das turmas com base no nível
if ($nivel_usuario == 2) {
    $id_usuario = $_SESSION['id_usuario'];
    $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $query->execute();
    $escola = $query->fetch(PDO::FETCH_ASSOC);
    $id_escola = $escola['id_escola'];
}

// Definir o número de turmas por página
$turmas_por_pagina = 15;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_atual = max(1, $pagina_atual);
$offset = ($pagina_atual - 1) * $turmas_por_pagina;

// Consulta otimizada de turmas
if ($nivel_usuario == 2) {
    $sql = "
        SELECT t.id_turma, t.nome_turma, t.serie_turma, t.id_curso, t.id_escola
        FROM turma t
        WHERE t.id_escola = :id_escola
        LIMIT :limite OFFSET :offset
    ";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql = "SELECT * FROM turma LIMIT :limite OFFSET :offset";
    $query = $conexao->prepare($sql);
}

$query->bindValue(':limite', $turmas_por_pagina, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$turmas = $query->fetchAll(PDO::FETCH_ASSOC);

// Consulta para o total de turmas
if ($nivel_usuario == 2) {
    $sql_total = "SELECT COUNT(*) as total FROM turma WHERE id_escola = :id_escola";
    $query_total = $conexao->prepare($sql_total);
    $query_total->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
} else {
    $sql_total = "SELECT COUNT(*) as total FROM turma";
    $query_total = $conexao->prepare($sql_total);
}

$query_total->execute();
$total_turmas = $query_total->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($total_turmas / $turmas_por_pagina);
?>


    <div class="content">
        <?php 
        require_once 'PAGES/COMPONENTS/topbar.php';
        ?>

        <div class="container">
            <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=turmas">Turmas</a></h1>
            <?php require_once 'mensagens.php'; ?>
            <div class="head-crud">
                <h2>Lista de Turmas</h2>
                <div class="func-head">
                    <?php if ($nivel_necessario == 3): ?>
                        <a href="?page=relatorioTurma" target="_blank" class="add-user">
                    <?php elseif ($nivel_necessario == 2): ?>
                        <a href="?page=relatorioTurma&id_escola=<?php echo $id_escola; ?>" target="_blank" class="add-user">
                    <?php endif; ?>
                        <span>Relatório</span>
                        <i class='bx bx-receipt'></i>
                    </a>
                    <?php if ($nivel_necessario >= 2): // Exibe para níveis 2 e 3 ?>
                    <div class="add-user" onclick="document.getElementById('modalCadastro').style.display='block'">
                        <span>Adicionar turma</span>
                        <i class='bx bx-plus'></i>
                    </div>
                    <?php endif; ?>
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

            <!-- Modal de Cadastro -->
            <div id="modalCadastro" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="document.getElementById('modalCadastro').style.display='none'">&times;</span>
                    <h2 class="modal-title">Cadastro de Turma</h2>
                    <form action="PAGES/TURMA/inserirTurma.php" method="post">
                    <div class="campo">
                        <label for="nome_turma">Nome: </label>
                        <input type="text" name="nome_turma" id="nome_turma" class="modal-form" required>
                    </div>
                    <div class="campo">
                        <label for="serie_turma">Série: </label>
                        <input type="text" name="serie_turma" id="serie_turma" class="modal-form" required>
                    </div>
                    <?php if ($nivel_necessario == 3): ?>
                    <div class="campo">
                        <label for="id_escola">Escola: </label>
                        <select name="id_escola" id="id_escola" class="modal-form" required>
                            <option value="">Selecione...</option>
                            <?php 
                                $sqlEscola = "SELECT * FROM escola";
                                $stmtEscola = $conexao->prepare($sqlEscola);
                                $stmtEscola->execute();
                                while ($dadosEscola = $stmtEscola->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $dadosEscola['id_escola'] . "'>" . $dadosEscola['nome_escola'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="campo">
                        <label for="id_curso">Curso: </label>
                        <select name="id_curso" id="id_curso" class="modal-form" required>
                            <option value="">Selecione...</option>
                            <?php 
                                $sqlCurso = "SELECT * FROM curso WHERE id_escola = :id_escola";
                                $stmtCurso = $conexao->prepare($sqlCurso);
                                $stmtCurso->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                $stmtCurso->execute();
                                while ($dadosCurso = $stmtCurso->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $dadosCurso['id_curso'] . "'>" . $dadosCurso['nome_curso'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <input type="submit" class="btn-modal" value="Adicionar">
                </form>
                </div>
            </div>

            <?php if (empty($turmas)): ?>
            <p class="aviso">Nenhuma turma foi adicionada.</p>
            <?php else: ?>
                
                <!-- Tabela de Turmas -->
                <table class="crud">
                    <tr class="campos">
                        <td>ID</td>
                        <td>Nome</td>
                        <td>Série</td>
                        <?php if ($nivel_necessario == 3): ?>
                            <td>Escola</td>
                        <?php endif; ?>
                        <td>Curso</td>
                        <td>Controle</td>
                    </tr>
                <?php foreach ($turmas as $dados): ?>
                    <tr>
                        <td><?php echo $dados['id_turma']; ?></td>
                        <td><?php echo $dados['nome_turma']; ?></td>
                        <td><?php echo $dados['serie_turma']; ?>º</td>
                        <?php if ($nivel_necessario == 3): ?>
                            <td>
                                <?php 
                                    $sqlEsc = "SELECT nome_escola FROM escola WHERE id_escola = :id_escola";
                                    $queryEsc = $conexao->prepare($sqlEsc);
                                    $queryEsc->bindValue(':id_escola', $dados['id_escola'], PDO::PARAM_INT);
                                    $queryEsc->execute();
                                    $nomeEsc = $queryEsc->fetchColumn();
                                    echo $nomeEsc;
                                ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?php 
                                $sqlCurso = "SELECT nome_curso FROM curso WHERE id_curso = :id_curso";
                                $queryCurso = $conexao->prepare($sqlCurso);
                                $queryCurso->bindValue(':id_curso', $dados['id_curso'], PDO::PARAM_INT);
                                $queryCurso->execute();
                                $nomeCurso = $queryCurso->fetchColumn();
                                echo "$nomeCurso";
                            ?>
                        </td>
                        <td class="controle">
                            <span class="btn-crud view" data-action="view" data-tipo="turma" data-id="<?= $dados['id_turma'] ?>"><i class='bx bx-file'></i></span>
                            <span class="btn-crud edit" data-action="edit" data-tipo="turma" data-id="<?= $dados['id_turma'] ?>"><i class='bx bxs-edit-alt'></i></span>
                            <a class='btn-crud delete' href='PAGES/TURMA/excluirTurma.php?id_turma=<?= $dados['id_turma'] ?>'><i class='bx bxs-trash'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>    
            
            <!-- Botões de paginação -->
            <div class="paginacao">
                <?php if ($pagina_atual > 1): ?>
                    <a href="?page=turmas&pagina=<?= $pagina_atual - 1 ?>" class="btn-paginacao">Anterior</a>
                    <?php endif; ?>

                    <?php if ($pagina_atual < $total_paginas): ?>
                        <a href="?page=turmas&pagina=<?= $pagina_atual + 1 ?>" class="btn-paginacao">Próxima</a>
                        <?php endif; ?> 
                    </div>
                </div>    
            </div>
            <?php endif; ?>

    <script src="JS/modal.js"></script>
    <script src="JS/msg.js"></script>
    <script src="JS/viewedit.js"></script>

