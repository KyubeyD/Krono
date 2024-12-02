<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_usuario'])) {
        $id_usuario = $_GET['id_usuario'];

        $sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
        $query = mysqli_query($conexao, $sql);
        $dados = mysqli_fetch_array($query);
        echo "<h1>Dados de " . $dados['nome_usuario'] . "</h1>";
        echo "<table border='1'>"; 
        echo "
            <tr>
                <td>ID</td>
                <td>Nome</td>
                <td>Senha</td>
                <td>Data de Cadastro</td>
                <td>Status</td>
                <td>Nível</td>
                <td>Foto</td>
            </tr>
        ";
        echo "
            <tr>
                <td>" . $dados['id_usuario'] . "</td>
                <td>" . $dados['nome_usuario'] . "</td>
                <td>" . $dados['senha_usuario'] . "</td>
                <td>" . $dados['dt_cadastro_usuario'] . "</td>
                <td>" . $dados['status_usuario'] . "</td>
                <td>" . $dados['nivel_usuario'] . "</td>
                <td>" . $dados['foto_usuario'] . "</td>
            </tr>
        ";
        echo "</table>";
    }
?>