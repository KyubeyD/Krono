<?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        switch ($msg) {
            case 1:
                echo "<p>Profesosr(a) adicionado(a) com sucesso!</p>";
                break;
            case 2:
                echo "<p>Professor(a) atualizado(a) com sucesso!</p>";
                break;
            case 3:
                echo "<p>Professor(a) bloqueado(a) com sucesso!</p>";
                break;
            case 4:
                echo "<p>Professor(a) ativado(a) com sucesso!</p>";
                break;
        }
    }
?>