<?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        switch ($msg) {
            case 1:
                echo "<div class='mensagem sucess' id='msg'><span>Turma adicionada com sucesso</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
            case 2:
                echo "<div class='mensagem sucess' id='msg'><span>Turma atualizada com sucesso!</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
            case 3:
                echo "<div class='mensagem sucess' id='msg'><span>Turma excluída com sucesso!</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
        }
    }
?>