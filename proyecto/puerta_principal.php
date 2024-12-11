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
					$id_local = "$row[7]";	
					$nombre_fantasia = "$row[8]";	
					$img_avatar = "$row[9]";
					$result100=$mysqli->query("SELECT * FROM locales WHERE id='$id_local'");
					if($row100 = $result100->fetch_array())
						{
						$nombre_local = "$row100[1]";
						}
				if ($labor == "ADMINISTRADOR" && (basename($_SERVER['PHP_SELF']) == "homologacion.php"||basename($_SERVER['PHP_SELF']) == "homologacion_nuevo.php"||basename($_SERVER['PHP_SELF']) == "homologacion_modificar.php"||basename($_SERVER['PHP_SELF']) == "locales.php"||basename($_SERVER['PHP_SELF']) == "locales_modificar.php"||basename($_SERVER['PHP_SELF']) == "locales_nuevo.php")) {
					?>
					<script language="javascript">location.href = "dashboard.php";</script>
					<?php
					exit();
				}
				if ($labor == "BODEGA" && (basename($_SERVER['PHP_SELF']) == "homologacion.php"||basename($_SERVER['PHP_SELF']) == "homologacion_nuevo.php"||basename($_SERVER['PHP_SELF']) == "homologacion_modificar.php"||basename($_SERVER['PHP_SELF']) == "locales.php"||basename($_SERVER['PHP_SELF']) == "locales_modificar.php"||basename($_SERVER['PHP_SELF']) == "locales_nuevo.php"||basename($_SERVER['PHP_SELF']) == "dashboard.php"||basename($_SERVER['PHP_SELF']) == "carga_archivos_venta.php")) {
					?>
					<script language="javascript">location.href = "carga_archivos_stock.php";</script>
					<?php
					exit(); 
				}
				if ($labor == "VENTA" && (basename($_SERVER['PHP_SELF']) == "homologacion.php"||basename($_SERVER['PHP_SELF']) == "homologacion_nuevo.php"||basename($_SERVER['PHP_SELF']) == "homologacion_modificar.php"||basename($_SERVER['PHP_SELF']) == "locales.php"||basename($_SERVER['PHP_SELF']) == "locales_modificar.php"||basename($_SERVER['PHP_SELF']) == "locales_nuevo.php"||basename($_SERVER['PHP_SELF']) == "dashboard.php"||basename($_SERVER['PHP_SELF']) == "carga_archivos_stock.php")) {
					?>
					<script language="javascript">location.href = "carga_archivos_venta.php";</script>
					<?php
					exit(); 
				}
				
				
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