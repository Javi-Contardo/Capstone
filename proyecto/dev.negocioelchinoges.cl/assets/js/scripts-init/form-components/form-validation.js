$( document ).ready(function() {
	
    $( "#formRegistrarUsuario" ).validate( {
        rules: {
            //VALIDACIONES PARA FORMULARIO USUARIOS
            nombrec: "required",
            id_cliente:"required",
            labor:"required",
            clave: {
                required: true,
                minlength: 6
            },
            confirmar_clave: {
                required: true,
                minlength: 6,
                equalTo: "#clave"
            },
            correo: {
                required: true,
                email: true
            },
		},
        messages:{
            //MENSAJES PARA FORMULARIO USUARIOS
            nombrec:"Debes completar este campo.",
			id_cliente: "Debes completar este campo.",
            labor:"Seleccione una labor para el usuario.",
            correo:{
                required:"Debes completar este campo.",
                email:"Debes escribir un correo válido."
            },
            clave:{
                required:"Debes completar este campo.",
                minlength:"Tu contraseña debe ser de 6 caracteres de largo. "
            },
            confirmar_clave:{
                required:"Debes confirmar tu contraseña.",
                minlength:"Tu contraseña debe ser de 6 caracteres de largo.",
                equalTo:"Debes repetir la misma contraseña."
            }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            error.addClass( "invalid-feedback" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    } );
    $( "#formRegistrarCliente" ).validate( {
        rules: {
            //VALIDACIONES PARA FORMULARIO CLIENTES
            nombre_cont: "required",
            rut: "required",
            correo: {
                required: true,
                email: true
            },
            telefono: {
                required: false,
                minlength: 9,
                //maxlength: 10
            },
            direccion: "required",
            comuna: "required",
            giro: "required",
            nombre_fan: "required",
            razon_social: "required"   
		},
        messages:{
            //MENSAJES PARA FORMULARIO CLIENTES
            nombre_cont:"Debes completar este campo.",
            rut: "Debes completar este campo.",
            correo:{
                required:"Debes completar este campo.",
                email:"Debes escribir un correo válido."
            },
            telefono: {
                required: "Debes completar este campo.",
                minlength: "Tu teléfono debe contener 9 caracteres (Incluye el dígito verificador)."
                //maxlength: "Tu número debe tener como máximo 10 caracteres."
            },
            direccion: "Debes completar este campo.",
            comuna: "Debes completar este campo.",
            giro: "Debes completar este campo.",
            nombre_fan: "Debes completar este campo.",
            razon_social: "Debes completar este campo."		
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            error.addClass( "invalid-feedback" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    } );
    $( "#formCrearUsuario" ).validate( {
        rules: {
           rut:"required",
           nombrec:"required",
           correo: {
                required:true,
                email:true
           },
           nombre_fantasia:"required",
           giro:"required",
           razon_social:"required",
           clave: {
                required: true,
                minlength: 6
            },
            confirmar_clave: {
                required: true,
                minlength: 6,
                equalTo: "#clave"
            }

		},
        messages:{
            rut:"Debes completar este campo.",
            nombrec:"Debes completar este campo.",
            correo:{
                required:"Debes completar este campo.",
                email:"Debes escribir un correo válido."
            },
            nombre_fantasia:"Debes completar este campo.",
            giro:"Debes completar este campo.",
            razon_social:"Debes completar este campo.",
            clave:{
                required:"Debes completar este campo.",
                minlength:"Tu contraseña debe ser de 6 caracteres de largo. "
            },
            confirmar_clave:{
                required:"Debes confirmar tu contraseña.",
                minlength:"Tu contraseña debe ser de 6 caracteres de largo.",
                equalTo:"Debes repetir la misma contraseña."
            }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            error.addClass( "invalid-feedback" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    } );
    $( "#formRegistrarB2B" ).validate( {
        rules: {
            //VARIABLES DE FALABELLA
            b2b_falabella:"required",
            rut_empresa:"required",
            rut_usuario:"required",
            //
            //VARIABLES DE WALMART
            id_usuario:"required",
            //
            //VARIABLES DE CENCOSUD
            email_cencosud:"required",
            //

            //GLOBAL
            clave:"required",

        },
        messages:{
            //MENSAJES DE FALABELLA
            b2b_falabella:"Debes completar este campo.",
            rut_empresa:"Debes completar este campo.",
            rut_usuario:"Debes completar este campo.",
            //
            //MENSAJES DE FALABELLA
            id_usuario:"Debes completar este campo.",
            //
            //MENSAJES CENCOSUD
            email_cencosud:"Debes completar este campo.",
            //

            
            //GLOBAL
            clave:"Debes completar este campo.",

        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            error.addClass( "invalid-feedback" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });
    
});

