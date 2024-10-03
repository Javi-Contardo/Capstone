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