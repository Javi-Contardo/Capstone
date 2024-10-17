<!-- SCRIPTS BASE DEL HEADER. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<!-- Para que el servidor no bloquee jQuery -->
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- Disable tap highlight on IE -->
<meta name="msapplication-tap-highlight" content="no">
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.1/dist/apexcharts.min.js"></script>
<!-- Para los íconos -->
<!--<script src="https://kit.fontawesome.com/4270c959fd.js" crossorigin="anonymous"></script>-->
<link href="assets/fontawesome6/css/fontawesome.css" rel="stylesheet">
<link href="assets/fontawesome6/css/brands.css" rel="stylesheet">
<link href="assets/fontawesome6/css/solid.css" rel="stylesheet">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Importar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="assets/css/base.min.css">
	<!-- script del cortador de imagen -->	
	<script src="https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.js"></script>
	<link href="https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.css" rel="stylesheet"/>
	<!-- script del cortador de imagen -->	

<script language="javascript">
	//Función de cerrar sesión.
	function cerrarSesion()
    {

		var url=window.location.host

		if (url=='servidordstchile.ddns.net'){var proyecto='proyecto_b2b'}else {var proyecto='';}
	    Swal.fire({
	        title: 'Cerrar sesión',
	        text: '¿Estás seguro que deseas cerrar sesión?',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Cerrar sesión',
	        cancelButtonText: 'No'
	        }).then((result) => {
	            if (result.isConfirmed) {
	                //Matar las cookies.
	            	document.cookie = "email=; expires=Thu, 18 Dec 2000 12:00:00 UTC; path=/"+proyecto+";";
                	document.cookie = "password=; expires=Thu, 18 Dec 2000 12:00:00 UTC; path=/"+proyecto+";";
                	//Redireccionar al login.
	            	location.href = "login.php";
	            }
	        })
		
    }
	
	
	
    //Función para cualquier alerta que no necesite confirmación.
	function alertaGeneral(titulo,contenido,icono){
		Swal.fire({
		    icon: icono,
		    title: titulo,
		    text: contenido,
		});
		
	}
	
	////////////Funcion para alertas de pagos y cuenta corriente//////////
	
	function alertaCTAPAGOS(titulo,contenido,icono){
		Swal.fire({
		    icon: icono,
		    title: titulo,
		    html: contenido,
		});
		
	}

    //Función para alertas que requieran una redirección a otro sitio.
	function alertaGeneralHref(titulo,contenido,icono,ruta_href){
		

		Swal.fire({
		    icon: icono,
		    title: titulo,
		    text: contenido,
		    footer: 'Redireccionando...',
		}).then((result) => {
			  if (result) {
			   
				location.href = ruta_href;
			  }
			});		
	}
	function alertaConfirmacion(titulo,contenido,icono,txtBotonConf,txtBotonCancel,funcResultado,tituloFunc1,contenidoFunc2,iconoFunc2)
    {
	    Swal.fire({
	        title: titulo,
	        text: contenido,
	        icon: icono,
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: txtBotonConf,
	        cancelButtonText: txtBotonCancel
	        }).then((result) => {
	            if (result.isConfirmed) {
					alertaGeneral(tituloFunc1,contenidoFunc2,iconoFunc2);
				}
	        })
		
    }
</script>
<!-- Modal de carga de imagen --> 
  <div class="modal fade" id="modal_recorte" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_recorte_titulo">Avatar</h5>
          <button type="button" class="close" aria-label="Close" onClick="cls_modRec()"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body">
          <div class="row form-group mt-1" >
            <div class="col-lg-10">
              <input type="file"  id="customFile" accept=".png, .jpeg, .jpg, .gif"  data-toggle="modal" data-target="#exampleModal">
              <label for="fileupload">* jpg,jpeg,png y gif</label>
            </div>
          </div>	
          <div class="row form-group mt-1" >
            <div class="col-lg-8">
              <div class="col-sm-12" id="editor" style="width: auto; display: flex"></div>
              <code id="base64" onChange="sube_archivo(this)" ></code> </div>
            <div class="col-lg-4">
              <div>
                <canvas id="preview" style="height: auto; width: 100% !important; border: 1px solid #A3A3A3; border-radius: 5px" ></Canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" id="cut_img" onClick="guardar_img()">guardar_recorte</button>
        </div>
      </div>
    </div>
  </div>	