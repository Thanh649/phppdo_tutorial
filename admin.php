<?php
	if(!isset($_SESSION["loged"])){
		header("location:index.php");
		setcookie("error", "login fail!", time()+1, "/","", 0);
	}
	else
		echo "login success";
 
?>