<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Cadastro de escola</h1>
    <hr>
    <h2>Dados da Escola</h2>
    <form action="inserir_esc.php" method="post">
        <div class="campo">
            <label for="nome_escola">Nome da escola: </label>
            <input type="text" name="nome_escola" id="nome_escola">
        </div>
        <div class="campo">
            <label for="nome_responsavel">Nome do responsável: </label>
            <input type="text" name="nome_responsavel" id="nome_responsavel">
        </div>
        <div class="campo">
            <label for="senha">Senha: </label>
            <input type="password" name="senha" id="senha">
        </div>
        <div class="campo">
            <label for="cnpj">CNPJ: </label>
            <input type="text" name="cnpj" id="cnpj">
        </div>
        <div class="campo">
            <label for="email">E-mail: </label>
            <input type="email" name="email" id="email">
        </div>
        <div class="campo">
            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone">
        </div>
        <div class="campo">
            <label for="cep">CEP: </label>
            <input type="text" name="cep" id="cep">
        </div>
        <hr>
        <h2>Localidade</h2>
        <div class="campo">
            <label for="cidade">Cidade: </label>
            <input type="text" name="cidade" id="cidade">
        </div>
        <div class="campo">
            <label for="bairro">Bairro: </label>
            <input type="text" name="bairro" id="logradouro">
        </div>
        <div class="campo">
            <label for="logradouro">Logradouro: </label>
            <input type="text" name="logradouro" id="logradouro">
        </div>
        <div class="campo">
            <label for="municipio">Municipio: </label>
            <input type="text" name="municipio" id="municipio">
        </div>
        <div class="campo">
            <label for="UF">UF: </label>
            <input type="text" name="UF" id="UF">
        </div>
        <div class="campo">
            <label for="numero">Número: </label>
            <input type="text" name="numero" id="numero">
        </div>
        <div class="campo">
            <label for="complemento">Complemento: </label>
            <input type="text" name="complemento" id="complemento">
        </div>
        <hr>
        <h2>Regra de disponibilidade</h2>
        <div class="campo">
            <label for="nome_regra">Nome da regra: </label>
            <input type="text" name="nome_regra" id="nome_regra">
        </div>
        <div class="campo">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" id="descricao"></textarea>
        </div>
        <input type="submit" value="Cadastrar">
    </form>
</script>
</body>
</html>