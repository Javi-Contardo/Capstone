<html lang="es">

<head>
    <?php include("scripts-header.php");?>
	<?
	
	$error=$_GET['error'];
	$passs=$_GET['passs'];
	?>
<input type="hidden" id="pruebapass" value="<?php echo $passs; ?>">

	<?
	
	if($error==1){
		?>
		
		<script>
							
		alertaGeneral("CORREO INCORRECTA","warning");

		</script>
	
		<?
	}else if($error==2){
		?>
		
		<script>
							
		alertaGeneral("Error al iniciar sesión","Debe especificar un Correo y una contraseña.","warning");

		</script>
	
		<?
	}
	else if($error==3){
		?>
		
		<script>
		var contra = document.getElementById("pruebapass").value;
		alertaGeneral("CONTRASEÑA INCORRECTA: " + contra,"warning");

		</script>
	
		<?
	}
	
	?>
    <title>Negocio el chino Gestion</title>
	<link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>

<body>
    <div class="app-container" style="background: linear-gradient(to bottom, #6a0dad, #87cefa);">
        <div class="app-container">
            <div class="h-100 bg-blue-plate bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <div class="app-inverse mx-auto mb-3"></div>
                        <div class="modal-dialog w-100 mx-auto">
                            <div class="modal-content">
                                <!-- Formulario de inicio de sesión. -->
                                <form class="" action="entrada.php" method="post">
                                    <div class="modal-body">
                                        <div class="h5 modal-title text-center text-dark">
                                            <h4 class="mt-2">
												<img src="dev.negocioelchinoges.cl/assets/images/logo-lol.png" width="200" height="200" alt=""/>
                                                <strong><div>Inicio de sesión</div></strong>
											</h4>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="email"
                                                        id="exampleEmail" placeholder="Usuario/correo electrónico..."
                                                        type="email" class="form-control"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="password"
                                                        id="examplePassword" placeholder="Contraseña..." type="password"
                                                        class="form-control"></div>
                                            </div>
                                        </div>
                                        <div class="position-relative form-check"><input name="check" id="exampleCheck"
                                                type="checkbox" class="form-check-input"><label for="exampleCheck"
                                                class="form-check-label">Keep me logged in</label></div>
                                        <div class="divider"></div>
                                        <!--<h6 class="mb-0">¿No tienes una cuenta? <a href="registro_usuarios.php"
                                                class="text-primary"> Regístrate acá</a></h6>-->
                                    </div>
                                    <div class="justify-content-center clearfix"
                                        style=" padding: 16px; allign-items: center; display: flex;">
                                        <div class="float-left"><a href="recuperar_contrasena.php"
                                                class="btn-lg btn btn-link">Recuperar contraseña</a></div>
                                        <div class="float-right">
                                            <button name="inicio" class="btn btn-primary btn-lg" type="submit">Iniciar sesión</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="text-center text-white opacity-8 mt-3">Copyright © Gungadevs 2024</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Incluir scripts. -->
    <?php include("scripts.php");?>
</body>

</html>