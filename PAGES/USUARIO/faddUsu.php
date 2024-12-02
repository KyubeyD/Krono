<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="inserir_usu.php" method="post" enctype="multipart/form-data">
        <div class="campo">
            <label for="nome_usuario">Nome: </label>
            <input type="text" name="nome_usuario" id="nome_usuario">
        </div>
        <div class="campo">
            <label for="senha_usuario">Senha: </label>
            <input type="password" name="senha_usuario" id="senha_usuario">
        </div>
        <div class="campo">
            <label for="dt_cadastro_usuario">Data de Cadastro: </label>
            <input type="text" name="dt_cadastro_usuario" id="dt_cadastro_usuario" value="<?php echo date('d/m/Y'); ?>" disabled>
        </div>
        <div class="campo">
            <label for="foto_usuario">Foto de usuario: </label>
            <input type="file" name="foto_usuario" id="foto_usuario" accept="image/*">
        </div>
        <div class="campo">
            <label for="status_usuario">Status: </label>
            <label for="ativo">
                <input type="radio" name="status_usuario" id="ativo" checked disabled> Ativo
            </label>
            <label for="bloqueado">
                <input type="radio" name="status_usuario" id="bloqueado" disabled> Bloqueado
            </label>
        </div>
        <div class="campo">
            <label for="nivel_usuario">Nivel: </label>
            <select name="nivel_usuario" id="nivel_usuario">
                <option value="1">Professor</option>
                <option value="2">Escola</option>
                <option value="3">Administrador</option>
            </select>
        </div>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>