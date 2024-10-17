<? include("puerta_principal.php");?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trade IN</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
	 <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <script src="js/jquery-1.4.2.min.js"></script>
<script>
$(document).ready(function() 
	{
	$("#link_usuarios").addClass("active");
	listado_datos();
  	});	
function listado_datos()
	{
		$('#example').DataTable().clear().destroy();
		$('#example').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "usuarios_datatable.php",
		"paging": true,
		  "lengthChange": false,
		  "searching": true,
		  "ordering": true,
		  "info": true,
		  "autoWidth": false,
		  "responsive": true,
		  "order": [[ 0, "desc" ]],
		  "columnDefs": [
    		{
			"targets": 0, // your case first column
			"className": "text-center",
			"width": "4%"
		   },
			{
			"targets": 4, // your case first column
			"className": "text-center",
			},
			{
			"targets": 5, // your case first column
			"className": "text-center",
			"width": "10%"
		   },
			{
			"targets": 6, // your case first column
			"className": "text-center"
		   }
		  	]
		
	} );
	}
function procesa_archivo()
		{
			console.log("hola munsdo f ")
			$('#modal-overlay').modal('show')
			setTimeout(function(){$('#modal-overlay').modal('hide')},3000);
			
		}
function desactivar_usuario(id)
		{
			console.log("id:"+id);
		$.post("usuarios_ajax.php", {id:id,proceso:'DESACTIVAR_USUARIO'}, 
		function(result) 
			{
				console.log(result);
				var obj = JSON.parse(result);

				if (obj.respuesta=='OK')
					{
					$('#modal-success').modal('show')
					document.getElementById('modal-success-texto').innerHTML='Usuario desactivado correctamente';
		    
						listado_datos();
					}

			}); 	
		}
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
					$('#modal-success').modal('show')
					document.getElementById('modal-success-texto').innerHTML='Usuario activado correctamente';
		    
						listado_datos();
					}

			}); 	
		}
	</script>	
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  	<?php include("sidebar-header.php");?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Usuarios</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <a href="usuarios_nuevo.php" class="btn btn-primary" onClick="procesa_archivo()">Nuevo Usuario</a>
				<div class="row">
				  <div class="col-lg-12">
			      </div>
				</div>
				<div class="row">
				  <div class="col-lg-12">&nbsp;
			      </div>
				  <div class="col-lg-12">
					   <div class="card">
						  <!-- /.card-header -->
						  <div class="card-body">
							<table id="example" class="table table-bordered table-striped">
							  <thead>
							  <tr>
								<th>Id</th>
								<th>Usuario</th>
								<th>Nombre</th>
								<th>Correo</th>
								<th>Labor</th>
								<th>Estado</th>
								<th></th>
							  </tr>
							  </thead>
							  <tbody>
							  </tbody>
							  <tfoot>
							  <tr>
								<th>Id</th>
								<th>Usuario</th>
								<th>Nombre</th>
								<th>Correo</th>
								<th>Labor</th>
								<th>Estado</th>
								<th></th>
							  </tr>
							  </tfoot>
							</table>
						  </div>
						  <!-- /.card-body -->
           			</div>
				  </div>
				</div>
				
              </div>
		    </div>

            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          
        </div>
        <!-- /.row -->
	    <!-- /.row -->  
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<div class="modal fade" id="modal-overlay">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="modal-header">
          <h4 class="modal-title">Procesando Archivo</h4>
        </div>
        <div class="modal-body">
          <p>Estamos procesando el archivo, por favor espere.&hellip;</p>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<div class="modal fade" id="modal-success">
        <div class="modal-dialog">
          <div class="modal-content bg-success">
            <div class="modal-header">
              <h4 class="modal-title">Proceso completado</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="modal-success-texto">Usuario orrectamente&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>	
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Page specific script -->	
<script>
	

$(document).ready(function() {
	
} );
	
	
</script>	
</body>
</html>
