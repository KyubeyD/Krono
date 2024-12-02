<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
       require_once __DIR__ . '/../../ASSETS/conexao.php';
       
       $id_usuario = $_GET['id_usuario'];
       $sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
       $query = mysqli_query($conexao, $sql);
       $dados = mysqli_fetch_array($query);
       echo "<h1>Edição de " . $dados['nome_usuario'] . "</h1>";
    ?>
    <form action="atualizar_usu.php" method="post">
        <input type="hidden" name="id_usuario" value="<?php echo $dados['id_usuario'] ?>">
        <div class="campo">
            <label for="nome_usuario">Nome: </label>
            <input type="text" name="nome_usuario" id="nome_usuario" value="<?php echo $dados['nome_usuario'] ?>">
        </div>
        <div class="campo">
            <label for="senha_usuario">Senha: </label>
            <input type="password" name="senha_usuario" id="senha_usuario" value="<?php echo $dados['senha_usuario'] ?>">
        </div>
        <div class="campo">
            <label for="dt_cadastro_usuario">Data de Edição: </label>
            <input type="text" name="dt_cadastro_usuario" id="dt_cadastro_usuario" value="<?php echo date('d/m/Y'); ?>" disabled>
        </div>
        <div class="campo">
            <label for="status_usuario">Status: </label>
            <?php 
                switch($dados['status_usuario']) {
                    case '0':
                        echo "
                            <label for='ativo'>
                                <input type='radio' name='status_usuario' id='ativo' value='1'> Ativo
                            </label>
                            <label for='bloqueado'>
                                <input type='radio' name='status_usuario' id='bloqueado' value='0' checked> Bloqueado
                            </label>                            
                        ";
                        break;
                    case '1':
                        echo "
                            <label for='ativo'>
                                <input type='radio' name='status_usuario' id='ativo' value='1' checked> Ativo
                            </label>
                            <label for='bloqueado'>
                                <input type='radio' name='status_usuario' id='bloqueado' value='0'> Bloqueado
                            </label>                            
                        ";
                        break;
                }
            ?>
        </div>
        <div class="campo">
            <label for="nivel_usuario">Nivel: </label>
            <select name="nivel_usuario" id="nivel_usuario">
                <option value="1">Professor</option>
                <option value="2">Escola</option>
                <option value="3">Administrador</option>
            </select>
        </div>
        <input type="submit" value="Editar">
    </form>
</body>
</html>