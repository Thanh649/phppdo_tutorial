<?php
	require_once 'dbconfig.php';

	try {
		$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
		$pdo->exec("set names utf8");
		session_start();
		echo "Connected to $dbname at $host successfully.";
		echo "<br>";
	} catch (PDOException $pe) {
		die("Could not connect to the database $dbname :" . $pe->getMessage());
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<?php
		
			$user = $pass = $vpass = $email ="";
			if(isset($_POST['nRegister']))
			{
				$pass = $_POST['password'];
				$vpass = $_POST['verifyPassword'];
				if($_POST['user'] == NULL)
				{
					header("location:index.php?page=register");
					setcookie("error_user", "Please enter your username!", time()+1, "/","", 0);
				}
				else
				{
					$sql = "SELECT username FROM login_account WHERE username = :user";
					$q = $pdo->prepare($sql);
					$q->execute(array(":user" => $_POST['user']));
					$q->setFetchMode(PDO::FETCH_ASSOC);
					$r = $q->fetch();
					$q->closeCursor();
					if( $r == NULL)
					{
						$user = $_POST['user'];
					}
					else
					{
						header("location:index.php?page=register");
						setcookie("error_duplicate_user", "username already in use !", time()+1, "/","", 0);
					}
				}
				if($_POST['password'] == NULL)
				{
					header("location:index.php?page=register");
					setcookie("error_pass", "Please enter your password!", time()+1, "/","", 0);
				}
				else
				{
					$pass = $_POST['password'];
				}
				if($_POST['email'] == NULL)
				{
					header("location:index.php?page=register");
					setcookie("error_email", "Please enter your email!", time()+1, "/","", 0);
				}
				else
				{
					$sql = "SELECT email FROM login_account WHERE email = :email";
					$q = $pdo->prepare($sql);
					$q->execute(array(":email" => $_POST['email']));
					$q->setFetchMode(PDO::FETCH_ASSOC);
					$r = $q->fetch();
					$q->closeCursor();
					if( $r == NULL)
					{
						$email = $_POST['email'];
					}
					else
					{
						header("location:index.php?page=register");
						setcookie("error_duplicate_email", "email already in use !", time()+1, "/","", 0);
					}
				}
				if($pass != $vpass)
				{
					header("location:index.php?page=register");
					setcookie("error_verify", "Verify password wrong!", time()+1, "/","", 0);
				}
				else
				{
					$pass = $_POST['password'];
					$vpass = $_POST['verifyPassword'];
				}
			}
			
			if($email && $user && $pass &&($pass == $vpass))
			{
				$sql = "INSERT INTO login_account(username, password, email)
						VALUES ('$user', '$pass', '$email')";
				$pdo->exec($sql);
				header("location:index.php?page=register");
				setcookie("success", "Register success!", time()+1, "/","", 0);
			}
		?>
		<!-- 'end thực hiện kiểm tra dữ liệu người dùng đăng ký' -->
		
		<?php
			$u = $p = "";
			if(isset($_POST['nLogin']))
			{
				if($_POST['login_username'] == NULL)
				{
					echo "Please enter your username<br />";
				}
				else
				{
					$u = $_POST['login_username'];
				}
				if($_POST['login_password'] == NULL)
				{
					echo "Please enter your password<br />";
				}
				else
				{
					$p = $_POST['login_password'];
				}
			}
			
			if($u && $p)
			{
				$temp = [':login_username'=>$u, ':login_password'=>$p];
				$sql = 'SELECT * FROM login_account WHERE username = :login_username and password= :login_password';
				$q = $pdo->prepare($sql);
				$q->execute($temp);
				$q->setFetchMode(PDO::FETCH_ASSOC);
				$r = $q->fetch();
				$q->closeCursor();
				if( $r == NULL)
				{
					header("location:index.php");
					echo "Username or password is not correct, please try again";
				}
				else
				{
					header("location:index.php");
					$_SESSION["loged"] = true;
					$_SESSION['userid'] = $r['id'];
					$_SESSION['level'] = $r['level'];
				}
			}
		?>
		
			<div>
				<a href='index.php?page=register' >register </a>
				<a href='index.php' > home </a>
				<?php if(isset($_SESSION["loged"])) echo "<a href='index.php?act=logout' >logout</a>"; ?>
			</div>
			
			<div>
				<!-- 'start nếu xảy ra lỗi thì hiện thông báo:' -->
				<?php
				if(isset($_COOKIE["error_user"])){
				?>
				<div 
					<strong>'Error!'</strong> <?php echo $_COOKIE["error_user"]; ?>
				</div>
				<?php } ?>
				
				<?php
				if(isset($_COOKIE["error_pass"])){
				?>
				<div 
					<strong>'Error!'</strong> <?php echo $_COOKIE["error_pass"]; ?>
				</div>
				<?php } ?>
				
				<?php
				if(isset($_COOKIE["error_email"])){
				?>
				<div 
					<strong>'Error!'</strong> <?php echo $_COOKIE["error_email"]; ?>
				</div>
				<?php } ?>
				
				<?php
				if(isset($_COOKIE["error_verify"])){
				?>
				<div 
					<strong>'Error!'</strong> <?php echo $_COOKIE["error_verify"]; ?>
				</div>
				<?php } ?>
				
				<?php
				if(isset($_COOKIE["error_duplicate_user"])){
				?>
				<div 
					<strong>'Error!'</strong> <?php echo $_COOKIE["error_duplicate_user"]; ?>
				</div>
				<?php } ?>
				
				<?php
				if(isset($_COOKIE["error_duplicate_email"])){
				?>
				<div 
					<strong>'Error!'</strong> <?php echo $_COOKIE["error_duplicate_email"]; ?>
				</div>
				<?php } ?>
				<!-- 'end nếu xảy ra lỗi thì hiện thông báo:' -->
	 
	 
				<!-- 'start nếu thành công thì hiện thông báo:' -->
				<?php
					if(isset($_COOKIE["success"])){
				?>
				<div >
					<strong>'Congratulation!'</strong> <?php echo $_COOKIE["success"]; ?>
				</div>
				<?php } ?>
				<!-- 'end nếu thành công thì hiện thông báo:' -->
	 
				<?php
				if((isset($_GET["page"])&&$_GET["page"]=="register") || isset($_POST['nRegister']) )
					include "register.php";
				if(!isset($_GET["page"]))
				{
					if(isset($_SESSION["loged"]))
						include "admin.php";
					else
						include "login.php";
				}
				if((isset($_GET["act"])))
					include "login.php";
				?>
			</div>
	</body>
</html>