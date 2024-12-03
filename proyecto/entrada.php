<?php 
	include("conexion.php");

	//include("scripts-header.php");
	
	if(trim($_POST['email']) != "" && trim($_POST['password']) != "")
		{
			//Obtener email y contraseña.
			$email =$_POST['email'];
			$password =$_POST['password'];
			//Cifrar la contraseña.
			$password = md5($password);
			$result = $mysqli->query("SELECT * FROM acceso WHERE email='$email'");
			if($row = $result->fetch_array())
				{
					//Comparar la contraseña de BD con la ingresada.
					if($row[4] == $password)
						{
							//Seteamos las cookies para el email y contraseña, las cuales duran 2 horas y 13 minutos.
							setcookie("email",$email,time()+3600);
							setcookie("password",$password,time()+3600);
							//setcookie("email",$email,time()+8000);
							//setcookie("password",$password,time()+8000);
							if($row[6]=='ADMINISTRADOR'||$row[6]=='OWNER'){
							?>
								<script LANGUAGE="javascript">location.href = "dashboard.php";</script>
							<?php
							}
							elseif($row[6]=='VENDEDOR'){
							?>
								<script LANGUAGE="javascript">location.href = "carga_archivos_venta.php";</script>
							<?php
							}
							elseif($row[6]=='BODEGA'){
							?>
								<script LANGUAGE="javascript">location.href = "carga_archivos_stock.php";</script>
							<?php
							}
						}
					else
						{
						?>
						<script>
							
							/*alertaInicioSesion("Error al iniciar sesión","Usuario y/o contraseña incorrectos.","warning");*/
							
						</script>
						<?PHP
						?>
						<script LANGUAGE="javascript">location.href = "login.php?error=3&passs=<? echo $password;?>";</script>
						<?php
						}
				}
			else
				{
					
					?>
						<script LANGUAGE="javascript">location.href = "login.php?error=1&passs=<? echo $password;?>";</script>
					<?PHP
				}
		}
	else
		{
			
			?>
				<script LANGUAGE="javascript">location.href = "login.php?error=2";</script>
			<?PHP
		}
		
?>

<script>


function alertaInicioSesion(titulo, contenido, icono) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: contenido,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        window.location.href = 'login.php'; // Redirecciona al usuario a login.php, independientemente de la acción del usuario
    });
}


</script>