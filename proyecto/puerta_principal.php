<?php
	if(isset($_COOKIE["email"]) && isset($_COOKIE["password"]))
		{
			include("conexion.php");
			$password=$_COOKIE["password"];
			$email=$_COOKIE["email"];
			
			$result=$mysqli->query("SELECT * FROM acceso WHERE email='$email' AND clavecifrada='$password'");
			if($row = $result->fetch_array())
				{
					//Seteamos las cookies para el email y contraseña, las cuales duran 2 horas y 13 minutos.
					setcookie("email",$_COOKIE["email"],time()+3600);
					setcookie("password",$_COOKIE["password"],time()+3600);
					//setcookie("email",$_COOKIE["email"],time()+8000);
					//setcookie("password",$_COOKIE["password"],time()+8000);
					//Establecer variables para llenar los campos.
					$id_usuario="$row[0]";
					$id_email="$row[0]";
					$email = "$row[1]";
					$nombre = "$row[2]";
					$nombre_usuario = "$row[2]";
					$labor = "$row[6]";		
<<<<<<< HEAD
					$id_local = "$row[7]";	
					$nombre_fantasia = "$row[8]";	
					$img_avatar = "$row[9]";
					$result100=$mysqli->query("SELECT * FROM locales WHERE id='$id_local'");
					if($row100 = $result100->fetch_array())
						{
						$nombre_local = "$row100[1]";
						}
=======
					$id_local = "$row[7]";		
>>>>>>> 2f8a61adda3ae6fe02dddb3a243eca03f8eb2ceb
				}
			else
				{
					setcookie("email","x",time()-10000);
					setcookie("password","x",time()-10000);
					?>
						<script language="javascript">location.href = "login.php";</script>
					<?
				}
		}
	else
		{
			?>
				<script language="javascript">location.href = "login.php";</script>
			<?
		}
?>