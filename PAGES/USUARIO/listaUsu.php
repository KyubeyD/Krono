<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/crud.css">
<link rel="stylesheet" href="CSS/mensagens.css">
<?php 
    require_once 'PAGES/COMPONENTS/sidebar.php';

    // Definir o número de usuários por página
    $usuarios_por_pagina = 6;

    // Verificar a página atual
    $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_atual < 1) $pagina_atual = 1;

    // Calcular o offset para a consulta
    $offset = ($pagina_atual - 1) * $usuarios_por_pagina;

    // Consulta para obter o total de usuários
    $sql_total = "SELECT COUNT(*) as total FROM usuario";
    $query_total = $conexao->query($sql_total);
    $total_usuarios = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para obter os usuários da página atual
    $sql = "SELECT * FROM usuario LIMIT :limite OFFSET :offset";
    $query = $conexao->prepare($sql);
    $query->bindValue(':limite', $usuarios_por_pagina, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC); // Obtém os resultados da página atual

    // Calcular o número total de páginas
    $total_paginas = ceil($total_usuarios / $usuarios_por_pagina);
?>
<div class="content">
    <?php 
        require_once 'PAGES/COMPONENTS/topbar.php';
    ?>

    <div class="container">
        <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=users">Usuários</a></h1>
        <?php require_once 'mensagens.php'; ?>
        <div class="head-crud">
            <h2>Lista de Usuários</h2>
            <div class="add-user">
                <span>Adicionar usuário</span>  
                <i class='bx bx-plus'></i>
            </div>
        </div>
        <table class="crud">
            <thead>
                <tr class="campos">
                    <td>Foto</td>
                    <td>Nome</td>
                    <td>Senha</td>
                    <td>Data de Cadastro</td>
                    <td>Status</td>
                    <td>Nível</td>
                    <td>Controle</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($usuarios as $dados): 
                        $foto_usuario = !empty($dados['foto_usuario']) ? $dados['foto_usuario'] : 'semfoto.png';
                        $data_cadastro = new DateTime($dados['dt_cadastro_usuario']);
                        if ($dados['status_usuario'] == '0') {
                            $status = "Bloqueado";
                            $link = "<a class='btn-crud ativa' href='PAGES/USUARIO/ativa_usu.php?id_usuario=" . $dados['id_usuario'] . "'><i class='bx bx-lock-open-alt' ></i></a>";
                        } else {
                            $status = "Ativo";
                            $link = "<a class='btn-crud delete' href='PAGES/USUARIO/block_usu.php?id_usuario=" . $dados['id_usuario'] . "'><i class='bx bx-lock-alt'></i></a>";
                        }
    
                        // Nível
                        switch ($dados['nivel_usuario']) {
                            case '1':
                                $nivel = "Professor";
                                break;
                            case '2':
                                $nivel = "Escola";
                                break;
                            case '3':
                                $nivel = "Administrador";
                                break;
                        }
                ?>
                    <tr>
                        <td><img class="foto_usu" src="IMG/UPLOADS/<?php echo $foto_usuario; ?>" alt="Foto de <?php echo $dados['nome_usuario']; ?>"></td>
                        <td><?php echo $dados['nome_usuario']; ?></td>
                        <td><?php echo $dados['senha_usuario']; ?></td>
                        <td><?php echo $data_cadastro->format('d/m/Y'); ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $nivel; ?></td>
                        <td class="controle">
                            <a class="btn-crud view" href="view_usu.php?id_usuario=<?php echo $dados['id_usuario']; ?>"><i class='bx bx-file'></i></a>
                            <a class="btn-crud edit" href="fedi_usu.php?id_usuario=<?php echo $dados['id_usuario']; ?>"><i class='bx bxs-edit-alt' ></i></a>
                            <?php echo $link; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>    

        <!-- Botões de paginação -->
        <div class="paginacao">
            <?php if ($pagina_atual > 1): ?>
                <a href="?page=users&pagina=<?= $pagina_atual - 1 ?>" class="btn-paginacao">Anterior</a>
            <?php endif; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="?page=users&pagina=<?= $pagina_atual + 1 ?>" class="btn-paginacao">Próxima</a>
            <?php endif; ?> 
        </div>
    </div>    
</div>
<script src="JS/msg.js"></script>
