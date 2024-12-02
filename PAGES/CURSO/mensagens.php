<?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        switch ($msg) {
            case 1:
                echo "<p>Curso adicionado com sucesso!</p>";
                break;
            case 2:
                echo "<p>Curso atualizado com sucesso!</p>";
                break;
            case 3:
                echo "<p>Curso excluído com sucesso!</p>";
                break;
        }
    }
?>