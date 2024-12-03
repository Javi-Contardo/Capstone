<?php include("puerta_principal.php"); ?>
<html lang="es">

<head>
    <?php include("scripts-header.php"); ?>
    <script>
        $(document).ready(function() {
            $("#act-homologacion").addClass("mm-active");
            $("#titulo-cabecera").text("Homologacion");
            $("#descripcion-cabecera").text("Módulo para la gestión de homologación del sistema Gungastore");
            $("#titulo-cabecera").append($("<a href='homologacion.php' class='text-success'><i class='fa-duotone fa-solid fa-arrow-left' style=''--fa-secondary-color: #ffffff;'></i> Atras</a>"));

            //Evitar el envío del formulario al recargar la página.
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
                                                <h5 class="card-title">Modificar homologación.</h5>
                                                <form id="#" class="col-md-10 mx-auto" method="post" action="">
                                                    <?php
                                                    $id_user = $_GET['id_homologacion'] ?? '';
                                                    $btnRegistrar = $_POST['btnRegistrar'] ?? null;
                                                    $nombrec = $_POST['nombrec'] ?? '';
                                                    $correo = $_POST['correo'] ?? '';

                                                    if ($id_user !== '') {
                                                        // Recuperar datos de la base de datos si es un usuario existente.
                                                        $result = $mysqli->query("SELECT descripcion, codigo_retail FROM homologacion WHERE id_homologacion='$id_user'");
                                                        if ($row = $result->fetch_assoc()) {
                                                            $nombrec = $nombrec ?: $row['descripcion'];
                                                            $correo = $correo ?: $row['codigo_retail'];
                                                        }
                                                    }

                                                    if (isset($btnRegistrar)) {
                                                        // Validar campos vacíos
                                                        if (empty($nombrec) || empty($correo)) {
                                                            ?>
                                                            <script>
                                                                alertaGeneral("Campos requeridos", "Debe completar todos los campos antes de continuar.", "error");
                                                            </script>
                                                            <?php
                                                        } else {
                                                            // Validar si el código ya existe para otro producto
                                                            $result = $mysqli->query("SELECT * FROM homologacion WHERE codigo_retail='$correo' AND id_homologacion != '$id_user'");
                                                            if ($result->num_rows > 0) {
                                                                ?>
                                                                <script>
                                                                    alertaGeneral("Error al modificar la homologación", "El código de retail ya pertenece a un producto.", "error");
                                                                </script>
                                                                <?php
                                                            } else {
                                                                // Actualizar datos utilizando consultas preparadas
                                                                $updateQuery = $mysqli->prepare("UPDATE homologacion SET codigo_retail = ?, descripcion = ? WHERE id_homologacion = ?");
                                                                $updateQuery->bind_param('ssi', $correo, $nombrec, $id_user);

                                                                if ($updateQuery->execute()) {
                                                                    ?>
                                                                    <script>
                                                                        alertaGeneralHref("Homologación modificada", "¡Homologación modificada exitosamente!", "success", "homologacion.php");
                                                                    </script>
                                                                    <?php
                                                                } else {
                                                                    echo "Error al modificar la homologación: " . $mysqli->error;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="nombrec">Nombre Producto</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="nombrec"
                                                                name="nombrec" placeholder="Escriba el nombre del producto acá..." value="<?= htmlspecialchars($nombrec); ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="correo">Código retail</label>
                                                        <div>
                                                            <input type="text" class="mb-2 form-control-sm form-control" id="correo"
                                                                name="correo" placeholder="Escriba el código retail acá..." value="<?= htmlspecialchars($correo); ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary form-control-sm" name="btnRegistrar" value="btnRegistrar">Modificar Homologación</button>
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
