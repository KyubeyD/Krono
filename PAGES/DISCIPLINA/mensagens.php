<?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        switch ($msg) {
            case 1:
                echo "<p>Disciplina adicionada com sucesso!</p>";
                break;
            case 2:
                echo "<p>Disciplina atualizada com sucesso!</p>";
                break;
            case 3:
                echo "<p>Disciplina excluída com sucesso!</p>";
                break;
        }
    }
?>