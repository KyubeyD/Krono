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

        $id_professor = $_GET['id_professor'];
        $sqlProf = "SELECT * FROM professor WHERE id_professor = $id_professor";
        $queryProf = mysqli_query($conexao, $sqlProf);
        $dadosProf = mysqli_fetch_array($queryProf);

        $sqlUsu = "SELECT senha_usuario FROM usuario WHERE id_usuario = " . $dadosProf['id_usuario'] . ";";
        $queryUsu = mysqli_query($conexao, $sqlUsu);
        $senhaProf = mysqli_fetch_assoc($queryUsu)['senha_usuario'];
    ?>
    <h1>Edição de <?php echo $dadosProf['nome_professor']; ?></h1>
    <form action="atualizar_prof.php" method="post">
        <input type="hidden" name="id_professor" value="<?php echo $dadosProf['id_professor']; ?>">
        <input type="hidden" name="id_usuario" value="<?php echo $dadosProf['id_usuario']; ?>">
        <div class="campo">
            <label for="nome_professor">Nome: </label>
            <input type="text" name="nome_professor" id="nome_professor" value="<?php echo $dadosProf['nome_professor']; ?>">
        </div>
        <div class="campo">
            <label for="senha">Senha: </label>
            <input type="text" name="senha" id="senha" value="<?php echo $senhaProf; ?>">
        </div>
        <div class="campo">
            <label for="email_professor">E-mail: </label>
            <input type="email" name="email_professor" id="email_professor" value="<?php echo $dadosProf['email_professor']; ?>">
        </div>
        <div class="campo">
            <label for="telefone_professor">Telefone: </label>
            <input type="text" name="telefone_professor" id="telefone_professor" value="<?php echo $dadosProf['telefone_professor']; ?>">
        </div>
        <div class="campo">
            <label for="cpf_professor">CPF: </label>
            <input type="text" name="cpf_professor" id="cpf_professor" value="<?php echo $dadosProf['cpf_professor']; ?>">
        </div>
        <input type="submit" value="Editar">
    </form>
</body>
</html>