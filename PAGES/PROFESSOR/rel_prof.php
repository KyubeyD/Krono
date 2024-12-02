<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id_escola'])) {
    $id_escola = $_GET['id_escola'];
    $sql = "SELECT professor.*, vinculo_prof_esc.* 
        FROM professor
        INNER JOIN vinculo_prof_esc ON professor.id_professor = vinculo_prof_esc.id_professor
        WHERE vinculo_prof_esc.id_escola = :id_escola";
    
    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
        $query->execute();
        $professores = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar professores: " . $e->getMessage();
    }

    $sql = "SELECT escola.nome_escola, escola.email, escola.nome_responsavel, usuario.foto_usuario
    FROM escola
    INNER JOIN usuario ON escola.id_usuario = usuario.id_usuario
    WHERE escola.id_escola = :id_escola";

    try {
        $query = $conexao->prepare($sql);
        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
        $query->execute();
        $escola = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar escola: " . $e->getMessage();
    }

    $html = '
    <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Relatório de cursos</title>
            <link href="https://fonts.cdnfonts.com/css/rubik" rel="stylesheet">
            <link rel="shortcut icon" href="IMG/icon-krono.png" type="image/x-icon">
            <style>
                .logo {
                    width: 300px;
                    margin: 0 auto;
                    margin-bottom: 75px;
                }

                .logo img {
                    width: 100%;
                }

                h1, h2, h3 {
                    font-family: "Rubik", sans-serif;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                    border-radius: 10px; /* Adicionando o border-radius na tabela */
                    overflow: hidden; /* Garante que as bordas arredondadas se mantenham */
                }

                td, th {
                    font-family: "Rubik", sans-serif;
                    padding: 10px;
                    border: 1px solid #ddd;
                    text-align: left;
                }

                th {
                    background-color: #3955A0;
                    color: white;
                    font-weight: bold;
                }

                .headerCrud td {
                    font-weight: bold ;
                }

                .headLista {
                    background: #3955A0;
                    padding: 0 30px;
                    border-radius: 30px;
                    text-align: center;
                }

                .headLista h3 {
                    color: #fff;
                }

                .info {
                    width: 65%;
                    float: left;
                }

                .icon {
                    width: 100px;
                    height: 100px;
                    text-align: right;
                    float: right;
                    margin-top: 12px;
                }

                /* Tabela Zebrada */
                tr:nth-child(odd) {
                    background-color: #e5e8f0;
                }
                tr:nth-child(even) {
                    background-color: #f5f8ff;
                }
            </style>
        </head>
        <body>          
            <figure class="logo">
                <img src="IMG/LOGO KRONO.png" alt="Krono">
            </figure>
            <div class="headLista">
                <h3>Lista de professores</h3>
            </div>
            <div class="headInfo">
                <div class="info">            
                    <h3>Escola: ' . $escola['nome_escola'] . '</h3>
                    <h3>Responsável: ' . $escola['nome_responsavel'] . '</h3>
                    <h3>E-mail: ' . $escola['email'] . '</h3>
                </div>
                <figure class="icon">
                    <img src="IMG/UPLOADS/' . $escola['foto_usuario'] . '" alt="Foto da Escola">
                </figure>
            </div>
            <table>
                <tr class="headerCrud">
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                </tr>';

                // Preencher a tabela com os cursos
                foreach ($professores as $professor) {
                    $html .= '
                        <tr>
                            <td>' . $professor['id_professor'] . '</td>
                            <td>' . $professor['nome_professor'] . '</td>
                            <td>' . $professor['email_professor'] . '</td>
                            <td>' . $professor['telefone_professor'] . '</td>
                            <td>' . $professor['cpf_professor'] . '</td>
                        </tr>';
                }

    $html .= '
                </table>
            </body>
        </html>
    ';
} else {
    $sql = "SELECT * FROM professor";
    
    try {
        $query = $conexao->prepare($sql);
        $query->execute();
        $professores = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao listar cursos: " . $e->getMessage();
    }
}

$mpdf = new \Mpdf\Mpdf();

$mpdf->WriteHTML($html);
$mpdf->Output();
?>
