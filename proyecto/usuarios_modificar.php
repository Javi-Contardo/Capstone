<?php
include("puerta_principal.php");
?>
<html lang="es">

<head>
    <?php include("scripts-header.php"); ?>
    <script>
        $(document).ready(function () {
            $("#act-usuarios").addClass("mm-active");
            $("#titulo-cabecera").text("Usuarios ");
            $("#descripcion-cabecera").text("Módulo para la gestión de usuarios del sistema Gungastore");
            $("#titulo-cabecera").append($("<a href='usuarios.php' class='text-success'><i class='fa-duotone fa-solid fa-arrow-left' style=''--fa-secondary-color: #ffffff;'></i> Atras</a>"));

            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            tipo_usuario();
        });

        function tipo_usuario() {
            if ($("#labor").val() == 'ANALISTA' || $("#labor").val() == 'LOGISTICA' || $("#labor").val() == 'COMERCIAL') {
                $("#lista_clientes").removeClass("d-none");
            } else {
                $("#lista_clientes").addClass("d-none");
            }
        }
    </script>
    <title>Gungastore</title>
</head>

<body>
    <div class="app-container" style="background: linear-gradient(to bottom, #6a0dad, #87cefa);">
        <div class="app-main">
            <?php include("sidebar-header.php"); ?>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__wrapper">
                    <div class="app-inner-layout__content pt-1">
                        <div class="tab-content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h5 class="card-title">Modificar usuario.</h5>
                                                <form id="formRegistrarUsuario" class="col-md-10 mx-auto" method="post" action="">
                                                    <?php
                                                    $id_user = $_GET['id_usuario'];

                                                    $btnRegistrar = $_POST['btnRegistrar'];
                                                    $nombrec1 = isset($_POST['nombrec']) ? strtoupper($_POST['nombrec']) : '';
                                                    $correo1 = isset($_POST['correo']) ? strtolower($_POST['correo']) : '';
                                                    $laborx = isset($_POST['labor']) ? $_POST['labor'] : '';
                                                    $local = isset($_POST['local']) ? $_POST['local'] : '';
                                                    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
                                                    $confirmar_clave = isset($_POST['confirmar_clave']) ? $_POST['confirmar_clave'] : '';

                                                    if ($id_user != '') {
                                                        $result = $mysqli->query("SELECT id_acceso, email, nombre, clavevisual, nombre_labor, id_local, estado FROM acceso WHERE id_acceso='$id_user'");
                                                        if ($row = $result->fetch_row()) {
                                                            $nombrec = $row[2];
                                                            $correo = $row[1];
                                                            $clave = $row[3];
                                                            $confirmar_clave = $row[3];
                                                            $nombre_labor_actual = $row[4];
                                                            $id_local_actual = $row[5];
                                                            $estado = $row[6];
                                                        }
                                                    }

                                                    if (isset($btnRegistrar)) {
                                                        if (empty($nombrec1) || empty($correo1)) {
                                                            ?>
                                                            <script>
                                                                alertaGeneral("Campos requeridos", "Debe completar todos los campos antes de continuar.", "error");
                                                            </script>
                                                            <?php
                                                        } else {
                                                            $result = $mysqli->query("SELECT * FROM acceso WHERE email='$correo1' AND id_acceso != '$id_user'");
                                                            if ($result->num_rows > 0) {
                                                                ?>
                                                                <script>
                                                                    alertaGeneral("Error al modificar el usuario", "El correo del usuario ya posee una cuenta asociada.", "error");
                                                                </script>
                                                                <?php
                                                            } else {
                                                                if (empty($clave)) {
                                                                    $clave = $row[3]; // clavevisual
                                                                    $clavecifrada = $row[3]; // cifrada
                                                                } else {
                                                                    $clavecifrada = md5($clave);
                                                                }

                                                                $updateQuery = $mysqli->prepare(
                                                                    "UPDATE acceso 
                                                                    SET email = ?, nombre = ?, clavevisual = ?, clavecifrada = ?, estado = ?, nombre_labor = ?, id_local = ? 
                                                                    WHERE id_acceso = ?"
                                                                );
                                                                $updateQuery->bind_param(
                                                                    'ssssssii', 
                                                                    $correo1, $nombrec1, $clave, $clavecifrada, $estado, $laborx, $local, $id_user
                                                                );

                                                                if ($updateQuery->execute()) {
                                                                    ?>
                                                                    <script>
                                                                        alertaGeneralHref("Usuario modificado", "¡Usuario modificado exitosamente!", "success", "usuarios.php");
                                                                    </script>
                                                                    <?php
                                                                } else {
                                                                    echo "Error al modificar el usuario: " . $mysqli->error;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="nombrec">Nombre Completo</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="nombrec"
                                                                name="nombrec" placeholder="Escriba su nombre completo acá..." value="<?= $nombrec; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="correo">Correo electrónico</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="correo"
                                                                name="correo" placeholder="Ejemplo: hugot@gmail.com..." value="<?= $correo; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="labor">Labor</label>
                                                        <select class="mb-2 form-control-sm form-control" id="labor" name="labor">
                                                            <option value="" disabled>Seleccione una labor</option>
                                                            <?php
                                                            $filtro_query = $labor == 'ADMINISTRADOR' ? " AND nombre_labor != 'ADMINISTRADOR'" : '';
                                                            $query = $mysqli->query("SELECT nombre_labor FROM labor WHERE nombre_labor != 'OWNER' $filtro_query");
                                                            while ($valores = $query->fetch_assoc()) {
                                                                $selected = $laborx == $valores['nombre_labor'] ? 'selected' : '';
                                                                echo "<option value='{$valores['nombre_labor']}' $selected>{$valores['nombre_labor']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="local">Local</label>
                                                        <select class="mb-2 form-control-sm form-control" id="local" name="local">
                                                            <option value="" disabled>Seleccione un local</option>
                                                            <?php
                                                            $filtro_query1 = $labor == 'ADMINISTRADOR' ? "WHERE id='$id_local_actual'" : '';
                                                            $query = $mysqli->query("SELECT id, nombre_local FROM locales $filtro_query1");
                                                            while ($valores = $query->fetch_assoc()) {
                                                                $selected = $local == $valores['id'] ? 'selected' : '';
                                                                $disabled = $labor == 'ADMINISTRADOR' ? '' : '';
                                                                echo "<option value='{$valores['id']}' $selected $disabled>{$valores['nombre_local']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="clave">Contraseña</label>
                                                        <input type="password" class="mb-2 form-control-sm form-control" id="clave" value="<?=$clave;?>"
                                                            name="clave" placeholder="Escriba su contraseña acá..." />
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="confirmar_clave">Repetir contraseña</label>
                                                        <input type="password" class="mb-2 form-control-sm form-control" id="confirmar_clave" value="<?=$clave;?>"
                                                            name="confirmar_clave" placeholder="Repita su contraseña acá..." />
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary form-control-sm" name="btnRegistrar" value="btnRegistrar">Modificar usuario</button>
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
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <?php include("scripts.php"); ?>
</body>

</html>
