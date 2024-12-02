<?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        switch ($msg) {
            case 1:
                echo "<div class='mensagem sucess' id='msg'><span>Usuário adicionado com sucesso!</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
            case 2:
                echo "<div class='mensagem sucess' id='msg'><span>Usuário atualizado com sucesso!</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
            case 3:
                echo "<div class='mensagem sucess' id='msg'><span>Usuário bloqueado com sucesso!</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
            case 4:
                echo "<div class='mensagem sucess' id='msg'><span>Usuário ativado com sucesso!</span><i class='bx bx-x' id='x-msg'></i></div>";
                break;
        }
    }
?>