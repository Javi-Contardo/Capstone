<?php include("puerta_principal.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
?>

<html lang="es">

<head>
    <?php include("scripts-header.php");?> 
    <title>Gungastore</title>
    <script>
        $(document).ready(function() 
            {
                $("#act-homologacion").addClass("mm-active");
				$("#titulo-cabecera").text("Homologacion");
				$("#descripcion-cabecera").text("Módulo para la gestión de homologacion del sistema Gungastore");

                listado_datos();
            });	
            function listado_datos()
                {
                    $('#ejemplo').DataTable().clear().destroy();
                    $('#ejemplo').dataTable( {
                        "bProcessing": true,
                        "bServerSide": true,
                        "sAjaxSource": "homologacion_datatable.php",
                        "paging": true,
                        "lengthChange": false,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "order": [[ 0, "desc" ]],
                        "columnDefs": 
                        [
                            {
                                "targets": 0, 
                                "className": "text-left",
                                "width": "30%"
                            },
                            {
                                "targets": 1, 
                                "className": "text-left",
                                "width": "30%"
                            },
                            {
                                "targets": 2, 
                                "className": "text-center",								                             
                                "width": "20%"

                            }
                        ],

                    });
                }
            function procesa_archivo()
                    {
                        console.log("hola munsdo f ")
                        $('#modal-overlay').modal('show')
                        setTimeout(function(){$('#modal-overlay').modal('hide')},3000);
                        
                    }
		
            function eliminar_homologacion(id) 
                    {
                        console.log("id:"+id);
                    $.post("homologacion_ajax.php", {id:id,proceso:'ELIMINAR_HOMOLOGACION'}, 
                    function(result) 
                        {
                            console.log(result);
                            var obj = JSON.parse(result);

                            if (obj.respuesta=='OK')
                                {
                                    alertaGeneral("homologacion eliminada","La homologacion ha sido eliminada.","warning");
                                    listado_datos();
                                }

                        }); 	
                    }
/*			function eliminar_usuario(id) 
                    {
                        console.log("id:"+id);
                    $.post("usuarios_ajax.php", {id:id,proceso:'ELIMINAR_USUARIO'}, 
                    function(result) 
                        {
                            console.log(result);
                            var obj = JSON.parse(result);

                            if (obj.respuesta=='OK')
                                {
                                    alertaGeneral("Usuario Eliminado","El usuario ha sido eliminado.","warning");
                                }

                        }); 	
                    }*/
		
            function activar_usuario(id)
                    {
                        console.log("id:"+id);
                    $.post("usuarios_ajax.php", {id:id,proceso:'ACTIVAR_USUARIO'}, 
                    function(result) 
                        {
                            console.log(result);
                            var obj = JSON.parse(result);

                            if (obj.respuesta=='OK')
                                {
                                    alertaGeneral("Usuario Activado","¡Usuario activado correctamente!","success");
                                    //listado_datos();
									$("#des"+id).addClass("d-block");
                                	$("#des"+id).removeClass("d-none");
                                	$("#act"+id).addClass("d-none");
                                	$("#act"+id).removeClass("d-block");
                                }

                        }); 	
                    }
    </script>
    <style>
        @media (max-width: 768px)
        {
            .botones
            {
                margin-top:5px;
                margin-bottom:5px;
                margin-left:10px;

            }
        }
    </style>
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
                                                <a href="homologacion_nuevo.php" class="justify-content-end form-control-sm">
                                                    <button class="mb-2 mr-2 btn btn-primary form-control-sm">Nueva homologacion</button>
                                                </a>
                                                <table style="width: 100%;" id="ejemplo" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Descripcion</th>
                                                            <th>Codigo retail</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
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
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <!-- Incluir scripts. -->
    <?php include("scripts.php");?>

    

</body>

</html>