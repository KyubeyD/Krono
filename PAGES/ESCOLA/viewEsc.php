<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_escola'])) {
        $id_escola = $_GET['id_escola'];

        $sql = "SELECT * FROM escola WHERE id_escola = $id_escola";
        $query = mysqli_query($conexao, $sql);
        $dados = mysqli_fetch_array($query);
        echo "<h1>Dados de " . $dados['nome_escola'] . "</h1>";
        echo "<table border='1'>"; 
        echo "
            <tr>
                <td>ID</td>
                <td>Nome</td>
                <td>Responsável</td>
                <td>CNPJ</td>
                <td>Telefone</td>
                <td>CEP</td>
                <td>Número</td>
                <td>Complemento</td>
                <td>Regra</td>
                <td>Usuário</td>
            </tr>
        ";
        echo "
            <tr>
                <td>" . $dados['id_escola'] . "</td>
                <td>" . $dados['nome_escola'] . "</td>
                <td>" . $dados['nome_responsavel'] . "</td>
                <td>" . $dados['cnpj'] . "</td>
                <td>" . $dados['telefone'] . "</td>
                <td>" . $dados['cep'] . "</td>
                <td>" . $dados['numero'] . "</td>
                <td>" . $dados['complemento'] . "</td>
                <td>" . $dados['id_regra'] . "</td>
                <td>" . $dados['id_usuario'] . "</td>
            </tr>
        ";
        echo "</table>";
    }
?>