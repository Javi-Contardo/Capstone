<!--Toastr-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script src="assets/js/scripts-init/toastr.js"></script>-->





<!-- Íconos -->
<!--<script src="https://kit.fontawesome.com/052c513b4a.js" crossorigin="anonymous"></script>-->
<script src="https://kit.fontawesome.com/4ae245a66c.js" crossorigin="anonymous"></script>
<!-- SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>

.vertical-nav-menu li.mm-active>a i {
    color: #00dc61 !important;
}
.vertical-nav-menu li a:hover i {
    color: #2580ff !important;
}

</style>
<!-- SIDEBAR -->
<div class="app-sidebar-wrapper">
	<div class="app-sidebar sidebar-shadow">
		<div class="app-header__logo">
			<a href="#" data-toggle="tooltip" data-placement="bottom" title="GungaStore" class="logo-src"></a>
			<button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
			</button>
		</div>
		<div class="scrollbar-sidebar scrollbar-container">
			<div class="app-sidebar__inner">
				<ul class="vertical-nav-menu">
					<li class="app-sidebar__heading">Dashboard</li>
					<li id="act-dashboard">
						<a href="dashboard.php" >
							<i class="metismenu-icon fa-solid fa-chart-line"></i>
							Dashboard 
						</a>
					</li>
					<!-- SUB. SIDEBAR -->
					<li class="app-sidebar__heading">Configuración</li>
					<li class="" id="act-carga-archivos">
						<a href="#">
							<i class="metismenu-icon fa-solid fa-code-branch"></i>
							Registro de archivos
							<i id="caretIcon" class="fa-solid fa-caret-down fa-rotate-90"></i>
						</a>
						<ul class="">
							
							<li><a href="carga_archivos_venta.php" id="act-carga-venta" style="list-style: none;"><i class="fa-solid fa-dollar-sign" style="margin-right: 5px"></i> Ventas</a></li>
							<li><a href="carga_archivos_stock.php" id="act-stock" style="list-style: none;"><i class="fa-solid fa-chart-simple" style="margin-right: 5px"></i>Stock</a></li>
							<li><a href="conectorb2b_falabella.php" id="act-carga-stock-cd" style="list-style: none;"><i class="fa-solid fa-warehouse" style="margin-right: 5px"></i>Stock CD</a></li>
						</ul>
					</li>
					<!-- SUB. SIDEBAR -->
<!--					<li>
						<a href="detalle_oc.php" id="act-detalleoc">
							<i class="metismenu-icon pe-7s-users">
							</i>Detalles OC
						</a>
					</li>-->
					
					<? if ($labor=='ADMINISTRADOR'){?>
					<li class="" id="act-empresas_no_retail1">
						<a href="#">
							<i class="metismenu-icon fa-solid fa-gear"></i>
							Empresas no retail
							<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
						</a>
						<ul class="">
							<li>
								<a href="empresas_no_retail.php" id="act-empresas_no_retail">Empresas NO Retail
								</a>
							</li>
							<li>
								<a href="facturas_no_retail.php" id="act-facturas_no_retail">Facturas no retail</a>
							</li>
							<li>
								<a href="vista_cliente_facturas_no_retail.php" id="act-vista_cliente_facturas_no_retail">Vista cliente</a>
							</li>
							<li>
								<a href="vista_transporte_facturas_no_retail.php" id="act-vista_transporte_facturas_no_retail">Vista transporte</a>
							</li>
							<li>
								<a href="conciliacion_no_retail.php" id="act-conciliacion_no_retail">Conciliacion No Retail</a>
							</li>
						</ul>
					</li>
					<?}?>
					<!-- SUB. SIDEBAR -->
					
					<? if ($labor=='ADMINISTRADOR'){?>
					
					<li class="app-sidebar__heading">ADMINISTRACI&oacute;N</li>
					<? if ($labor=='ADMINISTRADOR'){?>
					<li id="act-clientes">
						<a href="clientes.php">
							<i class="metismenu-icon fa-solid fa-star"></i>Clientes
						</a>
					</li>
					<? } ?>
					<? if ($labor=='ADMINISTRADOR'){?>
					<li id="act-usuarios">
						<a href="usuarios.php">
							<i class="metismenu-icon fa-solid fa-user"u></i>Usuarios
						</a>
					</li>
					<? } ?>
					<? } ?>

		
					
				</ul>
			</div>
		</div>
	</div>
</div>




<!-- ANIMACIONES SIDEBAR -->
<div class="app-sidebar-overlay d-none animated fadeIn"></div>
<div class="app-main__outer">
	<div class="app-main__inner">
		<div class="header-mobile-wrapper">
			<div class="app-header__logo">
				<a href="#" data-toggle="tooltip" data-placement="bottom" title="Koncilia" class="logo-src"></a>
				<button type="button" class="hamburger hamburger--elastic mobile-toggle-sidebar-nav">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>
				<div class="app-header__menu">
				<span>
					<button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
						<span class="btn-icon-wrapper">
							<i class="fa fa-ellipsis-v fa-w-6"></i>
						</span>
					</button>
				</span>
				</div>
			</div>
		</div>
		<!-- 

			IMPORTANTE:
			En esta parte hay algunos div que no están cerrados. Pero si se cierran, el footer no funciona bien.
		
		-->



<!-- HEADER -->
<div class="app-header">
	<div class="page-title-heading">
		<span id="titulo-cabecera" >_</span>
		<div id="descripcion-cabecera" class="page-title-subheading">
		...
		</div>
	</div>
	<div class="app-header-right">
		<div class="header-btn-lg pr-0">
			<div class="header-dots">
				<div class="dropdown">
					<button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 btn btn-link">
						<div class="page-title-heading text-decoration-none" id="nombre_cliente"><?=$nombre_cliente;?></div>
					</button>
					<? if ($labor=='ADMINISTRADOR'){?>
					<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
						<div class="dropdown-menu-header mb-0">
							<div class="dropdown-menu-header-inner bg-night-sky">
								<div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/city1.jpg');"></div>
								<div class="menu-header-content text-light">
									<h5 class="menu-header-title">Clientes</h5>
								</div>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-events-header" role="tabpanel">
									<div class="form-group">
                                        <input type="text" class="mb-2 form-control-sm form-control" id="filtro_cliente" name="filtro_cliente" placeholder="Escriba el cliente acá..." value="" onKeyUp="espera()" />
                                    </div>
									<div class="scroll-area-sm">
									<div class="scrollbar-container">
										<div class="p-3">
											<div id="listado_clientes" class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						
					</div>
					<? } ?>
				</div>
			</div>
		</div>
		<div class="header-btn-lg pr-0">
			<div class="header-dots">
				<div class="dropdown">
					<button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 btn btn-link">
						<i class="typcn typcn-bell"></i>
						<span id="punto_campana" class="badge badge-dot badge-dot-sm badge-danger" style="display: none">Notifications</span>
					</button>
					<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
						<div class="dropdown-menu-header mb-0">
							<div class="dropdown-menu-header-inner bg-night-sky">
								<div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/city2.jpg');"></div>
								<div class="menu-header-content text-light">
									<h5 class="menu-header-title">Notificaciones</h5>
									<!--<h6 class="menu-header-subtitle">You have <b>21</b> unread messages</h6>-->
								</div>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-events-header" role="tabpanel">
								<div class="scroll-area-sm">
									<div class="scrollbar-container">
										<div class="p-3">
											<div id="listado_notifi" class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
												
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
		<div class="header-btn-lg pr-0">
			<div class="widget-content p-0">
				<div class="widget-content-wrapper">
					<div class="widget-content-left">
						<div class="btn-group">
							<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
								<?
                                if ($img_avatar!=''){$ruta_img="archivos/img_avatar/$img_avatar";}
                                else{$ruta_img="assets/images/avatars/default.jpg";}
								?>

								<img width="42" class="rounded" src="<?=$ruta_img;?>" alt="">
								<i class="fa fa-angle-down ml-2 opacity-8"></i>
							</a>
							<div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
								<div class="dropdown-menu-header">
									<div class="dropdown-menu-header-inner bg-info">
										<div class="menu-header-image opacity-2" style="background-image: url('assets/images/dropdown-header/city1.jpg');"></div>
										<div class="menu-header-content text-left">
											<div class="widget-content p-0">
												<div class="widget-content-wrapper">
													<div class="widget-content-left mr-3">
														<a onClick="carga_modal()" href="#"><img width="42" id="avatar_emp" class="rounded-circle"
															 src="<?=$ruta_img;?>"
															 alt=""></a><input type="hidden" id="logo" value="">
													</div>
													<div class="widget-content-left">
														<div class="widget-heading"><?php echo $nombre ?>
														</div>
														<div class="widget-subheading opacity-8"><?php echo $labor ?>
														</div>
													</div>
													<div class="widget-content-right mr-2">
														<button class="btn-pill btn-shadow btn-shine btn btn-focus" onClick="cerrarSesion()" >Cerrar sesión</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="scroll-area-xs" style="height: 150px;">
									<div class="scrollbar-container">
										<ul class="nav flex-column">
											<li class="nav-item">
												<a href="javascript:void(0);" class="nav-link" onClick="abrir_modal_clave()">Cambiar Clave
												</a>
											</li>
										</ul>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="app-header-overlay d-none animated fadeIn"></div>
</div>

<script>
		
		
	
/* --------------------------------------------- SCRIPT PARA LA ELIMINACIO,SUBIDA Y RECORTE DE IMAGEN --------------------------------------------------- */	
//abertura y cierre modal advertencia de eliminacion de img	
function abMod(){
	let logo = $('#logo').val();
	if(logo=='' || logo== null){
          $('#modal_recorte').modal('show')
	}
	else{
		$('#modal-warning-borrar').modal('show');
	}
	
}
	
function close_ModWarn(){
	$('#modal-warning-borrar').modal('hide');
}	
	
//abertura y cierre modal subida y recorte de img	
function carga_modal(){
	$('#modal_recorte').modal('show')
}	
	
function cls_modRec(){
	$('#modal_recorte').modal('hide')
}
	
//script de subida y recorte de imagen
	const inputImage = document.querySelector('#customFile');
    // Nodo donde estará el editor
    const editor = document.querySelector('#editor');
    // El canvas donde se mostrará la previa
    const miCanvas = document.querySelector('#preview');
    // Contexto del canvas
    const contexto = miCanvas.getContext('2d');
    // Ruta de la imagen seleccionada
    let urlImage = undefined;
	
    // Evento disparado cuando se adjunte una imagen
    inputImage.addEventListener('change', abrirEditor, false);
	
function abrirEditor(e){
        // Obtiene la imagen
        urlImage = URL.createObjectURL(e.target.files[0]);
		 // Borra editor en caso que existiera una imagen previa
        editor.innerHTML = '';
        let cropprImg = document.createElement('img');
        cropprImg.setAttribute('id', 'croppr');
        editor.appendChild(cropprImg);

        // Limpia la previa en caso que existiera algún elemento previo
        contexto.clearRect(0, 0, miCanvas.width, miCanvas.height);

        // Envia la imagen al editor para su recorte
        document.querySelector('#croppr').setAttribute('src', urlImage);
		
        // Crea el editor
	    new Croppr('#croppr', {
			autoCrop: true,
            aspectRatio: 1,
            startSize: [50, 50],
			onCropEnd: recortarImagen
		})		
    }	
	
function recortarImagen(data){
	
        // Variables
        const inicioX = data.x;
        const inicioY = data.y;
        const nuevoAncho = data.width;
        const nuevaAltura = data.height;
        const zoom = 1;
        let imagenEn64 = '';
        // La imprimo
        miCanvas.width = nuevoAncho;
        miCanvas.height = nuevaAltura;
        // La declaro
        let miNuevaImagenTemp = new Image();
        // Cuando la imagen se carge se procederá al recorte
        miNuevaImagenTemp.onload = function() {
			
			
		  // Se recorta
            contexto.drawImage(miNuevaImagenTemp, inicioX, inicioY, nuevoAncho * zoom, nuevaAltura * zoom, 0, 0, nuevoAncho, nuevaAltura);
            // Se transforma a base64
            imagenEn64 = miCanvas.toDataURL("image/jpeg");
            // Mostramos el código generado
            //document.querySelector('#base64').textContent = imagenEn64;
           // document.querySelector('#base64HTML').textContent = '<img src="' + imagenEn64.slice(0, 40) + '...">';
        }
        // Proporciona la imagen cruda, sin editarla por ahora
        miNuevaImagenTemp.src = urlImage;
    }	
// subida de imagen 	
/*function sube_archivo(input){
  	console.log("Espere un momento por favor");
    var reader = new FileReader();
    reader.onload = function (e) 
        {
        console.log("PROCESO 1");       
		
		var nombre=input.files[0].name;
		var extension = nombre.split(".");
		var id_empleado='<? //echo $_SESSION['id_usuario']; ?>';
		console.log('este id de empleado enviamos:'+id_empleado)       
        
		if (extension[1] != "jpg" && extension[1] != "png" && extension[1]!="jpeg"  && extension[1]!="gif")
			{
			mensaje('Solo se permiten archivos jpg,png,jpeg y gif')	
			return;	
			}
		$('#modal-overlay1').modal({backdrop: 'static',keyboard: false})
		$('#modal_recorte').modal('hide')
		$('#modal-overlay1').modal('show')
	
		var formData = new FormData();
        formData.append("archivo", input.files[0]); // En la posición 0; es decir, el primer elemento
       	formData.append("id",id_empleado);
        formData.append("proceso", 'SUBE_ARCHIVO');
        $.ajax({
            type: 'POST',
            url: 'subida_archivo_avatar_ajax.php',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function(){
            var xhr = $.ajaxSettings.xhr() ;
            xhr.upload.onprogress = function(evt)
                { console.log('progress', evt.loaded/evt.total*100) ; var porcentaje=Math.round(evt.loaded/evt.total*100);
                document.getElementById('modal-overlay-texto1').innerHTML="Subiendo archivo "+porcentaje+"%";
                };
            xhr.upload.onload = function()
				{
				console.log('DONE!') 
				$('#modal-overlay1').modal('hide')
				} ;
           
				// return the customized object
            return xhr ;
            },
            success: function (result) 
                {
                console.log(result);
                var obj = JSON.parse(result);
                if (obj.respuesta=='OK')
                    {
                    console.log( "ARCHIVO CARGADO EN EL SERVIDOR" );
						//$.post("retiro_progreso.php",{limpiar_sesion:'OK'})
					
						setTimeout(function (){
							$('#modal-overlay1').modal('hide')
						},500);
						console.log(obj.nombre_archivo+'------nombre archivo')
						document.getElementById('logo').value=obj.nombre_archivo
						document.getElementById('customFile').value='';
						document.getElementById('mostrar_imagen').src='archivos/img_avatar/'+obj.nombre_archivo
						//document.getElementById('carga_imagen').style.display='none'
						//check_img();
						//carga_imagenes();
                    }
				else
					{
					mensaje(obj.respuesta);
					setTimeout(function (){
							$('#modal-overlay1').modal('hide')
						},500);
					}
                }
            });

        }
	reader.readAsDataURL(input.files[0]);

    }*/	
	
/*function mensaje(texto){
	$(document).ready(function() 
		{
		$('#modal-warning').modal('show')
		document.getElementById('texto_mensaje').innerHTML=texto;
		});	
}*/
	
/*function mensaje_ok(texto){
	$(document).ready(function() 
		{
		$('#modal-success').modal('show')
		document.getElementById('texto_mensaje_ok').innerHTML=texto;
		});	
}	
	
function borrar_img(){
		var nombre_img = document.getElementById('avatar_emp').value;
		console.log(nombre_img+'--Nombre archivo a eliminar')
		//$('#modal-warning-borrar').modal('show');
		Swal.fire({
		  title: 'Estas Seguro(a)?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#198754',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Eliminar'
		}).then((result) => {
		  if (result.isConfirmed) {
			borrar_img_si(nombre_img)
		  }
		})
		
	}	
	
function borrar_img_si(nombre_img){
		var id ='<? //echo $_SESSION['id_usuario']; ?>';
		console.log("se esta enviando el id de:"+id)
		console.log('este es el archivo a eleminar:'+nombre_img)
		
		$.post("subida_archivo_avatar_ajax.php", {nombre_img:nombre_img,id:id,proceso:'BORRA_IMAGEN'}, 
		function(result) 
		{
			window.location.reload();	
			
		}); 

		
	}*/
	
function guardar_img(){	
	// converitoms el canvas en una imagen //	
	var imagen = document.getElementById('preview');
    var dataURL = imagen.toDataURL("image/jpeg",0.95);
    var blobBin = atob(dataURL.split(',')[1]);
    var array = [];
    for(var i = 0; i < blobBin.length; i++) {
        array.push(blobBin.charCodeAt(i));
    }
    var file= new Blob([new Uint8Array(array)],{type: 'image/jpeg'});	
	// fin de la conversion 		
	var ctx = imagen.getContext("2d");
	var img=document.getElementById('avatar_emp');							
	ctx.drawImage(img, 0, 0, imagen.width, imagen.height);
	//document.getElementById('img_lista').src=file;        
		
	var id_usuario='<?=$id_usuario;?>';
	$('#modal-overlay1').modal({backdrop: 'static',keyboard: false})
	$('#modal_recorte').modal('hide')
	$('#modal-overlay1').modal('show')

	var formData = new FormData();
	formData.append("archivo",file); // En la posición 0; es decir, el primer elemento
	formData.append("id",id_usuario);
	formData.append("proceso", 'SUBE_ARCHIVO');
	$.ajax({
		type: 'POST',
		url: 'subida_archivo_avatar_ajax.php',
		data: formData,
		processData: false,
		contentType: false,
		xhr: function(){
		var xhr = $.ajaxSettings.xhr() ;
		xhr.upload.onprogress = function(evt)
			{ console.log('progress', evt.loaded/evt.total*100) ; var porcentaje=Math.round(evt.loaded/evt.total*100);
			//document.getElementById('modal-overlay-texto1').innerHTML="Subiendo archivo "+porcentaje+"%";
			};
		xhr.upload.onload = function()
			{
			console.log('DONE!') 
			$('#modal-overlay1').modal('hide')

			} ;

			// return the customized object
		return xhr ;
		},
		success: function (result) 
			{
			console.log(result);
			var obj = JSON.parse(result);
			if (obj.respuesta=='OK')
				{
				console.log( "ARCHIVO CARGADO EN EL SERVIDOR" );					
					setTimeout(function (){
						$('#modal-overlay1').modal('hide')
					},500);
					console.log(obj.nombre_archivo+'------nombre archivo')
					document.getElementById('logo').value=obj.nombre_archivo;
					window.location.reload();
				}
			else
				{
				mensaje(obj.respuesta);
				setTimeout(function (){
						$('#modal-overlay1').modal('hide')
					},500);
				}
			}
		});		
	}
function cambio_cliente(valor)
	{
		console.log(valor)
		$.post("notificaciones_ajax.php", {proceso:'CAMBIO_CLIENTE',valor:valor}, 
			   function(data) 
			   	{
				//console.log(data)
				console.log("cambio cliente")
				location.href='dashboard.php'
				}); 
		
	}
function filtrar_cliente()
	{
		var valor=document.getElementById('filtro_cliente').value;
		console.log(valor)
		$.post("notificaciones_ajax.php", {proceso:'LISTADO_CLIENTES',filtro_valor:valor}, 
			   function(data) 
			   	{
			console.log("lista de clientes")
				$('#listado_clientes').html(data);
		
				//console.log("verificanc notificaciones:"+data)
		
				}); 
	}
var myTimeout=''
function espera()
	{
	clearTimeout(myTimeout);
	myTimeout = setTimeout(function () { filtrar_cliente()}, 800);
	}	
	
function abrir_modal_clave()
	{
	$('#modal_cambio_clave').modal('show')
		
	}
function cambiar_clave()
	{
		var clave_a=document.getElementById('clave_actual').value;
		var clave_1=document.getElementById('nueva_clave1').value;
		var clave_2=document.getElementById('nueva_clave2').value;
		var largo1=clave_1.length;
		//console.log(clave_a+"  "+clave_1+"  "+clave_2+" largo:"+largo1)
		
		if (clave_1!=clave_2)
			{
			alertaGeneral("Nueva clave inválida","La nueva clave no coincide, verifique que la ha escrito correctamente","warning");	
            return;
			}
		if (largo1<6)
			{
			alertaGeneral("Nueva clave inválida","La nueva clave debe contener como mínimo 6 caracteres entre letras y números.","warning");	
            return;
			}
		var clave_n=clave_1;
		$.post("notificaciones_ajax.php", {proceso:'CAMBIO_CLAVE',clave_a:clave_a,clave_n:clave_n}, 
			   function(data) 
			   	{
				console.log("respuesta ")
				console.log(data)
				if (data=='OK')
					{
					Swal.fire({
						title: 'Cambio de clave',
						text: 'Clave modificada correctamente, la sesión se cerrara para que acceda con su nueva clave',
						icon: 'warning',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Cerrar sesión',
						}).then((result) => {
							if (result.isConfirmed) {
								//Matar las cookies.
							}
								document.cookie = "email=; expires=Thu, 18 Dec 2000 12:00:00 UTC; ";
								document.cookie = "password=; expires=Thu, 18 Dec 2000 12:00:00 UTC; ";
								//Redireccionar al login.
								location.href = "login.php";
						})

					}
				else
					{
					alertaGeneral("Clave actual inválida","Verifique que ha escrito la clave actual correctamente.","warning");	
            			
					}
			
				//console.log("verificanc notificaciones:"+data)
		
				}); 
		
	}

	
	

var myTimeout=''

$('body').click(function() {
			console.log("click en pantalla")
	
				clearTimeout(myTimeout);
				myTimeout = setTimeout(function () {
					console.log("hola mundo");
					
					$.post("puerta_checksesion.php", 
					function(result) 
					{
						console.log(result);
					if (result=='TERMINADA')
						{
							location.href='login.php';
						}
					}); 
					
					}, 10000);
			
			
		});	
</script>