<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <main>
        <div class="header" id="header">
            <nav class="content-header">             
                <figure class="logo">
                    <img src="../IMG/logo-krono-branca.png" alt="Krono">
                </figure>   

                <nav class="navegacao">
                    <ul>
                        <li><a href="../index.php?page=home">Home</a></li>
                        <li><a href="../index.php?page=planos">Planos</a></li>
                        <li><a href="../index.php?page=login">Login</a></li>
                        <li><a href="../index.php?page=cadastro">Cadastre-se</a></li>
                    </ul>
                </nav>
            </nav>
        </div>

        <div class="container">
            <div class="cadastro">
                <form action="validacao.php" class="form-cadastro" method="post">
                    <h1>Seja bem-vindo(a)!</h1>
                    <div class="campos">
                        <h2>Faça seu login</h2>
                        <div class="campo">
                            <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Nome do usuário">
                        </div>
                        <div class="campo">
                            <input type="password" name="senha_usuario" id="senha_usuario" placeholder="Senha">
                        </div>
                        <input type="submit" value="Entrar" class="btn-page logar">
                    </div>
                </form>
            </div>
            <div class="quadro">
                <div class="conteudo">
                    <h1>Não possui conta ?</h1>
                    <p>Organize seu tempo com o Krono! Cadastre-se e mantenha seus horários em dia!</p>
                    <a href="../index.php?page=cadastro" class="btn-cadastro" id="btn-change">Cadastre-se</a>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="mask.js"></script>
    <script src="verifycep.js"></script>
</body>
</html>
