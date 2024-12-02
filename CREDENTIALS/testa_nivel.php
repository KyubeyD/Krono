<?php
if(($_SESSION['nivel_usuario'] < $nivel_necessario)) {
	session_destroy();
	echo "Acesso NEGADO!!!<br><br>";
	header("Location: index.php?page=home"); 
	exit;
}
?>