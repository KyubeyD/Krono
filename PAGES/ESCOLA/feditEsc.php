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
       
       $id_escola = $_GET['id_escola'];
       $sqlEsc = "SELECT * FROM escola WHERE id_escola = $id_escola";
       $queryEsc = mysqli_query($conexao, $sqlEsc);
       $dadosEsc = mysqli_fetch_array($queryEsc);

       $sqlUsu = "SELECT senha_usuario FROM usuario WHERE id_usuario = " . $dadosEsc['id_usuario'] . ";";
       $querySenha = mysqli_query($conexao, $sqlUsu);
       $senhaEsc = mysqli_fetch_assoc($querySenha)['senha_usuario'];

       $sqlRegra = "SELECT * FROM regra_disp WHERE id_regra = " . $dadosEsc['id_regra'] . ";";
       $queryRegra = mysqli_query($conexao, $sqlRegra);
       $dadosRegra = mysqli_fetch_array($queryRegra);

       echo "<h1>Edição de " . $dadosEsc['nome_escola'] . "</h1>";
    ?>
    <hr>
    <h2>Dados da Escola</h2>
    <form action="atualizar_esc.php" method="post">
        <input type="hidden" name="id_escola" value="<?php echo $dadosEsc['id_escola']; ?>">
        <input type="hidden" name="id_usuario" value="<?php echo $dadosEsc['id_usuario']; ?>">
        <div class="campo">
            <label for="nome_escola">Nome da escola: </label>
            <input type="text" name="nome_escola" id="nome_escola" value="<?php echo $dadosEsc['nome_escola']; ?>">
        </div>
        <div class="campo">
            <label for="nome_responsavel">Nome do responsável: </label>
            <input type="text" name="nome_responsavel" id="nome_responsavel" value="<?php echo $dadosEsc['nome_responsavel']; ?>"> 
        </div>
        <div class="campo">
            <label for="senha">Senha: </label>
            <input type="password" name="senha" id="senha" value="<?php echo $senhaEsc; ?>">
        </div>
        <div class="campo">
            <label for="cnpj">CNPJ: </label>
            <input type="text" name="cnpj" id="cnpj" value="<?php echo $dadosEsc['cnpj'];?>">
        </div>
        <div class="campo">
            <label for="email">E-mail: </label>
            <input type="email" name="email" id="email" value="<?php echo $dadosEsc['email']; ?>" >
        </div>
        <div class="campo">
            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone" value="<?php echo $dadosEsc['telefone']; ?>">
        </div>
        <div class="campo">
            <label for="numero">Número: </label>
            <input type="text" name="numero" id="numero" value="<?php echo $dadosEsc['numero']; ?>">
        </div>
        <div class="campo">
            <label for="complemento">Complemento: </label>
            <input type="text" name="complemento" id="complemento" value="<?php echo $dadosEsc['complemento']; ?>">
        </div>
        <hr>
        <h2>Regra de disponibilidade</h2>
        <input type="hidden" name="id_regra" value="<?php echo $dadosRegra['id_regra']; ?>">
        <div class="campo">
            <label for="nome_regra">Nome da regra: </label>
            <input type="text" name="nome_regra" id="nome_regra" value="<?php echo $dadosRegra['nome_regra']; ?>">
        </div>
        <div class="campo">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" id="descricao"><?php echo $dadosRegra['descricao']; ?></textarea>
        </div>
        <input type="submit" value="Editar">
    </form>
</script>
</body>
</html>