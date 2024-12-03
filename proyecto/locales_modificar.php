<?php include("puerta_principal.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$id_local = $_GET['id']; // Asegúrate de recibir el ID del local a modificar

// Consulta para obtener los datos del local a modificar
$result = $mysqli->query("SELECT nombre_local, direccion, estado FROM locales WHERE id='$id_local'");
$local = $result->fetch_assoc(); // Obtenemos los datos del local

?>

<html lang="es">

<head>
    <?php include("scripts-header.php");?>
    <script>
        $(document).ready(function() 
            {
                $("#act-locales").addClass("mm-active");
                $("#titulo-cabecera").text("Locales ");
                $("#descripcion-cabecera").text("Módulo para la gestión de local del sistema Gungastore");
			    $("#titulo-cabecera").append($("<a href='locales.php' class='text-success'><i class='fa-duotone fa-solid fa-arrow-left' style=''--fa-secondary-color: #ffffff;'></i> Atras</a>"));

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
    <div class="app-container"  style="background: linear-gradient(to bottom, #6a0dad, #87cefa);">
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
                                                <h5 class="card-title">Modificar local.</h5>

                                                <!-- Mostrar datos del local a modificar -->
                                                <div class="mb-3">
                                                    <strong>Nombre del local:</strong> <?= strtoupper($local['nombre_local']); ?><br>
                                                    <strong>Dirección:</strong> <?= strtoupper($local['direccion']); ?><br>
                                                    <strong>Estado actual:</strong> <?= strtoupper($local['estado']); ?><br>
                                                </div>

                                                <form id="formRegistrarUsuario" class="col-md-10 mx-auto" method="post" action="">
                                                    <?php
                                                        $btnRegistrar = $_POST['btnRegistrar'];
                                                        $nombre_local = strtoupper($_POST['nombre_local_nuevo']);
                                                        $direccion_local = strtoupper($_POST['direccion_local_nuevo']);
                                                        $estado_local = strtoupper($_POST['estado_local']);

                                                        if(isset($btnRegistrar))
                                                        {
                                                            // Validar si existe el email registrado en la BD
                                                            $result = $mysqli->query("SELECT id, nombre_local, direccion, estado FROM locales WHERE nombre_local='$nombre_local' AND direccion='$direccion_local' AND id != '$id_local'");
                                                            if($row2 = $result->fetch_row())
                                                            {
                                                                ?>
                                                                <script>
                                                                    // Función importada desde sidebar-header.php
                                                                    alertaGeneral("Error al modificar el local","El local ya existe registrado con ese nombre y dirección.","error");
                                                                </script>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                if(!$mysqli->query("UPDATE locales SET nombre_local='$nombre_local', direccion='$direccion_local', estado='$estado_local' WHERE id='$id_local'"))
                                                                {
                                                                    echo ("Error al modificar el local. Error: ".$mysqli->error);
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <script>
                                                                        var func = alertaGeneralHref("Local modificado","¡Local modificado exitosamente!","success","locales.php");
                                                                    </script> 
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="nombre_local_nuevo">Nombre local</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="nombre_local_nuevo"
                                                                name="nombre_local_nuevo" placeholder="Escriba su nombre del nuevo local acá..." value="<?=$local['nombre_local'];?>"/>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="direccion">Dirección</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="direccion_local_nuevo"
                                                                name="direccion_local_nuevo" placeholder="Escriba la dirección acá..." value="<?=$local['direccion'];?>"/>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="estado_local">Estado</label>
                                                        <select class="mb-2 form-control-sm form-control" id="estado_local" name="estado_local">
                                                            <option value="" disabled>Seleccione un estado</option>
                                                            <option value="ACTIVO" <?= $local['estado'] == 'ACTIVO' ? 'selected' : ''; ?>>Activo</option>
                                                            <option value="SUSPENDIDO" <?= $local['estado'] == 'SUSPENDIDO' ? 'selected' : ''; ?>>Suspendido</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary form-control-sm" name="btnRegistrar" value="btnRegistrar"> Modificar local </button>
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
