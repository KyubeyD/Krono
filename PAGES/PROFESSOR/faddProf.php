<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Cadastro de professor(a)</h1>
    <form action="inserir_prof.php" method="post">
        <div class="campo">
            <label for="nome_professor">Nome: </label>
            <input type="text" name="nome_professor" id="nome_professor">
        </div>
        <div class="campo">
            <label for="senha">Senha: </label>
            <input type="password" name="senha" id="senha">
        </div>
        <div class="campo">
            <label for="email_professor">E-mail: </label>
            <input type="email" name="email_professor" id="email_professor">
        </div>
        <div class="campo">
            <label for="telefone_professor">Telefone: </label>
            <input type="text" name="telefone_professor" id="telefone_professor">
        </div>
        <div class="campo">
            <label for="cpf_professor">CPF: </label>
            <input type="text" name="cpf_professor" id="cpf_professor">
        </div>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>