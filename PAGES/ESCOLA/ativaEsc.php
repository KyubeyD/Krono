<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_GET['id_escola'])) {
        $id_escola = $_GET['id_escola'];

        $sqlEsc = "SELECT * FROM escola WHERE id_escola = $id_escola";
        $queryEsc = mysqli_query($conexao, $sqlEsc);
        $dadosEsc = mysqli_fetch_assoc($queryEsc);

        $sqlUsu = "UPDATE usuario SET status_usuario = '1' WHERE id_usuario = " . $dadosEsc['id_usuario'] . ";";

        $queryUsu = mysqli_query($conexao, $sqlUsu);
        
        if ($queryUsu) {
            header('location: lista_esc.php?msg=4');    
        }
    }
?>