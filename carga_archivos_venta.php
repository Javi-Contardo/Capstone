<?php include("puerta_principal.php");?>

<html lang="es">
<head>
    <?php include("scripts-header.php");?>
    <script>
        $(document).ready(function() 
            {
                $("#act-carga-archivos").addClass("mm-active");
                $("#act-carga-venta").addClass("mm-active");
                $("#titulo-cabecera").text("Conectores B2B (Walmart) | ");
                $("#titulo-cabecera").append($("<a href='conectorb2b_walmart.php' class='text-success'><i class='fa-solid fa-arrow-left'></i> Atras</a>"));
                $("#descripcion-cabecera").text("Cargar Archivo Cuenta Corriente");
            });
        
        function listado()
        {
        $('#example').DataTable().clear().destroy();
        $('#example').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "carga_archivos_walmart_venta_datatable.php",
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
                "targets": 1, // your case first column
                "width": "5%"
                },
                {
                "targets": 2, // your case first column
                "width": "20%"
                },
                {
                "targets": 3, // your case first column
                "className": "text-center",
                "width": "10%"
                },
                {
                "targets": 4, // your case first column
                "className": "text-center",
                "width": "15%"
                },
                {
                "targets": 5, // your case first column
                "className": "text-center",
                "width": "4%"
                }
                ],
            } );		
        }
        function check_modal()
        		{
        			console.log("hola munsdo f ")
        			//$('#modal-overlay').modal({backdrop: 'static',keyboard: false})
        			//$('#modal-overlay').modal('show')
        			//setTimeout(function(){$('#modal-overlay').modal('hide')},3000);
        			//$('#modal-warning').modal({backdrop: 'static',keyboard: false})
        			$('#modal-warning').modal('show')
        			document.getElementById('modal-warning-texto').innerHTML="error de prueba";
               		//setTimeout(function(){$('#modal-overlay').modal('hide')},3000);
                
        		}
      
        function sube_archivo(input) 
        	{
            $.post("carga_archivos_walmart_venta_progreso_cancelar.php",{limpiar_sesion:'OK'})
            console.log("Espere un momento por favor");
            var reader = new FileReader();
            reader.onload = function (e) 
                {
                  console.log("PROCESO 1");
                  var nombre=input.files[0].name;
                  var ln=nombre.length
                  var ln1=ln-1;
                  var ln2=ln-2;
                  var ln3=ln-3
						      console.log(nombre[ln1]+" "+nombre[ln2]+" "+nombre[ln3])
                  //var extension = nombre.split(".");
              
                  var extension = nombre[ln3]+nombre[ln2]+nombre[ln1];
                  console.log("extension:"+extension);
                  if ((extension!='XLS'&&extension!='xls'&&extension!='CSV'&&extension!='csv'))
                    {
                      alertaGeneral("Error","Solo se permiten archivos Excel .xls","warning");	
                      return;	
                    }
                  //$('#modal-overlay').modal({backdrop: 'static',keyboard: false})
                  $('#modal-overlay').modal('show')
                    
        	    	  var formData = new FormData();
                  formData.append("archivo", input.files[0]); // En la posición 0; es decir, el primer elemento
                  formData.append("area", 'SUBE_ARCHIVO');
                  $.ajax({
                      type: 'POST',
                      url: 'carga_archivos_venta_ajax.php',
                      data: formData,
                      processData: false,
                      contentType: false,
                      xhr: function(){
                        var xhr = $.ajaxSettings.xhr();
                        xhr.upload.onprogress = function(evt){ 
                          console.log('progress', evt.loaded/evt.total*100) ; var porcentaje=Math.round(evt.loaded/evt.total*100);
                          document.getElementById('modal-overlay-texto').innerHTML="Subiendo archivo "+porcentaje+"%";
                          };
                        xhr.upload.onload = function(){
                          console.log('DONE!')
                          setTimeout(function (){
                          //$('#modal-overlay').modal('hide')
                          },500);
                          //$('#modal-overlay').modal('hide')
                          };
                        // return the customized object
                        return xhr;
                      },
                      success: function (result) 
                            {
                            console.log(result);
                            var obj = JSON.parse(result);
                            if (obj.respuesta=='OK')
                                {
                                setTimeout(function (){
        	    						$('#modal-overlay').modal('hide')
        	    					},500);
								//$('#modal-overlay').modal('hide');
                                    console.log( "ARCHIVO CARGADO EN EL SERVIDOR" );
                                    procesa_archivo(obj.nombre_archivo);
								$('#customFile1').val('');
                                }
        	    			else
        	    				{
        	    				alertaGeneral("Error en la subida",obj.respuesta,"warning");
        	    				setTimeout(function (){
        	    						$('#modal-overlay').modal('hide')
        	    					},500);
        	    				}
                            }
                        });
                    }
        	    reader.readAsDataURL(input.files[0]);
          }
      
        function anular_carga(id)
        	{
        		console.log("id:"+id);
            
            
        		$.post("registros_ajax.php", {id:id,area:'ANULAR_CARGA'}, 
        	function(result) 
        		{
        			console.log(result);
        			var obj = JSON.parse(result);
                
        			if (obj.respuesta=='OK')
        				{
                            alertaGeneral("Carga anulada","¡Carga anulada exitosamente!","success");
        					listado();
        				}
        			else
        				{
                            alertaGeneral("Error anulación","Lo sentimos, ya no se puede anular la carga","error");
        				}
        		}); 
        	}
        function procesa_archivo(nombre_archivo)
        	{
        	$('#modal-overlay2').modal({backdrop: 'static',keyboard: false})
        	$('#modal-overlay2').modal('show')
            
        	document.getElementById('modal-overlay-texto2').innerHTML="Procesando archivo";
        	console.log("procesando "+nombre_archivo);	
            
            var intervalo = setInterval(
            function() 
                {
                $.ajax({url: 'carga_archivos_walmart_venta_progreso.php',
                      success: function(data) 
                          {
        					  var obj = JSON.parse(data);
                        	console.log(data)
                          if(obj.progress<100 && obj.progress>=0)
                              {
                            
                                console.log(obj.progress);
                                console.log(obj.progresstime);
                                console.log(obj.parametro);
                                if (obj.progress==''){obj.progress=0;}
                                if(obj.progresstime<60){
                                    var segundos= Math.round(obj.progresstime);
                                    document.getElementById('tiempo').innerHTML="Tiempo restante: "+segundos+" segundos.";
                                }
                                else if(obj.progresstime>=60){
                                    var minutos= (obj.progresstime/60).toFixed(1);
                                    document.getElementById('tiempo').innerHTML="Tiempo restante: "+minutos+" minutos.";
                                }
                                
                                document.getElementById('modal-overlay-texto2').innerHTML="Procesando archivo "+obj.progress+"% ";
        					    document.getElementById('parametros').innerHTML="Parametros:"+obj.parametro;
                            	console.log('Repeat:'+obj.progress);
                              } 
                          else 
                              {
                                console.log('End');
                              }
                          }
                      });
                }, 2000);
            $.post("carga_archivos_venta_ajax.php",{nombre_archivo:nombre_archivo,area:'PROCESA_ARCHIVO'},
            function(result) 
                {
                console.log("###3"+result+"#####");
                var obj = JSON.parse(result);
                
        		if (obj.errores!='')
        				{
        					document.getElementById('listado_errores').innerHTML=obj.errores;
        					clearInterval(intervalo);	
        					$('#modal-overlay2').modal('hide')
        				}
                if (obj.respuesta=='OK')
                    {
						console.log("ok")
						clearInterval(intervalo);	
						$.post("carga_archivos_progreso.php",{limpiar_sesion:'OK'})
						alertaGeneral("Archivo cargado","¡Archivo cargado exitosamente!","success");


						setTimeout(function (){
							$('#modal-overlay2').modal('hide')
							var inputfile=document.getElementById('customFile1').file;
							var clone = inputfile.clone();
							clone.value = '';
							inputfile.replaceWith(clone);
							//document.getElementById('modal-warning-texto2').innerHTML=obj.respuesta;
						},500);
						listado();
						setTimeout(function (){
						$('#modal-overlay2').modal('hide')
						},500);
        			}
                else if (obj.respuesta=='BIG_DATA')
                    {
                    console.log("ok")
            		clearInterval(intervalo);	
                    setTimeout(function (){
        			procesa_archivo_big(nombre_archivo,obj.registro);
        			},1000);
        			}
                else
                    {
                    console.log("error:"+obj.respuesta)
                    alertaGeneral("Error inesperado","Se ha producido el siguiente error: "+obj.respuesta,"error");
        			setTimeout(function (){
        				$('#modal-overlay2').modal('hide')
        		    },500);
        			clearInterval(intervalo);	
              /*      $.post("maestro_formulas_progreso.php",{limpiar_sesion:'OK'})*/
                    }
                
                })
        	}
        
		function procesa_archivo_big(nombre_archivo,registro)
        	{
        	$('#modal-overlay2').modal({backdrop: 'static',keyboard: false})
        	$('#modal-overlay2').modal('show')
            
        	document.getElementById('modal-overlay-texto2').innerHTML="Procesando archivo";
        	console.log("procesando "+nombre_archivo+" registro:"+registro);	
            
            var intervalo = setInterval(
            function() 
                {
                $.ajax({url: 'carga_archivos_walmart_venta_progreso.php',
                      success: function(data) 
                          {
        					  var obj = JSON.parse(data);
                        
                          if(obj.progress<100 && obj.progress>=0)
                              {
                            
                                console.log(obj.progress);
                                console.log(obj.progresstime);
                                console.log(obj.parametro);
                                if (obj.progress==''){obj.progress=0;}
                                if(obj.progresstime<60){
                                    var segundos= Math.round(obj.progresstime);
                                    document.getElementById('tiempo').innerHTML="Tiempo restante: "+segundos+" segundos.";
                                }
                                else if(obj.progresstime>=60){
                                    var minutos= (obj.progresstime/60).toFixed(1);
                                    document.getElementById('tiempo').innerHTML="Tiempo restante: "+minutos+" minutos.";
                                }
                                
                                document.getElementById('modal-overlay-texto2').innerHTML="Procesando archivo "+obj.progress+"% ";
        					    document.getElementById('parametros').innerHTML="Parametros:"+obj.parametro;
                            	console.log('Repeat:'+obj.progress);
                              } 
                          else 
                              {
                                console.log('End');
                              }
                          }
                      });
                }, 2000);
            $.post("carga_archivos_venta_ajax.php",{nombre_archivo:nombre_archivo,registro:registro,area:'PROCESA_ARCHIVO'},
            function(result) 
                {
                console.log("###3"+result+"#####");
                var obj = JSON.parse(result);
                
        		if (obj.errores!='')
        				{
        					document.getElementById('listado_errores').innerHTML=obj.errores;
        					clearInterval(intervalo);	
        					$('#modal-overlay2').modal('hide')
        				}
                if (obj.respuesta=='OK')
                    {
                    console.log("ok")
                    clearInterval(intervalo);	
                    $.post("carga_archivos_progreso.php",{limpiar_sesion:'OK'})
                    alertaGeneral("Archivo cargado","¡Archivo cargado exitosamente!","success");

                    
        			setTimeout(function (){
        				$('#modal-overlay2').modal('hide')
        				//document.getElementById('modal-warning-texto2').innerHTML=obj.respuesta;
        		    },500);
        			listado();
        			setTimeout(function (){
        			$('#modal-overlay2').modal('hide')
        			},500);
        			}
                else if (obj.respuesta=='BIG_DATA')
                    {
                    console.log("ok")
                    clearInterval(intervalo);	
                    setTimeout(function (){
        			procesa_archivo_big(nombre_archivo,obj.registro);
        			},1000);
        			}
                
                else
                    {
                    console.log("error:"+obj.respuesta)
                    alertaGeneral("Error inesperado","Se ha producido el siguiente error: "+obj.respuesta,"error");
        			setTimeout(function (){
        				$('#modal-overlay2').modal('hide')
        		    },500);
        			clearInterval(intervalo);	
              /*      $.post("maestro_formulas_progreso.php",{limpiar_sesion:'OK'})*/
                    }
                
                })
        	}
        
        
        function cancelar_carga()
        	{
        		$.ajax({url: 'carga_archivos_progreso_cancelar.php',
                      success: function(data) 
                          {
                        
        					$('#modal-overlay2').modal('hide')
                        
                          }
                      });
                  
        	}
	</script>
    <style>
        .custom-file-input ~ .custom-file-label::after {
            content: "Buscar";
        }
    </style>
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
                                                <h5 class="card-title">Subir archivo.</h5>
                                                <div class="row mb-3">
                                                    <div class="col-1"></div>
                                                    <div class="col-10">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="customFile1" onChange="sube_archivo(this)">
                                                            <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-1"></div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h5 class="card-title">Errores.</h5>
                                                <div class="row mb-3">
                                                    <div class="col-1"></div>
                                                    <div class="col-10">
                                                        <div id="listado_errores" style="width: 100%; height: 350px; overflow-y: scroll; border: 2px solid #ccc; position: relative;"></div>
                                                    </div>
                                                    <div class="col-1"></div>
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
    </div>
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <!-- Incluir scripts. -->
    <?php include("scripts.php");?>

</body>

</html>


<!-- MODALES -->
<div class="modal fade" id="modal-overlay">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="modal-overlay-texto">Procesando Archivo</h4><i class="fas fa-2x fa-sync fa-spin"></i> 
            </div>
            <div class="modal-body">
                <p>Estamos procesando el archivo, por favor espere.&hellip;</p>
            </div>
		    <div class="modal-footer justify-content-center">

                <button type="button" class="btn btn-primary"  onClick="cancelar_carga()">Cerrar</button>
            </div>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-overlay2">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="modal-overlay-texto2">Procesando Archivo</h4><i class="fas fa-2x fa-sync fa-spin"></i> 
        </div>
        <div class="modal-body">
          <p>Estamos procesando el archivo, por favor espere.&hellip;</p>
          <p id="tiempo"></p>
          <p id="parametros"></p>
        </div>
		  <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-primary"  onClick="cancelar_carga()">Cerrar</button>
            </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<div class="modal fade" id="modal-warning">
        <div class="modal-dialog">
          <div class="modal-content bg-warning">
            <div class="modal-header">
              <h4 class="modal-title">Error de proceso</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="modal-warning-texto">One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cerrar</button>
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
              <p id="modal-success-texto">Archivo se ha cargado correctamente&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<div class="modal fade" id="modal-lg" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Componentes</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
             <table id="example2" class="table table-bordered table-striped">
							  <thead>
							  <tr>
								<th>KAM</th>
								<th>nombre KAM</th>
								<th>Cod. Solicitante</th>
								<th>Solicitante</th>
                                <th>Pagadpr</th>
                                <th>Destinatario</th>
                                <th>Pedido</th>
                                <th>Orden de compra</th>
                                <th>Entrega</th>
                                <th>Factura</th>
                                <th>Documento</th>
                                <th>Fec. Factura</th>
                                <th>Doc.SII</th>
                                <th>Motivo</th>
                                <th>Tipo de venta</th>
                                <th>SKU Petrizzio</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Q Facturado</th>
                                <th>Monto facturado</th>
                                <th>Estado</th>
                                <th>Ingresado por</th>
                                <th>Fecha de ingreso</th>
							  </tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div