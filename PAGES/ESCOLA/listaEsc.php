<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/mensagens.css">

<?php 
require_once 'PAGES/COMPONENTS/sidebar.php';

// Definir o número de escolas por página
$escolas_por_pagina = 10;

// Verificar a página atual
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;

// Calcular o offset para a consulta
$offset = ($pagina_atual - 1) * $escolas_por_pagina;

// Consulta para obter o total de escolas
$sql_total = "SELECT COUNT(*) as total FROM escola";
$query_total = $conexao->query($sql_total);
$total_escolas = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

// Consulta para obter as escolas da página atual
$sql = "SELECT * FROM escola LIMIT :limite OFFSET :offset";
$query = $conexao->prepare($sql);
$query->bindValue(':limite', $escolas_por_pagina, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$escolas = $query->fetchAll(PDO::FETCH_ASSOC); // Obtém os resultados da página atual

// Calcular o número total de páginas
$total_paginas = ceil($total_escolas / $escolas_por_pagina);
?>

<div class="content">
    <?php 
    require_once 'PAGES/COMPONENTS/topbar.php';
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=escolas">Escolas</a></h1>
        <?php require_once 'mensagens.php'; ?>
        <div class="head-crud">
            <h2>Lista de Escolas</h2>
            <div class="add-user">
                <span>Adicionar escola</span>  
                <i class='bx bx-plus'></i>
            </div>
        </div>
        <table class="crud">
            <tr class="campos">
                <td>Foto</td>
                <td>Nome</td>
                <td>Responsável</td>
                <td>CNPJ</td>
                <td>E-mail</td>
                <td>Telefone</td>
                <td>CEP</td>
                <td>Número</td>
                <td>Complemento</td>
                <td>Regra</td>
                <td>Status</td>
                <td>Controle</td>
            </tr>
            <?php 
            // Itera sobre os resultados utilizando PDO
            foreach ($escolas as $dados) {
                // Consulta para obter os dados do usuário
                $sqlUsu = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";
                $queryUsu = $conexao->prepare($sqlUsu);
                $queryUsu->bindValue(':id_usuario', $dados['id_usuario'], PDO::PARAM_INT);
                $queryUsu->execute();
                $dadosUsu = $queryUsu->fetch(PDO::FETCH_ASSOC);

                echo "<tr>";
                // Exibir a foto da escola
                echo "<td><img src='IMG/UPLOADS/" . $dadosUsu['foto_usuario'] . "' alt='Foto de " . $dados['nome_escola'] . "' class='foto_usu'></td>";
                echo "<td>" . $dados['nome_escola'] . "</td>";
                echo "<td>" . $dados['nome_responsavel'] . "</td>";
                echo "<td>" . $dados['cnpj'] . "</td>";
                echo "<td>" . $dados['email'] . "</td>";
                echo "<td>" . $dados['telefone'] . "</td>";
                echo "<td>" . $dados['cep'] . "</td>";
                echo "<td>" . $dados['numero'] . "</td>";
                echo "<td>" . $dados['complemento'] . "</td>";
                echo "<td>" . $dados['id_regra'] . "</td>";

                // Exibir status
                echo "<td>";
                echo $dadosUsu['status_usuario'] == '0' ? "Bloqueada" : "Ativa";
                echo "</td>";

                // Controles de edição e exclusão
                echo "<td>
                    <a class='btn-crud view' href='view_esc.php?id_escola=" . $dados['id_escola'] . "'>Detalhar</a>
                    <a class='btn-crud edit' href='fedit_esc.php?id_escola=" . $dados['id_escola'] . "'>Editar</a>";

                // Link para ativar ou bloquear a escola
                if ($dadosUsu['status_usuario'] == '0') {
                    echo "<a class='btn-crud activate' href='ativa_esc.php?id_escola=" . $dados['id_escola'] . "'>Ativar</a>";
                } else {
                    echo "<a class='btn-crud block' href='block_esc.php?id_escola=" . $dados['id_escola'] . "'>Bloquear</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>    

        <!-- Botões de paginação -->
        <div class="paginacao">
            <?php if ($pagina_atual > 1): ?>
                <a href="?page=escolas&pagina=<?= $pagina_atual - 1 ?>" class="btn-paginacao">Anterior</a>
            <?php endif; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="?page=escolas&pagina=<?= $pagina_atual + 1 ?>" class="btn-paginacao">Próxima</a>
            <?php endif; ?> 
        </div>
    </div>    
</div>
<script src="JS/msg.js"></script>
