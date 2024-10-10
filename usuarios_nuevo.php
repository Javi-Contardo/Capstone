<?php include("puerta_principal.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
?>

<html lang="es">

<head>
    <?php include("scripts-header.php");?>
    <script>
        $(document).ready(function() 
            {
                $("#act-usuarios").addClass("mm-active");
				$("#titulo-cabecera").text("Usuarios");
				$("#descripcion-cabecera").text("Módulo para la gestión de usuarios del sistema Gungastore");

                //Evitar el envío del formulario al recargar la página.
                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                }
                if($("#labor").val()!='CLIENTE'){
                    $("#lista_clientes").addClass("d-none");
                }
            });
         
        function tipo_usuario()
        {
            if($("#labor").val()=='ANALISTA'||$("#labor").val()=='LOGISTICA'||$("#labor").val()=='COMERCIAL'){
                $("#lista_clientes").removeClass("d-none");
            }else{
                $("#lista_clientes").addClass("d-none");
            }
        }
	</script>
    <title>Gungastore</title>
</head>

<body>
    <div class="app-container app-theme-gray">
        <div class="app-main">
            <?php include("sidebar-header.php");?>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__wrapper">
                    <div class="app-inner-layout__content pt-1">
                        <div class="tab-content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h5 class="card-title">Crear usuario.</h5>
                                                <form id="formRegistrarUsuario" class="col-md-10 mx-auto" method="post" action="">
                                                    <?php
                                                       
                                                        $btnRegistrar= $_POST['btnRegistrar'];
                                                        $nombrec= strtoupper($_POST['nombrec']);
                                                        $correo= strtolower($_POST['correo']);
                                                        $laborx= $_POST['labor'];
                                                        $id_localx= $_POST['local'];
                                                        $clave= $_POST['clave'];
                                                        $confirmar_clave= $_POST['clave'];
                                                        $id_clientex= $_POST['id_cliente'];
                                                        $clavecifrada= md5($clave);
											             /*if ($id_user!='')
                                                        {
                                                            $result = $mysqli->query("select id_acceso,email,nombre,clavevisual,nombre_labor,id_cliente,nombre_fantasia from acceso where id_acceso='$id_user'");
                                                            if($row = $result->fetch_row())
                                                                {
                                                                    if ($nombrec==''){$nombrec=$row[2];}
                                                                    if ($correo==''){$correo=$row[1];}
                                                                    if ($clave==''){$clave=$row[3];}
                                                                    if ($confirmar_clave==''){$confirmar_clave=$row[3];}   
                                                                    if ($laborx==''){$laborx=$row[4];}
                                                                    if ($id_clientex==''){$id_clientex=$row[5];}                                                   
                                                                }
                                                        }*/
                                                        if(isset($btnRegistrar))
                                                        {
                                                            
                                                            //Validar si existe el email registrado en la BD
											
                                                            $result = $mysqli->query("select id_acceso,email,nombre,clavevisual,nombre_labor from acceso where email='$correo' ");
                                                            if($row2 = $result->fetch_row())
                                                            {
                                                                ?>
                                                                <script>
                                                                    //Función importada desde sidebar-header.php
                                                                    alertaGeneral("Error al crear usuario","El correo del usuario ya posee una cuenta asociada.","error");
                                                                </script>
                                                                <?PHP
                                                            }
                                                            else
                                                            {
                                                               
                                                                if(!$mysqli -> query("INSERT INTO acceso(email,nombre,clavevisual,clavecifrada,estado,nombre_labor,id_local) VALUES ('$correo','$nombrec','$clave','$clavecifrada','ACTIVO','$laborx','$id_localx') ") )
                                                                {
                                                                    echo ("Error al crear el usuario. Error: ".$mysqli -> error);
                                                                }
                                                            else
                                                            {
																?>
                                                                 
                                                                    <script>
                                                                        var func= alertaGeneralHref("Usuario creado","¡Usuario creado exitosamente!","success","usuarios.php");
                                                                    </script> 
                                                                <?php
                                                            }
                                                            }
                                                            
                                                            
                                                          
                                                        }
                                                          
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="nombrec">Nombre Completo</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="nombrec"
                                                                name="nombrec" placeholder="Escriba su nombre completo acá..." value="<?=$nombrec;?>"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="correo">Correo electrónico</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="email"
                                                                name="correo" placeholder="Ejemplo: hugot@gmail.com..." value="<?=$correo;?>"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="labor">Labor</label>
                                                        <select class="mb-2 form-control-sm form-control" id="labor" name="labor" onChange="tipo_usuario()">
                                                            <option value="" disabled>Seleccione una labor</option>
                                                        <? 
																$query = $mysqli -> query ("SELECT nombre_labor FROM labor where nombre_labor != 'ADMINISTRADOR' ");
																while ($valores = mysqli_fetch_array($query)) {
																	//echo '<option name="labor" value="'.$valores[nom_labor].'">'.$valores[nom_labor].'</option>';
																	?>
																		<option name="opLabor" value="<?=$valores['nombre_labor']?>" <? if($laborx==$valores['nombre_labor'])  {?>selected<? }?>   ><?=$valores['nombre_labor']?></option>
																	<?
																}
													    ?>
                                                        </select>
                                                    </div>
													<div class="form-group">
                                                        <label for="local">Local</label>
                                                        <select class="mb-2 form-control-sm form-control" id="local" name="local">
                                                            <option value="" disabled>Seleccione un local</option>
                                                        <? 
																$query = $mysqli -> query ("SELECT id, nombre_local FROM locales");
																while ($valores = mysqli_fetch_array($query)) {
																	?>
																		<option name="opLocal" value="<?=$valores['id']?>" <? if($localx==$valores['nombre_local'])  {?>selected<? }?>   ><?=$valores['nombre_local']?></option>
																	<?
																}
													    ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="clave">Contraseña</label>
                                                        <input type="password" class="mb-2 form-control-sm form-control" id="clave"
                                                            name="clave" placeholder="Escriba su contraseña acá..." value="<?=$clave;?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="confirm_password">Repetir contraseña</label>
                                                        <div>
                                                            <input type="password" class="mb-2 form-control-sm form-control"
                                                                id="confirmar_clave" name="confirmar_clave"
                                                                placeholder="Repita su contraseña acá..." value="<?=$confirmar_clave;?>"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary form-control-sm" name="btnRegistrar" value="btnRegistrar"> Crear usuario </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!--DRAWER START-->
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <!-- Incluir scripts. -->
    <?php include("scripts.php");?>

</body>

</html>