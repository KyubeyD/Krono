<?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        switch ($msg) {
            case 1:
                echo "<p>Escola adicionada com sucesso!</p>";
                break;
            case 2:
                echo "<p>Escola atualizada com sucesso!</p>";
                break;
            case 3:
                echo "<p>Escola bloqueada com sucesso!</p>";
                break;
            case 4:
                echo "<p>Escola ativada com sucesso!</p>";
                break;
        }
    }
?>