<?php include("puerta_principal.php");?>
<html lang="es"><head>
    <?php include("scripts-header.php");?>
    <script>
        $(document).ready(function() {
            $("#act-dashboard").addClass("mm-active");
            $("#titulo-cabecera").text("Dashboard");
            $("#descripcion-cabecera").text("Reportería");
            //buscar();no activar
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
			
			
			
			
        });

        function actualizar_oc()
        {
            $.post("dashboard_ajax.php", {proceso:'ACT_OC'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                    if (obj.total_oc>=0)
                    {
                        //Total OC
                        $("#total_oc").text(obj.total_oc);
                        //OC Por Recibir 
						
                        $("#oc_conciliadas").text(obj.oc_conciliadas);
                        var oc_pr=obj.oc_conciliadas/obj.total_oc;
                        var round_oc_pr= oc_pr.toFixed(2);
						console.log('conciliada: '+round_oc_pr)

						$("#conciliadas_diferencia").text(obj.conciliadas_diferencia);
						var oc_conciliadas_diferencia=obj.conciliadas_diferencia/obj.total_oc;
                        var round_oc_conciliadas_diferencia= oc_conciliadas_diferencia.toFixed(2);
						console.log('conciliada con diferencia : '+round_oc_conciliadas_diferencia)
						
						$("#oc_con_diferencia").text(obj.oc_con_diferencia);
						var oc_con_diferencia=obj.oc_con_diferencia/obj.total_oc;
                        var round_oc_con_diferencia= oc_con_diferencia.toFixed(2);
						console.log('oc con diferencia : '+round_oc_con_diferencia)
						
						
	    				$("#esperando_b2b").text(obj.oc_esperando_b2b);
                        var oc_esperando_b2b=obj.oc_esperando_b2b/obj.total_oc;
                        var round_oc_esperando_b2b= oc_esperando_b2b.toFixed(2);
						console.log('esperando b2b : '+round_oc_esperando_b2b)
						
                        $('.circle-progress-1').circleProgress({
                                value: 1.0,
                                size: 46,
                                lineCap: 'round',
                                fill: {color: '#3ac47d'}
                        }).on('circle-animation-progress', function (event, progress, stepValue) {
                            $(this).find('small').html('<span>' + stepValue.toFixed(2).substr(2) + '<span>');
                        });
                        $('.circle-progress-2').circleProgress({
                            value: round_oc_pr,
                            size: 46,
                            lineCap: 'round',
                            fill: {color: '#da624a'}
                        }).on('circle-animation-progress', function (event, progress, stepValue) {
                            $(this).find('small').html('<span>' + stepValue.toFixed(2).substr(2) + '<span>');
                        });
                        $('.circle-progress-3').circleProgress({
                            value: round_oc_conciliadas_diferencia,
                            size: 46,
                            lineCap: 'round',
                            fill: {color: '#d92550'}
                        }).on('circle-animation-progress', function (event, progress, stepValue) {
                            $(this).find('small').html('<span>' + stepValue.toFixed(2).substr(2) + '<span>');
                        });
                        $('.circle-progress-4').circleProgress({
                            value: round_oc_con_diferencia,
                            size: 46,
                            lineCap: 'round',
                            fill: {color: '#f7b924'}
                        }).on('circle-animation-progress', function (event, progress, stepValue) {
                            $(this).find('small').html('<span>' + stepValue.toFixed(2).substr(2) + '<span>');
                        });
	    				$('.circle-progress-5').circleProgress({
                            value: round_oc_esperando_b2b,
                            size: 46,
                            lineCap: 'round',
                            fill: {color: '#6c757d'}
                        }).on('circle-animation-progress', function (event, progress, stepValue) {
                            $(this).find('small').html('<span>' + stepValue.toFixed(2).substr(2) + '<span>');
                        });
                    }    
                }
            );
        }
		function listado_datos() {
            $('#lista_oc').DataTable().clear().destroy();
            $('#lista_oc').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "dashboard_ranking_oc_dif.php",
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "order": [
                    [11, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-right",
                        "width": "10%"
                    },
                    {
                        "targets": 1,
                        "className": "text-left",
                        "width": "10%"
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                        "width": "8%"
                    },

                    {
                        "targets": 3,
                        "className": "text-right",
                        "width": "5%"
                    },

                    {
                        "targets": 4,
                        "className": "text-right",
                        "width": "8%"
                    },

                    {
                        "targets": 5,
                        "className": "text-right",
                        "width": "5%"
                    },
                    {
                        "targets": 6,
                        "className": "text-right",
                        "width": "8%"
                    },
                    {
                        "targets": 7,
                        "className": "text-right",
                        "width": "5%"
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                        "width": "8%"
                    },
                    {
                        "targets": 11,
                        "className": "text-right",
                        "width": "8%",
						"type": "num"
                    }
                ],
				"footerCallback": function (row, data, start, end, display) {
        				let api = this.api();
 
						// Remove the formatting to get integer data for summation
						let intVal = function (i) {
							return typeof i === 'string'
								? i.replace(/[\$,.]/g, '') * 1
								: typeof i === 'number'
								? i
								: 0;
						};
 
						// Total over all pages
						total = api
							.column(11)
							.data()
							.reduce((a, b) => intVal(a) + intVal(b), 0);

						// Total over this page
						pageTotal = api
							.column(11, { page: 'current' })
							.data()
							.reduce((a, b) => intVal(a) + intVal(b), 0);
 
							// Update footer
							valor= new Intl.NumberFormat('es-ES').format(pageTotal); 
							api.column(11).footer().innerHTML =
								'$' + valor + '';
						
						}

            });
			
			
	
			
        }
        
        function grafico_1y2()
        {
			console.log("############################## GRAFICO 1 Y 2 #####################################")
            $.post("dashboard_ajax.php", {proceso:'GRAFICO_1Y2'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                
						
				////// configuracion grafico general
				var options3 = {
							colors : ['#07165b', '#ADD500','#2480ff', '#00dc61'],
						
							chart: {
								height: 350,
								type: 'bar',
								toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_gr1.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
							},
							plotOptions: {
								bar: {
									horizontal: false,
									/*endingShape: 'rounded',
									columnWidth: '55%',*/
									borderRadius: 5,
									borderRadiusApplication: 'end',
									borderRadiusWhenStacked: 'last',
								},
							},
							dataLabels: {
								enabled: false
							},
							stroke: {
								show: true,
								width: 2,
								colors: ['transparent']
							},
							series: [{
								name: 'Total Diff Logistica',
								data: [obj.tot_dif_05, obj.tot_dif_530, obj.tot_dif_3060, obj.tot_dif_60mas]
							}],
							title: {
							text: 'Montos sin IVA'
							},
							xaxis: {
								categories: ['0 a 5', '5 a 30', '30 a 60', 'Más de 60'],
								tickPlacement: 'on'
							},
							yaxis: {
								title: {
									text: 'Total Diff Logistica'
								},
								labels: {
								formatter: function (val) {
										return "$ " + Intl.NumberFormat('es-ES').format(val);
									}
								}
							},
							fill: {
								opacity: 1

							},
							tooltip: {
								y: {
									formatter: function (val) {
										return "$ " + Intl.NumberFormat('es-ES').format(val);
									}
								}
							}
							
						};
				////// configuracion grafico general
				var chart3 = new ApexCharts(
						document.querySelector("#grafico_general"),
						options3
					);
				
				////// configuracion grafico general 2 financiero
				var options3b = {
							colors : ['#ADD500','#2480ff', '#00dc61'],
						
							chart: {
								height: 350,
								type: 'bar',
								toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_gr1b.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
							},
							plotOptions: {
								bar: {
									horizontal: false,
									/*endingShape: 'rounded',
									columnWidth: '55%',*/
									borderRadius: 5,
									borderRadiusApplication: 'end',
									borderRadiusWhenStacked: 'last',
								},
							},
							dataLabels: {
								enabled: false
							},
							stroke: {
								show: true,
								width: 2,
								colors: ['transparent']
							},
							series: [{
								name: 'Total Diff Financiera',
								data: [obj.tot_dif_05_fin, obj.tot_dif_530_fin, obj.tot_dif_3060_fin, obj.tot_dif_60mas_fin]
							}],
							title: {
							text: 'Montos con IVA'
							},
							xaxis: {
								categories: ['0 a 5', '5 a 30', '30 a 60', 'Más de 60'],
								tickPlacement: 'on'
							},
							yaxis: {
								title: {
									text: 'Total Diff Financiera'
								},
								labels: {
								formatter: function (val) {
										return "$ " + Intl.NumberFormat('es-ES').format(val);
									}
								}
							},
							fill: {
								opacity: 1

							},
							tooltip: {
								y: {
									formatter: function (val) {
										return "$ " + Intl.NumberFormat('es-ES').format(val);
									}
								}
							}
							
						};
				////// configuracion grafico general
				var chart3b = new ApexCharts(
						document.querySelector("#grafico_general2"),
						options3b
					);
				
				
				
					var options4 = {
						colors : ['#ADD500', '#0071ce','#0069B4', '#d70f18', '#64a70b'],
						chart: {
							height: 350,
							type: 'bar',
							stacked: true,
							toolbar:{
									show:true,
									offsetX: 20,
        							offsetY: 0,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_gr1.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
						},
						dataLabels:{enabled:false},
						plotOptions: {
							bar: {
								horizontal: false,
							},

						},
						stroke: {
							width: 1,
							colors: ['#fff']
						},
						series: [{
							name: 'Falabella',
							data: [obj.tot_dif_05_b2b['FALABELLA'], obj.tot_dif_530_b2b['FALABELLA'], obj.tot_dif_3060_b2b['FALABELLA'], obj.tot_dif_60mas_b2b['FALABELLA']]
						}, {
							name: 'Walmart',
							data: [obj.tot_dif_05_b2b['WALMART'], obj.tot_dif_530_b2b['WALMART'], obj.tot_dif_3060_b2b['WALMART'], obj.tot_dif_60mas_b2b['WALMART']]
						}, {
							name: 'Cencosud',
							data: [obj.tot_dif_05_b2b['CENCOSUD'], obj.tot_dif_530_b2b['CENCOSUD'], obj.tot_dif_3060_b2b['CENCOSUD'], obj.tot_dif_60mas_b2b['CENCOSUD']]
						}, {
							name: 'SMU',
							data: [obj.tot_dif_05_b2b['SMU'], obj.tot_dif_530_b2b['SMU'], obj.tot_dif_3060_b2b['SMU'], obj.tot_dif_60mas_b2b['SMU']]
						}, {
							name: 'Tottus',
							data: [obj.tot_dif_05_b2b['TOTTUS'], obj.tot_dif_530_b2b['TOTTUS'], obj.tot_dif_3060_b2b['TOTTUS'], obj.tot_dif_60mas_b2b['TOTTUS']]
						}],
						title: {
							text: 'Montos sin IVA'
						},
						xaxis: {
							categories: ['0 a 5', '5 a 30', '30 a 60','Más de 60']
						},
						yaxis: {
							title: {
								text: 'Logística'
							},
							labels: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}
						},
						tooltip: {
							y: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}
						},
						fill: {
							opacity: 1

						},

						legend: {
							position: 'top',
							horizontalAlign: 'left',
							offsetX: 40
						}
					};

				var chart4 = new ApexCharts(
						document.querySelector("#grafico_resumen"),
						options4
					);

				
					var options4b = {
						colors : ['#ADD500', '#0071ce','#0069B4', '#d70f18', '#64a70b'],
						chart: {
							height: 350,
							type: 'bar',
							stacked: true,
							toolbar:{
									show:true,
									offsetX: 20,
        							offsetY: 0,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_gr1b.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
						},
						dataLabels:{enabled:false},
						plotOptions: {
							bar: {
								horizontal: false,
							},

						},
						stroke: {
							width: 1,
							colors: ['#fff']
						},
						series: [{
							name: 'Falabella',
							data: [obj.tot_dif_05_b2b_fin['FALABELLA'], obj.tot_dif_530_b2b_fin['FALABELLA'], obj.tot_dif_3060_b2b_fin['FALABELLA'], obj.tot_dif_60mas_b2b_fin['FALABELLA']]
						}, {
							name: 'Walmart',
							data: [obj.tot_dif_05_b2b_fin['WALMART'], obj.tot_dif_530_b2b_fin['WALMART'], obj.tot_dif_3060_b2b_fin['WALMART'], obj.tot_dif_60mas_b2b_fin['WALMART']]
						}, {
							name: 'Cencosud',
							data: [obj.tot_dif_05_b2b_fin['CENCOSUD'], obj.tot_dif_530_b2b_fin['CENCOSUD'], obj.tot_dif_3060_b2b_fin['CENCOSUD'], obj.tot_dif_60mas_b2b_fin['CENCOSUD']]
						}, {
							name: 'SMU',
							data: [obj.tot_dif_05_b2b_fin['SMU'], obj.tot_dif_530_b2b_fin['SMU'], obj.tot_dif_3060_b2b_fin['SMU'], obj.tot_dif_60mas_b2b_fin['SMU']]
						}, {
							name: 'Tottus',
							data: [obj.tot_dif_05_b2b_fin['TOTTUS'], obj.tot_dif_530_b2b_fin['TOTTUS'], obj.tot_dif_3060_b2b_fin['TOTTUS'], obj.tot_dif_60mas_b2b_fin['TOTTUS']]
						}],
						title: {
							text: 'Montos con IVA'
						},
						xaxis: {
							categories: ['0 a 5', '5 a 30', '30 a 60','Más de 60']
						},
						yaxis: {
							title: {
								text: 'Financiero'
							},
							labels: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}
						},
						tooltip: {
							y: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}
						},
						fill: {
							opacity: 1

						},

						legend: {
							position: 'top',
							horizontalAlign: 'left',
							offsetX: 40
						}
					};

				var chart4b = new ApexCharts(
						document.querySelector("#grafico_resumen2"),
						options4b
					);

				setTimeout(function () {
						if (document.getElementById('grafico_general')) {
							chart3.render();
						}
						if (document.getElementById('grafico_general2')) {
							chart3b.render();
						}
						if (document.getElementById('grafico_resumen')) {
							chart4.render();
						}
						if (document.getElementById('grafico_resumen2')) {
							chart4b.render();
						}



					 }, 1000);
	
				
				
                }
            );
        }
       
        function grafico_3anual()
        {
			console.log("############################## GRAFICO 3 ANUAL #####################################")
            $.post("dashboard_ajax.php", {proceso:'GRAFICO_3ANUAL'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                	var options777 = {
						colors : ['#07165b', '#00dc61'],
						chart: {
							height: 397,
							type: 'bar',
							toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_gr3_a.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
						},
						series: [{
							name: 'Total Facturado',
							data: obj.total
						},{
							name: 'Notas de Crédito',
							data: obj.notas_credito
						}],
						dataLabels: {
								enabled: false
							},
						stroke: {
							width: [0, 0]
						},
						// labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						labels: obj.meses,
						xaxis: {
							type: 'text'
						},
						yaxis: {
							labels: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}}
					};

					var chart777 = new ApexCharts(
						document.querySelector("#grafico_historial"),
						options777
					);
					
					const chartDiv = document.querySelector('#grafico_historial_no_con');
					var options7772 = {
						colors : ['#00dc61','#FF4560'],
						chart: {
							height: 397,
							type: 'bar',
							toolbar:{
								show:false
							},
							events: {
								dataPointSelection: function(event, chartContext, config) {
 								console.log('dato:'+config.dataPointIndex)
									window.open("dashboard_link_xls.php?indice="+config.dataPointIndex,'_blank')
								}
							}
						},
						series: [{
							name: 'No Conciliado +',
							data: obj.no_con
						},{
							name: 'No Conciliado -',
							data: obj.no_con_neg
						}],
						dataLabels: {
								enabled: false
							},
						stroke: {
							width: 1
						},
						// labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						labels: obj.meses,
						xaxis: {
							type: 'text',
							
						},
						yaxis: {
							labels: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}}
					};

					var chart7772 = new ApexCharts(
						document.querySelector("#grafico_historial_no_con"),
						options7772
					);
					
					
				
					var options7773 = {
						colors : ['#00dc61'],
						chart: {
							height: 397,
							type: 'line',
							toolbar:{
								show:false
							}
						},
						series: [{
							name: 'No Conciliadas %',
							type: 'line',
							data: obj.esperando
						}],
						stroke: {
							width: 4
						},
						// labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						labels: obj.meses,
						xaxis: {
							type: 'text'
						},
						yaxis: {
							labels: {
								formatter: function (val) {
									return Intl.NumberFormat('es-ES').format(val) + "%";
								}
							}}
					};

					var chart7773 = new ApexCharts(
						document.querySelector("#grafico_historial_esp_b2b"),
						options7773
					);

					
					var options7774 = {
						colors : ['#07165b', '#00dc61'],
						chart: {
							height: 397,
							type: 'line',
							toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_gr3_b.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
						},
						series: [{
							name: 'Total Facturado',
							type: 'bar',
							data: obj.total
						},
								 {
							name: 'Conciliado con Diferencia',
							type: 'line',
							data: obj.no_con
						}],
						stroke: {
							width: [0,4]
						},
						// labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						labels: obj.meses,
						xaxis: {
							type: 'text'
						},
						yaxis: {
							labels: {
								formatter: function (val) {
									return "$ "+Intl.NumberFormat('es-ES').format(val);
								}
							}}

					};

					var chart7774 = new ApexCharts(
						document.querySelector("#grafico_historial_con_dif"),
						options7774
					);

				
			

				setTimeout(function () {
						if (document.getElementById('grafico_historial')) {
							chart777.render();
						}
						if (document.getElementById('grafico_historial_no_con')) {
							chart7772.render();
						}
						if (document.getElementById('grafico_historial_esp_b2b')) {
							chart7773.render();
						}
						if (document.getElementById('grafico_historial_con_dif')) {
							chart7774.render();
						}
						



					 }, 1000);
	
				
				
                }
            );
        }
       
  		function grafico_rechazos()
        {
			console.log("############################# GRAFICO RECHAZO GENERAL ###################################")
            $.post("dashboard_ajax.php", {proceso:'GRAFICO_RECHAZOS'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                
				
				var options5 = {
						colors : ['#012583','#2480ff', '#00dc61'],
						chart: {
							height: 350,
							type: 'bar',
							stacked: true,
							toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_rechazo_general.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
						},
						dataLabels:{enabled:false},
						plotOptions: {
							bar: {
								horizontal: true,
							},

						},
						stroke: {
							width: 1,
							colors: ['#fff']
						},
						series: [{
							name: 'Rechazos',
							data: obj.valor
						}],
						title: {
							text: ''
						},
						xaxis: {
							categories: obj.categoria,
							labels: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}
						},
						yaxis: {
							title: {
								text: ''
							}

						},
						tooltip: {
							y: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							},
							x: {
								formatter: function (val) {
									return val;
								}
							}
						},
						fill: {
							opacity: 1

						},

						legend: {
							position: 'top',
							horizontalAlign: 'left',
							offsetX: 40
						}
					};
				
				var chart5 = new ApexCharts(
						document.querySelector("#grafico_tipos_rechazos"),
						options5
					);

				setTimeout(function () {
						if (document.getElementById('grafico_tipos_rechazos')) {
								chart5.render();
							}



					 }, 1000);
	
				
				
                }
            );
        }
       
       
		
		function grafico_transporte()
        {
            console.log("############################# GRAFICO TRANSPORTE ###################################")
            $.post("dashboard_ajax.php", {proceso:'GRAFICO_TRANSPORTE'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                
				
				var options6 = {
					colors : ['#2480ff', '#00dc61'],
					chart: {
						height: 350,
						type: 'bar',
						stacked: true,
						toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_transporte.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
						
					},
					dataLabels:{enabled:false},
					plotOptions: {
						bar: {
							horizontal: true,
						},

					},
					stroke: {
						width: 1,
						colors: ['#fff']
					},
					series: [{
						name: 'Total',
						data: obj.valor
					}],
					title: {
						text: ''
					},
					xaxis: {
						categories: obj.transporte,
						labels: {
							formatter: function (val) {
								return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
						}
					},
					yaxis: {
						title: {
							text: undefined
						},

					},
					tooltip: {
						y: {
							formatter: function (val) {
								return "$ " + Intl.NumberFormat('es-ES').format(val);
							}
						},
							x: {
								formatter: function (val) {
									return val;
								}
							}
					},
					fill: {
						opacity: 1

					},

					legend: {
						position: 'top',
						horizontalAlign: 'left',
						offsetX: 40
					}
				};

				var chart6 = new ApexCharts(
					document.querySelector("#grafico_transportes"),
					options6
				);

				
				setTimeout(function () {
						if (document.getElementById('grafico_transportes')) {
								chart6.render();
							}



					 }, 1000);
	
				
				
                }
            );
        }
       
		function grafico_rechazos2()
        {
            console.log("############################# GRAFICO RECHAZO SKU ###################################")
            $.post("dashboard_ajax.php", {proceso:'GRAFICO_RECHAZOS2'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                
				
				var options5 = {
						colors : ['#00dc61'],
						chart: {
							height: 350,
							type: 'bar',
							stacked: true,
							toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_rechazo_sku.php","_blank")
												}
												}],
										reset:false
										
										
	

											}
										}
								//////
						},
						dataLabels:{enabled:false},
						plotOptions: {
							bar: {
								horizontal: true,
							},

						},
						stroke: {
							width: 1,
							colors: ['#fff']
						},
						series: [{
							name: 'Rechazos',
							data: obj.valor
						}],
						title: {
							text: ''
						},
						xaxis: {
							categories: obj.categoria,
							labels: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							}
						},
						yaxis: {
							title: {
								text: ''
							}

						},
						tooltip: {
							y: {
								formatter: function (val) {
									return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
							},
							x: {
								formatter: function (val) {
									return val;
								}
							}
						},
						fill: {
							opacity: 1

						},

						legend: {
							position: 'top',
							horizontalAlign: 'left',
							offsetX: 40
						}
					};
				
				var chart5 = new ApexCharts(
						document.querySelector("#grafico_tipos_rechazos2"),
						options5
					);

				setTimeout(function () {
						if (document.getElementById('grafico_tipos_rechazos2')) {
								chart5.render();
							}



					 }, 1000);
	
				
				
                }
            );
        }
       
       
		
		
		function grafico_choferes()
        {
            $.post("dashboard_ajax.php", {proceso:'GRAFICO_CHOFERES'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                
				
				var options7 = {
					colors : ['#00dc61'],
					chart: {
						height: 350,
						type: 'bar',
						stacked: true,
						toolbar:{
									show:true,
									tools: {
										download:true,
										selection:true,
										zoom:false,
										zoomin:false,
										zoomout:false,
										pan:false,
										customIcons: [{
												icon: '<img src="assets/images/icons-download-48.png" width="20">',
												index: 1,
												title: 'Download XLS',
												class: 'custom-icon',
												click: function (chart, options, e) {
												  console.log("clicked custom-icon")
													window.open("dashboard_choferes.php","_blank")
												}
												}],
										reset:false
										
										
	
											}
										}
								//////
					},
					dataLabels:{enabled:false},
					plotOptions: {
						bar: {
							horizontal: true,
						},

					},
					stroke: {
						width: 1,
						colors: ['#fff']
					},
					series: [{
						name: 'Total',
						data: obj.valor
					}],
					title: {
						text: ''
					},
					xaxis: {
						categories: obj.chofer,
						labels: {
							formatter: function (val) {
								return "$ " + Intl.NumberFormat('es-ES').format(val);
								}
						}
					},
					yaxis: {
						title: {
							text: undefined
						},

					},
					tooltip: {
						y: {
							formatter: function (val) {
								return "$ " + Intl.NumberFormat('es-ES').format(val);
							}
						},
							x: {
								formatter: function (val) {
									return val;
								}
							}
					},
					fill: {
						opacity: 1

					},

					legend: {
						position: 'top',
						horizontalAlign: 'left',
						offsetX: 40
					}
				};

				var chart7 = new ApexCharts(
					document.querySelector("#grafico_choferes"),
					options7
				);

				
				setTimeout(function () {
						if (document.getElementById('grafico_choferes')) {
								chart7.render();
							}



					 }, 1000);
	
				
				
                }
            );
        }
       
		
		
		function grafico_vista_casos()
        {
               console.log("############################# GRAFICO CASOS ###################################")
        	$.post("dashboard_ajax.php", {proceso:'GRAFICO_CASOS'}, 
            function(result) 
                {
                    console.log(result);
                    //Se transforma en una lista
                    var obj = JSON.parse(result);
                    //console.log(obj1);
                
				var randomScalingFactor = function () {
						return Math.round(Math.random() * 100);
					};
					var configPie = {
						type: 'pie',
						data: {
							datasets: [{
								data: [
									obj.cerrados,obj.abiertos
								],
								backgroundColor: [
									'#28a745',
									'#FF0033'
								],
								label: 'Dataset 1'
							}],
							labels: [
								'Cerrados',
								'Abiertos'

							]
						},
						options: {
							responsive: true
						}
					};	
				
				setTimeout(function () {
						// Pie
		if (document.getElementById('grafico_casos')) {
			var ctx2 = document.getElementById('grafico_casos').getContext('2d');
			window.myPie = new Chart(ctx2, configPie);
		}




					 }, 1000);
	
				
				
                }
            );
        }
       
		
		/*
		
        ###############################
        ###############################
                    FILTROS
        ###############################
        ###############################
        */
        function buscar(boton) 
        {
        	$('#modal-default').modal('show')
            var filtro = $("select#filtro").val();
            var listado = $("select#listado").val();
            var filtrar='';
        	var quitar_filtro = '';
        	if (boton=='filtrar'){var filtrar='filtrar';}
        	if (boton!='filtro'&&boton!=''){var quitar_filtro=boton;}
        	$.post("conciliacion_general_filtro.php", {boton:boton,filtro:filtro,listado:listado,filtrar:filtrar,quitar_filtro:quitar_filtro}, function(mensaje) {
        		$("#resultadoFiltro").html(mensaje); 
                //actualizar_oc();
        	 });
        }
        function busca_filtro() 
        {
            var filtro = $("select#filtro").val();
            if (filtro!='')
        	{
        	    $.post("conciliacion_general_ajax.php", {filtro: filtro}, 
        	    function(result) 
        	    {
        	    	//console.log(result);
        	    	var obj = JSON.parse(result);
        	    	if (obj.listado!=null)
        	    	{
        	    	    $('#listado').empty().append();
        	    	    for (i=0;i<obj.listado.length;i++)
        	    	    {
        	    	        $('#listado').append('<option value="'+obj.listado[i]+'">'+obj.listado[i]+'</option>');
        	    	    }
        	    	}
        	    }); 
        	}
        }	
		
		
		function nombreColaborador() 
        {
            var filtro = $("select#labor").val();
            if (filtro!='')
        	{
        	    $.post("conciliacion_general_ajax.php", {filtro: filtro}, 
        	    function(datos) 
        	    {
        	    	//console.log(result);
					console.log(datos)
        	    	var obj = JSON.parse(datos);
        	    	if (obj.listado!=null)
        	    	{
        	    	    $('#nom_colaborador').empty().append();
						$('#nom_colaborador').append('<option value="">-----</option>')
        	    	    for (i=0;i<obj.listado.length;i++)
        	    	    {
        	    	        $('#nom_colaborador').append('<option value="'+obj.listado[i]+'">'+obj.listado[i]+'</option>');
        	    	    }
        	    	}
        	    }); 
        	}
        }	
		
		function mostrar_oc(oc, diferencia)
	        {
	        	var oc= oc;
				var diferencia= diferencia;
                detalle_oc(oc,diferencia);
				console.log("ESTA ES LA DIFERENCIA; " + diferencia);
                $('#modal_detalles_oc').modal('show');
	        }
            function mostrar_factura_oc(oc,b2b)
	        {
	        	var oc= oc;
				console.log(oc+'  '+b2b)
                detalle_factura(oc,'');
                $('#modal_factura_oc').modal('show');
				document.getElementById('oc_fact').value=oc;
				document.getElementById('b2b_fact').value=b2b;
				
				
	        }
            function mostrar_b2b_oc(oc)
	        {
	        	var oc= oc;
                detalle_b2b(oc);
                $('#modal_b2b_oc').modal('show');
	        }
            function mostrar_nc_oc(oc)
	        {
	        	var oc= oc;
                detalle_nc(oc);
                $('#modal_nc_oc').modal('show');
	        }
            function mostrar_resumen_oc(oc)
	        {
	        	var oc= oc;
                detalle_resumen(oc,'');
                $('#modal_resumen_oc').modal('show');
	        }
            function mostrar_r_factura_oc(oc,filtro_factura)
	        {
	        	var oc= oc;
                var filtro_factura= filtro_factura;
                detalle_factura(oc,filtro_factura);
                $('#modal_factura_oc').modal('show');
	        }
            function mostrar_historial_oc(oc)
	        {
	        	var oc= oc;
                detalle_historial(oc);
                $('#modal_historial_oc').modal('show');
	        }
		 function detalle_oc(oc,diferencia)
        {
            var oc=oc;
			 var diferencia=diferencia;
			 console.log('--------------------esta es la orden de compra para el resumen: ' + oc)
            $('#table_modal').DataTable().clear().destroy();
            $('#table_modal').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "conciliacion_general_detalle_datatable.php?oc="+oc+"&diferencia="+diferencia,
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
                        "width": "5%"
                    },
                    {
                        "targets": 1, // your case first column
                        "className": "text-left",
						"render": function ( data, type, row ) {
									return data.substr( 0, 20 ) + '...'},
                        "width": "13%"
                    },
                    {
                        "targets": 2, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 3, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 4, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 5, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 6, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 7, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 8, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    },
                    {
                        "targets": 9, // your case first column
                        "className": "text-center",
                        "width": "3%"
                    }
                ],
            });		
        }
       
			function detalle_factura(oc,filtro_factura)
        {
            var oc=oc;
            var filtro_factura=filtro_factura;
            $('#table_modal_detalle').DataTable().clear().destroy();
            $('#table_modal_detalle').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "conciliacion_general_factura_datatable.php?oc="+oc+"&filtro_factura="+filtro_factura,
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
                        "width": "10%"
                    },
                    {
                        "targets": 1, // your case first column
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 2, // your case first column
                        "className": "text-left",
                        "width": "60%"
                    },
                    {
                        "targets": 3, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 4, // your case first column
                        "className": "text-right",
                        "width": "15%"
                    }
                ],
            });		
        }
        function detalle_b2b(oc)
        {
            var oc=oc;
            $('#table_modal_b2b').DataTable().clear().destroy();
            $('#table_modal_b2b').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "conciliacion_general_b2b_datatable.php?oc="+oc,
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
                        "width": "10%"
                    },
                    {
                        "targets": 1, // your case first column
                        "className": "text-left",
                        "width": "70%"
                    },
                    {
                        "targets": 2, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 3, // your case first column
                        "className": "text-right",
                        "width": "15%"
                    }
                ],
            });		
        }
        function detalle_nc(oc)
        {
            var oc=oc;
            $('#table_modal_nc').DataTable().clear().destroy();
            $('#table_modal_nc').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "conciliacion_general_nc_datatable.php?oc="+oc,
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
                        "width": "10%"
                    },
                    {
                        "targets": 1, // your case first column
                        "className": "text-center",
                        "width": "10%"
                    },
                    {
                        "targets": 2, // your case first column
                        "className": "text-left",
                        "width": "60%"
                    },
                    {
                        "targets": 3, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 4, // your case first column
                        "className": "text-right",
                        "width": "15%"
                    }
                ],
            });		
        }
        function detalle_resumen(oc)
        {
            var oc=oc;
          
            $('#table_modal_resumen').DataTable().clear().destroy();
            $('#table_modal_resumen').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "conciliacion_general_resumen_datatable.php?oc="+oc,
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
                        "width": "5%"
                    },
                    {
                        "targets": 1, // your case first column
                        "className": "text-left",
						"render": function ( data, type, row ) {
									return data.substr( 0, 20 ) + '...'},
                        "width": "10%"
                    },
                    {
                        "targets": 2, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 3, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 4, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 5, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 6, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 7, // your case first column
                        "className": "text-center",
                        "width": "5%"
                    }
                ],
            });		
        }
        function detalle_historial(oc) {
            var oc=oc;
            $('#table_modal_historial').DataTable().clear().destroy();
            $('#table_modal_historial').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "conciliacion_general_historial_datatable.php?oc="+oc,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [
                    {
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    {
                        "targets": 1,
                        "className": "text-left",
                        "width": "25%"
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                        "width": "5%"
                    },

                    {
                        "targets": 3,
                        "className": "text-right",
                        "width": "10%"
                    },

                    {
                        "targets": 4,
                        "className": "text-center",
                        "width": "5%"
                    },

                    {
                        "targets": 5,
                        "className": "text-right",
                        "width": "10%"
                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                        "width": "5%"
                    },
                    {
                        "targets": 7,
                        "className": "text-right",
                        "width": "10%"
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                        "width": "15%"
                    }
                ],
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
        small{
            font-size:65%!important;
        }
        .click{
            transition: font-weight .3s;
            cursor: pointer;
        }
        .click:hover{
            font-weight: bold;
        }
        .modal-larga{
            max-width: 1000px!important;
        }
    </style>
    <title>Gungastore</title>
</head>
<body>
    <div class="app-container app-theme-gray">
        <div class="app-main">
            <?php include("sidebar-header.php");?>
            <div class="app-inner-layout app-inner-layout-page">
                <div class="app-inner-layout__content">
                    <div class="tab-content">
                        <div class="container-fluid">
                        <!--<div class="form-group row col-lg-12">	
					        <label for="filtro">Buscar por:</label>
					        <div class="col-lg-2">
					            <select name="filtro" id="filtro" onChange="busca_filtro();" class="select2 mb-2 form-control-sm form-control" style="width: 100%;" >
					            	<option value="" selected disabled> Seleccione una opción</option>
					            	<option value="B2B">B2B</option>
					            </select>
                            </div>
					        <div class="col-lg-2">
					            <select name="listado" id="listado" class="select2 mb-2 form-control-sm form-control" style="width: 100%;">
                                    <option value="" selected disabled> Seleccione una opción</option>
					            </select>
                            </div>
					        <div class="col-lg-2">
					            <input type="button" name="filtrar" id="filtrar" value="Filtrar" class="boton boton--verde mb-2 form-control-sm form-control" onClick="buscar('filtrar')">
					        </div>
                            <div class="col-lg-12" id="resultadoFiltro"></div>
						</div>-->
                            <div class="row">
								<div class="btn-actions-pane-left col-md-4">
                                    <div role="group"
                                         class="btn-group-sm nav btn-group">
                                        <a data-toggle="tab" href="#tab-eg1-0"
                                           class="active btn btn-primary">Vista Logística</a>
                                        <a data-toggle="tab" href="#tab-eg1-1"
                                           class="btn" style="background-color: #ADD500;">Vista Financiera</a>
                                    </div>
                                </div>
								<div class="tab-content col-12">
                                <div class="tab-pane" id="tab-eg1-0" role="tabpanel">
									<div class="row">
										<div class="col-md-6">
											<div class="main-card mb-3 card">
												<div class="card-body">
													<h5 class="card-title">Resumen No Conciliado General</h5>
														<div id="grafico_general"></div>

												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="main-card mb-3 card">
												<div class="card-body">
													<h5 class="card-title">Resumen No Conciliado por Retail</h5>
													<div id="grafico_resumen"></div>

												</div>
											</div>
										</div>
									</div>	
                                </div>
                                <div class="tab-pane active" id="tab-eg1-1" role="tabpanel">
									<div class="row">
										<div class="col-md-6">
											<div class="main-card mb-3 card">
												<div class="card-body">
													<h5 class="card-title">Resumen No Conciliado General</h5>
														<div id="grafico_general2"></div>

												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="main-card mb-3 card">
												<div class="card-body">
													<h5 class="card-title">Resumen No Conciliado por Retail</h5>
													<div id="grafico_resumen2"></div>

												</div>
											</div>
										</div>
									</div>	
                                </div>
                                </div>			
                                
                            </div>
							<!-- ****************DATATABLE DE LA LISTA DE OC*************** -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Resumen 20 OC plazo vencido con mayores diferencias</h5>
                                            <table  id="lista_oc"
                                                class="table table-hover table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Orden Compra</th>
                                                        <th>Cadena</th>
                                                        <th>Fecha</th>
                                                        <th>Fact. (Uni)</th>
                                                        <th>Fact. ($$)</th>
                                                        <th>NC    (Uni)</th>
                                                        <th>NC    ($$)</th>
                                                        <th>Recepción (Uni)</th>
                                                        <th>Recepción ($$)</th>
                                                        <th>Estado</th>
												     	<th></th>
                                               			<th>Dif ($$)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Acá van los datos. -->
                                                </tbody>
												<tfoot>
													<tr>
														<th colspan="11" style="text-align:right !important">Total:</th>
														<th></th>
													</tr>
												</tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
							<div class="row">
                                <div class="col-sm-12 col-lg-12">
                                    <div class="mb-3 card">
                                        <div class="card-header-tab card-header">
                                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                                Resumen histórico de No Conciliados
											</div>
                                        </div>
                                        <div class="p-0 card-body">
                                            <div class="p-1 slick-slider mx-auto">
                                                <div class="slick-slider">
                                                    <div>
                                                        <div class="widget-chart widget-chart2 text-left p-0">
                                                            <div class="widget-chat-wrapper-outer">
                                                                <div class="widget-chart-content widget-chart-content-lg pb-0">
                                                                    <div class="widget-chart-flex">
                                                                        <div class="widget-title text-muted text-uppercase">
                                                                            Resumen de Diferencias OC No Conciliadas
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div>
                                                                                <small class="opacity-3 pr-1">
                                                                                    $
                                                                                </small>
                                                                                <span>629</span>
                                                                                <span class="text-primary pl-3">
                                                            <i class="fa fa-angle-down"></i>
                                                        </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>-->
                                                                </div>
                                                                <div class="widget-chart-wrapper he-auto opacity-10 m-0">
                                                                      <div id="grafico_historial_no_con"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
														<div class="widget-chart widget-chart2 text-left p-0">
                                                            <div class="widget-chat-wrapper-outer">
                                                                <div class="widget-chart-content widget-chart-content-lg pb-0">
                                                                    <div class="widget-chart-flex">
                                                                        <div class="widget-title text-muted text-uppercase">
                                                                            OC Conciliadas con diferencia
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div>
                                                                                <span class="text-warning">34</span>
                                                                            </div>
                                                                            <div class="widget-title ml-2 font-size-lg font-weight-normal text-dark">
                                                                                <span class="opacity-5 text-muted pl-2 pr-1">5%</span>
                                                                                increase
                                                                            </div>
                                                                        </div>
                                                                    </div>-->
                                                                </div>
                                                                <div class="widget-chart-wrapper he-auto opacity-10 m-0">
                                                                     <div id="grafico_historial_con_dif"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
													<div>
                                                        <div class="widget-chart widget-chart2 text-left p-0">
                                                            <div class="widget-chat-wrapper-outer">
                                                                <div class="widget-chart-content widget-chart-content-lg pb-0">
                                                                    <div class="widget-chart-flex">
                                                                        <div class="widget-title text-muted text-uppercase">
                                                                            Resumen de Facturación Mensual según OC
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div>
                                                                <span class="opacity-10 text-success pr-2">
                                                                    <i class="fa fa-angle-up"></i>
                                                                </span>
                                                                                <span>78</span>
                                                                                <small class="opacity-5 pl-1">
                                                                                    %
                                                                                </small>
                                                                            </div>
                                                                            <div class="widget-title ml-2 font-size-lg font-weight-normal text-muted">
                                                                                <span class="text-success pl-2">+14</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>-->
                                                                </div>
                                                                <div class="widget-chart-wrapper he-auto opacity-10 m-0">
                                                                           <div id="grafico_historial"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="widget-chart widget-chart2 text-left p-0">
                                                            <div class="widget-chat-wrapper-outer">
                                                                <div class="widget-chart-content widget-chart-content-lg pb-0">
                                                                    <div class="widget-chart-flex">
                                                                        <div class="widget-title text-muted text-uppercase">
                                                                            Resumen de Diferencias OC No Conciliadas en %
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="widget-numbers">
                                                                        <div class="widget-chart-flex">
                                                                            <div>
                                                                                <span class="text-warning">34</span>
                                                                            </div>
                                                                            <div class="widget-title ml-2 font-size-lg font-weight-normal text-dark">
                                                                                <span class="opacity-5 text-muted pl-2 pr-1">5%</span>
                                                                                increase
                                                                            </div>
                                                                        </div>
                                                                    </div>-->
                                                                </div>
                                                                <div class="widget-chart-wrapper he-auto opacity-10 m-0">
                                                                     <div id="grafico_historial_esp_b2b"></div>
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
                            
							
							<div class="row">

                                <div class="col-md-6">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Tipos de Rechazo General</h5>
                                            <div id="grafico_tipos_rechazos"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Tipos de Rechazo por Sku</h5>
                                            <div id="grafico_tipos_rechazos2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Empresa Transporte</h5>
                                            <div id="grafico_transportes"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Choferes</h5>
                                            <div id="grafico_choferes"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-3">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Casos</h5>
                                            <canvas id="grafico_casos"></canvas>
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
<script>
		// Radial homologacion
function actualiza_graf_hom(porcentaje)
	{
		console.log ("funcion grafico homologacion : "+porcentaje)
		var options_gh = {
			chart: {
				height: 350,
				type: 'radialBar',
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				radialBar: {
					startAngle: -135,
					endAngle: 225,
					hollow: {
						margin: 0,
						size: '70%',
						background: '#fff',
						image: undefined,
						imageOffsetX: 0,
						imageOffsetY: 0,
						position: 'front',
						dropShadow: {
							enabled: true,
							top: 3,
							left: 0,
							blur: 4,
							opacity: 0.24
						}
					},
					track: {
						background: '#fff',
						strokeWidth: '67%',
						margin: 0, // margin is in pixels
						dropShadow: {
							enabled: true,
							top: -3,
							left: 0,
							blur: 4,
							opacity: 0.35
						}
					},

					dataLabels: {
						showOn: 'always',
						name: {
							offsetY: -10,
							show: true,
							color: '#888',
							fontSize: '17px'
						},
						value: {
							formatter: function (val) {
								return parseInt(val)+'%';
							},
							color: '#111',
							fontSize: '36px',
							show: true,
						}
					}
				}
			},
			fill: {
				type: 'gradient',
				gradient: {
					shade: 'dark',
					type: 'horizontal',
					shadeIntensity: 0.5,
					gradientToColors: ['#ABE5A1'],
					inverseColors: true,
					opacityFrom: 1,
					opacityTo: 1,
					stops: [0, 100]
				}
			},
			series: [porcentaje],
			stroke: {
				lineCap: 'round'
			},
			labels: ['Porcentaje'],

	};

		var graf_hom = new ApexCharts
			(
				document.querySelector("#radial_homologacion"),
				options_gh
			);	
		 if (document.getElementById('radial_homologacion')) 
		 	{
				graf_hom.render();
			}		
		
	}
</script>
<div class="modal fade" id="modal-overlay2">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="modal-overlay-texto2">Procesando Archivo</h4><i class="fas fa-2x fa-sync fa-spin"></i> 
        </div>
        <div class="modal-body">
          <p>Estamos procesando el archivo, por favor espere.&hellip;</p>
          <p id="tiempo"></p>
        </div>
		  <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-primary"  onClick="cancelar_carga()">Cerrar</button>
        </div>
    </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade bd-example-modal-xl" id="modal_detalles_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Detalle de producto(s) no conciliado(s) </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Descripción</th>
                            <th>Factura (Uni.)</th>
							<th>NC (Uni.)</th>
                            <th>Recepción (Uni.)</th>
                            <th>Dif. (Uni.)</th>
							<th>Factura ($$)</th>
							<th>NC ($$)</th>
                            <th>Recepción ($$)</th>
                            <th>Dif. ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modal_b2b_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">B2B</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_b2b" class="table table-bordered table-striped">
                    <thead>
                        <tr>
						    <th>SKU</th>
                            <th>Descripción</th>
                            <th>Factura (Uni.)</th>
                            <th>Recepción (Uni.)</th>
							<th>Factura ($$)</th>
                            <th>Recepción ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modal_nc_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nota de Crédito (NC)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_nc" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>SKU</th>
                            <th>Descripción</th>
                            <th>NC (Uni)</th>
                            <th>NC ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="modal_resumen_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Resumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_resumen" class="table table-bordered table-striped">
                    <thead>
                        <tr>
							<th>Fact. SII</th>
							<th>Destinatario</th>
							<th>Fe#Factura</th>
							<th>NC</th>
							<th>Fact. (Uni)</th>
							<th>Fact. ($$)</th>
							<th>NC (Uni)</th>
							<th>NC ($$)</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modal_factura_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table id="table_modal_detalle" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>SKU</th>
                            <th>Descripción</th>
                            <th>Factura (Uni)</th>
                            <th>Factura ($$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg " id="modal_historial_oc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-larga">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Historial OC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="texto_modal">
                <table style="width: 100%;" id="table_modal_historial" class="table table-hover table-striped table-bordered display compact" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Orden Compra</th>
                            <th>Cadena</th>
                            <th>Factura (Uni)</th>
                            <th>Factura ($$)</th>
                            <th>NC (Uni)</th>
                            <th>NC ($$)</th>
                            <th>Recepción (Uni)</th>
                            <th>Recepción ($$)</th>
                            <th>Estado</th>
                            <th>Fecha Conciliación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Acá van los datos. -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--        MODAL HREF CASOS    -->
<div class="modal fade" id="modal_casos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ATENCIÓN!!!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Esta orden de compra ya tiene un caso asociado. ¿Desea ver el detalle? </p>
            </div>
            <div class="modal-footer">
  				<button type="button" style="margin-right: auto" class="btn btn-primary" name="btn_redireccion" id="btn_redireccion" >Sí, quiero </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
	









	
        	
$(document).ready(function() {

    setTimeout(function () {

		
		
	 }, 1000);
	
});	

</script>