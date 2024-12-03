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
                $("#act-homologacion").addClass("mm-active");
				$("#titulo-cabecera").text("Homologacion ");
				$("#descripcion-cabecera").text("Módulo para la gestión de homologacion del sistema Gungastore");
				$("#titulo-cabecera").append($("<a href='homologacion.php' class='text-success'><i class='fa-duotone fa-solid fa-arrow-left' style=''--fa-secondary-color: #ffffff;'></i> Atras</a>"));

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
    <div class="app-container" style="background: linear-gradient(to bottom, #6a0dad, #87cefa);">
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
                                                <h5 class="card-title">Crear homologacion.</h5>
                                                <form id="#" class="col-md-10 mx-auto" method="post" action="">
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
													
														$result = $mysqli->query("select nombre_local from locales where id='$id_localx'");
													    if($row = $result->fetch_row()){
														$nombre_local=$row[0];
														}
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
											
                                                            $result = $mysqli->query("select * from homologacion where codigo_retail='$correo' ");
                                                            if($row2 = $result->fetch_row())
                                                            {
                                                                ?>
                                                                <script>
                                                                    //Función importada desde sidebar-header.php
                                                                    alertaGeneral("Error al crear la homologacion","El codigo de retail ya pertenece a un producto.","error");
                                                                </script>
                                                                <?PHP
                                                            }
                                                            else
                                                            {
                                                               
                                                                if(!$mysqli -> query("INSERT INTO homologacion(descripcion,codigo_retail) VALUES ('$nombrec','$correo') ") )
                                                                {
                                                                    echo ("Error al crear el usuario. Error: ".$mysqli -> error);
                                                                }
                                                            else
                                                            {
																?>
                                                                 
                                                                    <script>
                                                                        var func= alertaGeneralHref("Homologacion creado","¡Homologacion creado exitosamente!","success","homologacion.php");
                                                                    </script> 
                                                                <?php
                                                            }
                                                            }
                                                            
                                                            
                                                          
                                                        }
                                                          
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="nombrec">Nombre Producto</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="nombrec"
                                                                name="nombrec" placeholder="Escriba el nombre de producto acá..." value="<?=$nombrec;?>"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="correo">Codigo retail</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="email"
                                                                name="correo" placeholder="Escriba el Codigo retail acá..." value="<?=$correo;?>"/>
                                                        </div>
													</div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary form-control-sm" name="btnRegistrar" value="btnRegistrar"> Crear Homologacion </button>
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