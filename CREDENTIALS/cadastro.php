<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krono</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/cadastro.css">
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
                <form action="registrar_esc.php" class="form-cadastro" id="form-escola" method="post">
                    <h1>Cadastro de escola</h1>
                    <div class="progress-bar" id="progress-bar-escola">
                        <div class="bar" id="bar-escola"></div>
                        <div class="progress" id="progress-escola"></div>
                        <span class="circle" id="circle1-escola"><i class='bx bxs-user-detail'></i></span>
                        <span class="circle" id="circle2-escola"><i class='bx bxs-map-pin'></i></span>
                        <span class="circle" id="circle3-escola"><i class='bx bxs-phone' ></i></span>
                        <span class="circle" id="circle4-escola"><i class='bx bxs-user' ></i></span>
                    </div>

                    <div class="campos" id="page-1">
                        <div class="campo">
                            <input type="text" name="nome_escola" id="nome_escola" placeholder="Nome da escola">
                        </div>
                        <div class="campo">
                            <input type="text" name="nome_responsavel" id="nome_responsavel" placeholder="Nome do responsável">
                        </div>
                        <div class="dois-campos">
                            <div class="campo">
                                <input type="text" name="cnpj" id="cnpj" placeholder="CNPJ">
                            </div>
                            <div class="campo">
                                <input type="email" name="email" id="email" placeholder="E-mail">
                            </div>
                        </div>
                    </div>

                    <div class="campos" id="page-2" style="display: none;">
                        <input type="hidden" name="uf" id="uf">
                        <input type="hidden" name="estado" id="estado">
                        <div class="dois-campos">
                            <div class="campo">
                                <input type="text" name="cep" id="cep" placeholder="CEP">
                            </div>
                            <div class="campo">
                                <input type="text" name="bairro" id="bairro" placeholder="Bairro">
                            </div>
                        </div>
                        <div class="campo">
                            <input type="text" name="cidade" id="cidade" placeholder="Cidade">
                        </div>
                        <div class="campo">
                            <input type="text" name="logradouro" id="logradouro" placeholder="Endereço">
                        </div>
                    </div>

                    <div class="campos" id="page-3" style="display: none;">
                        <div class="dois-campos">
                            <div class="campo">
                                <input type="text" name="numero" id="numero" placeholder="Número">
                            </div>
                            <div class="campo">
                                <input type="text" name="complemento" id="complemento" placeholder="Complemento">
                            </div>
                        </div>
                        <div class="campo">
                            <input type="text" name="telefone" id="telefone1" placeholder="Telefone 1">
                        </div>
                        <div class="campo">
                            <input type="text" name="telefone" id="telefone2" placeholder="Telefone 2 (Opcional)">
                        </div>
                    </div>

                    <div class="campos" id="page-4" style="display: none;">
                        <div class="campo">
                            <input type="text" name="usuario_escola" id="usuario_escola" placeholder="Nome de usuário">
                        </div>
                        <div class="campo">
                            <input type="password" name="senha_escola" id="senha_escola" placeholder="Senha">
                        </div>
                        <div class="campo">
                            <input type="password" name="confirmar_senha_escola" id="confirmar_senha_escola" placeholder="Confirmar senha">
                        </div>
                    </div>

                    <div class="page-button">
                        <div class="btn-page disabled" id="btn-voltar">Voltar</div>
                        <div class="btn-page" id="btn-proximo">Próximo</div>       
                        <input type="submit" class="btn-page" id="btn-cadastro" value="Cadastrar" style="display: none;">              
                    </div>
                </form>
            </div>
            <div class="quadro">
                <div class="conteudo">
                    <i class='bx bxs-school'></i>
                    <h1>Escolas</h1>
                    <p>Quer organizar as grades horárias de sua escola ?</p>
                    <div class="btn-cadastro" id="btn-change">Cadastre-se</div>
                    <a href="login.php" class="btn-cadastro">Já possui conta ?</a>
                </div>
            </div>
            <div class="cadastro">
                <form action="registrar_prof.php" class="form-cadastro" method="post">
                    <h1>Cadastro de professor</h1>
                    <div class="progress-bar" id="progress-bar-professor">
                        <div class="bar" id="bar-professor"></div>
                        <div class="progress" id="progress-professor"></div>
                        <span class="circle" id="circle1-professor"><i class='bx bxs-user-detail'></i></span>
                        <span class="circle" id="circle2-professor"><i class='bx bxs-user' ></i></span>
                    </div>

                    <div class="campos" id="page-1-professor">
                        <div class="campo">
                            <input type="text" name="nome_professor" id="nome_professor" placeholder="Nome completo">
                        </div>
                        <div class="campo">
                            <input type="email" name="email_professor" id="email_professor" placeholder="E-mail">
                        </div>
                        <div class="dois-campos">
                            <div class="campo">
                                <input type="text" name="cpf_professor" id="cpf" placeholder="CPF">
                            </div>
                            <div class="campo">
                                <input type="text" name="telefone_professor" id="telefone" placeholder="Telefone">
                            </div>
                        </div>
                    </div>

                    <div class="campos" id="page-2-professor" style="display: none;">
                        <div class="campo">
                            <input type="text" name="usuario_professor" id="usuario_professor" placeholder="Nome de usuário">
                        </div>
                        <div class="campo">
                            <input type="password" name="senha_professor" id="senha_professor" placeholder="Senha">
                        </div>
                        <div class="campo">
                            <input type="password" name="confirmar_senha_professor" id="confirmar_senha_professor" placeholder="Confirmar senha">
                        </div>
                    </div>

                    <div class="page-button">
                        <div class="btn-page disabled" id="btn-voltar-professor" type="button">Voltar</div>
                        <div class="btn-page" id="btn-proximo-professor">Próximo</div>
                        <input type="submit" class="btn-page" id="btn-cadastro-prof" value="Cadastrar" style="display: none;">
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../JS/cadastro.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../JS/mask.js"></script>
    <script src="../JS/verifycep.js"></script>
</body>
</html>
