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

        $id_turma = $_GET['id_turma'];
        $sqlTurma = "SELECT * FROM turma WHERE id_turma = " . $id_turma . ";";
        $queryTurma = mysqli_query($conexao, $sqlTurma);
        $dados = mysqli_fetch_array($queryTurma);
    ?>
    <h1>Edição de <?php echo $dados['nome_turma']; ?></h1>
    <form action="atualizar_turma.php" method="post">
        <input type="hidden" name="id_turma" value="<?php echo $dados['id_turma'] ?>">
        <div class="campo">
            <label for="nome_turma">Nome: </label>
            <input type="text" name="nome_turma" id="nome_turma" value="<?php echo $dados['nome_turma'] ?>">
        </div>
        <div class="campo">
            <label for="serie_turma">Série: </label>
            <input type="text" name="serie_turma" id="serie_turma" value="<?php echo $dados['serie_turma'] ?>">
        </div>
        <div class="campo">
            <label for="id_curso">Curso: </label>
            <select name="id_curso" id="id_curso">
                <?php 
                    $sqlCurso = "SELECT * FROM curso";
                    $queryCurso = mysqli_query($conexao, $sqlCurso);
                    
                    while ($dados = mysqli_fetch_array($queryCurso)) {
                        echo "<option value='" . $dados['id_curso'] . "'>" . $dados['nome_curso'] . "</option>";
                    }
                ?>
            </select>
        </div>
        <input type="submit" value="Editar">
    </form>
</body>
</html>