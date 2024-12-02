
<link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/topbar.css">
    <link rel="stylesheet" href="CSS/content.css">
    <link rel="stylesheet" href="CSS/crud.css">
    <link rel="stylesheet" href="CSS/mensagens.css">
    <link rel="stylesheet" href="CSS/modal.css">
<?php 
require_once 'PAGES/COMPONENTS/sidebar.php';

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
$query = $conexao->prepare($sql);
$query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
$query->execute();
$escola = $query->fetch(PDO::FETCH_ASSOC);
$id_escola = $escola['id_escola'];

// Definir o número de turmas por página
$turmas_por_pagina = 15;

// Verificar a página atual
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;

// Calcular o offset para a consulta
$offset = ($pagina_atual - 1) * $turmas_por_pagina;

// Consulta para obter o total de turmas
$sql_total = "SELECT COUNT(*) as total FROM turma WHERE id_escola = $id_escola";
$query_total = $conexao->query($sql_total);
$total_turmas = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

// Consulta para obter as turmas da página atual
$sql = "SELECT * FROM turma WHERE id_escola = :id_escola LIMIT :limite OFFSET :offset";
$query = $conexao->prepare($sql);
$query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
$query->bindValue(':limite', $turmas_por_pagina, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$turmas = $query->fetchAll(PDO::FETCH_ASSOC);

// Calcular o número total de páginas
$total_paginas = ceil($total_turmas / $turmas_por_pagina);
?>
<style>
    .crud {
        flex-grow: 0.7;
    }
</style>

<div class="content">
    <?php 
    require_once 'PAGES/COMPONENTS/topbar.php';
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=turmas">Grades Horárias</a></h1>
        <div class="head-crud">
            <h2>Grades das turmas</h2>
            <div class="func-head">
            <?php if ($nivel_necessario == 3): ?>
                        <a href="?page=relatorioTurma" target="_blank" class="add-user">
                    <?php elseif ($nivel_necessario == 2): ?>
                        <a href="?page=relatorioGradeHoraria&id_escola=<?php echo $id_escola; ?>" target="_blank" class="add-user">
                    <?php endif; ?>
                        <span>Relatório</span>
                        <i class='bx bx-receipt'></i>
                    </a>
            </div>
        </div>
        <?php 
            $sql = "SELECT COUNT(*) AS total FROM turma WHERE id_escola = :id_escola";
            $verificar_turma = $conexao->prepare($sql);
            $verificar_turma->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $verificar_turma->execute();
            $resultado_turma = $verificar_turma->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado_turma['total'] > 0):
        ?>
        <!-- Tabela de Turmas -->
        <table class="crud">
            <tr class="campos">
                <td>ID</td>
                <td>Nome</td>
                <td>Série</td>
                <td>Curso</td>
                <td>Controle</td>
            </tr>
            <?php 
            foreach ($turmas as $dados) {
                echo "<tr>";
                echo "<td>" . $dados['id_turma'] . "</td>";
                echo "<td>" . $dados['nome_turma'] . "</td>";
                echo "<td>" . $dados['serie_turma'] . "</td>";

                // Obter o nome do curso
                $sqlCurso = "SELECT nome_curso FROM curso WHERE id_curso = :id_curso";
                $queryCurso = $conexao->prepare($sqlCurso);
                $queryCurso->bindValue(':id_curso', $dados['id_curso'], PDO::PARAM_INT);
                $queryCurso->execute();
                $nomeCurso = $queryCurso->fetchColumn();
                echo "<td>$nomeCurso</td>";

                echo "
                    <td class='controle'>
                        <a class='btn-make' href='?page=fazer_grade&id_turma=" .$dados['id_turma'] . "'>Fazer grade</a>
                        <a class='btn-make' href='?page=view_grade&id_turma=" .$dados['id_turma'] . "'>Ver Grade</a>
                    </td>
                ";
                echo "</tr>";
            }
            ?>
        </table>    

        <!-- Botões de paginação -->
        <div class="paginacao">
            <?php if ($pagina_atual > 1): ?>
                <a href="?page=grades_horarias&pagina=<?= $pagina_atual - 1 ?>" class="btn-paginacao">Anterior</a>
            <?php endif; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="?page=grades_horarias&pagina=<?= $pagina_atual + 1 ?>" class="btn-paginacao">Próxima</a>
            <?php endif; ?> 
        </div>
        <?php else: ?>
            <p class="aviso">Nenhuma turma foi cadastrada pela escola.</p>
        <?php endif; ?>
    </div>    
</div>
<script src="JS/modal.js"></script>


