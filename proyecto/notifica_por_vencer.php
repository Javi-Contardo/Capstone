<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
include("conexion.php");

require ('PHPMailer-master2/src/PHPMailer.php');
require ('PHPMailer-master2/src/SMTP.php');
require ('PHPMailer-master2/src/Exception.php');

$horabase = date('Y-m-d H:i:s');
print("hora de inicio: $horabase xx <br>");

// Definir las fechas
$hoy = new DateTime(); // Utilizamos un objeto DateTime para manejar mejor las fechas
$hoy_formateado = $hoy->format('Y-m-d');
$fecha_limite = $hoy->modify('+7 days')->format('Y-m-d'); // Fecha límite para productos a punto de vencer

// Consulta para obtener los productos que están a punto de vencer (próximos 7 días) o ya vencidos
$resultlist2 = $mysqli->query("SELECT id, numero_tienda, nombre_tienda, numero_articulo, desc_art_1, upc, cantidad_existente_tienda, fecha_carga, subido_por, sku_interno, lote, fecha_vencimiento
                                FROM comercial_stock
                                WHERE fecha_vencimiento <= CURDATE() + INTERVAL 7 DAY and fecha_carga = (SELECT MAX(fecha_carga) FROM comercial_stock)"); // Incluir productos vencidos también

$adminsQuery = "SELECT email, nombre_labor, id_local, nombre_fantasia, nombre
                FROM acceso";
$adminsResult = $mysqli->query($adminsQuery);

if ($resultlist2 && $resultlist2->num_rows > 0) {
    // Agrupar los productos por sucursal
    $productosPorSucursal = [];
    while ($row2 = $resultlist2->fetch_assoc()) {
        $productosPorSucursal[$row2['numero_tienda']][] = $row2;
    }

    // Recorrer a los administradores para enviar correos
    while ($admin = $adminsResult->fetch_assoc()) {
        // Determinar los productos que se enviarán dependiendo del rol
        $productosParaEnviar = [];

        if ($admin['nombre_labor'] == 'OWNER') {
            // El OWNER recibe productos de todas las sucursales
            $productosParaEnviar = $productosPorSucursal;
        } elseif ($admin['nombre_labor'] == 'ADMINISTRADOR') {
            // El ADMINISTRADOR recibe productos de su sucursal
            if (isset($productosPorSucursal[$admin['id_local']])) {
                $productosParaEnviar = [$admin['id_local'] => $productosPorSucursal[$admin['id_local']]];
            }
        }

        // Si hay productos para enviar
        if (!empty($productosParaEnviar)) {
            ob_start(); // Inicia el buffer de salida

            ?>
            <!-- Código HTML para el mensaje de correo -->
            <html>
            <head>
            <title>Informe de Productos a Vencer</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
            body, td, th {
                font-family: Arial, Helvetica, sans-serif;
            }
            table {
                width: 100%;
                border: 1px solid #660066;
            }
            td {
                padding: 8px;
                text-align: center;
            }
            th {
                background-color: #cbc9c9;
                text-align: center;
            }
            </style>
            </head>
            <body bgcolor="#FFFFFF" text="#000000">
            <div align="center">
                <?php
                $now = new DateTime();
                setlocale(LC_ALL, 'esl');
                date_default_timezone_set("America/Santiago");
                $fechavista = $now->format('d-m-Y');

                // Título y mensaje principal
                $nombre_usuario = $admin['nombre'] . " " . $admin['nombre_fantasia']; // Nombre del admin con nombre del local
                if ($admin['nombre_labor'] == 'OWNER') {
                    // Si es un OWNER, solo mostramos su nombre sin el nombre de la tienda
                    $nombre_usuario = $admin['nombre'];
                }
                echo "<div align='left'><font size='3' color='black' face='Arial, Helvetica, sans-serif'>
                      Estimad@ $nombre_usuario, te informamos que los siguientes productos están a una semana de vencer o ya han vencido (<strong>$fechavista</strong>)</font>\n";
                echo "<p>&nbsp;</p>";

                // Tabla con los datos de los productos
                echo "<table align='center' cellpadding='1' cellspacing='1'>";
                echo "<tr>";
                echo "<th>ID</th><th>Numero de tienda</th><th>Nombre de tienda</th><th>Descripción de artículo</th><th>UPC</th><th>Cantidad</th><th>Fecha de ingreso</th><th>Ingresado por</th><th>Sku interno</th><th>Lote</th><th>Fecha vencimiento</th><th>Días hasta vencimiento</th><th>Estado</th>";
                echo "</tr>";

                // Mostrar los datos de los productos
                foreach ($productosParaEnviar as $sucursal_id => $productos) {
                    foreach ($productos as $producto) {
						
					 $fecha_vencimiento = new DateTime($producto['fecha_vencimiento']);		
						$hoy = new DateTime();

						$diferencia = $hoy->diff($fecha_vencimiento)->format('%r%a');
						$row[] = $diferencia;
						if($diferencia>=1)
						{
							$estado='<p style="color: green">NO VENCIDO</p>';
						}
						else
						{
							$estado='<p style="color: red">VENCIDO</p>';
						}

                        // Agregar los productos a la tabla
                        echo "<tr bgcolor='#ffffff'>";
                        echo "<td>" . $producto['id'] . "</td>";
                        echo "<td>" . $producto['numero_tienda'] . "</td>";
                        echo "<td>" . $producto['nombre_tienda'] . "</td>";
                        echo "<td>" . $producto['desc_art_1'] . "</td>";
                        echo "<td>" . $producto['upc'] . "</td>";
                        echo "<td>" . $producto['cantidad_existente_tienda'] . "</td>";
                        echo "<td>" . $producto['fecha_carga'] . "</td>";
                        echo "<td>" . $producto['subido_por'] . "</td>";
                        echo "<td>" . $producto['sku_interno'] . "</td>";
                        echo "<td>" . $producto['lote'] . "</td>";
                        echo "<td>" . $producto['fecha_vencimiento'] . "</td>";
                        echo "<td>" . $diferencia . " días</td>"; // Mostrar los días hasta el vencimiento
                        echo "<td>" . $estado . "</td>"; // Mostrar el estado
                        echo "</tr>";
                    }
                }

                echo "</table>";
                echo "<p>&nbsp;</p>";
                echo "<div align='center'><font size='3' color='black' face='Arial, Helvetica, sans-serif'><strong>Por favor gestionar los tickets de reversa para estos equipos.</strong></font></div>";
                echo "<div align='center'><font size='3' color='black' face='Arial, Helvetica, sans-serif'><strong>Atentamente, Servicio Técnico Negocios el chino.</strong></font></div>";
                echo "<div align='center'><font size='3' color='black' face='Arial, Helvetica, sans-serif'>Se han omitido los acentos para una correcta visualización en los servicios de correo.</font></div>";

                ?>
            </div>
            </body>
            </html>
            <?php

            $html = ob_get_contents(); // Obtiene el contenido del buffer


            // Configuración de PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->Mailer = "smtp";
            $mail->Host = "mail.negocioelchinoges.cl";
            $mail->Port = 26;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->SMTPAuth = true;
            $mail->Username = "admin@negocioelchinoges.cl";
            $mail->Password = "Wumdsi,G{SG4";
            $mail->From = "admin@negocioelchinoges.cl";
            $mail->FromName = "Servicio Técnico Negocio el Chino";
            $mail->Timeout = 120;

            // Dirección de correo a donde se enviará
            $mail->AddAddress($admin['email']); // Enviar al correo del administrador

            $asunto = "EQUIPOS A PUNTO DE VENCER O VENCIDOS";
            $mail->Subject = $asunto;
            $mail->isHTML(true);
            $mail->Body = $html;

            // Enviar el correo
            if (!$mail->Send()) {
                echo "Hubo un error al enviar el correo a " . $admin['email'] . "<br>";
            } else {
                echo "Correo enviado a " . $admin['email'] . "<br>";
            }

            ob_end_clean(); // Limpiar el buffer
        }
    }
} else {
    echo "No hay productos próximos a vencer o ya vencidos para enviar correos.";
}
?>
