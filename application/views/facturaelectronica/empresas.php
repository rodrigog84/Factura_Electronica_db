									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="inicio.html">Inicio</a></li>
											<li class="active">Registro de Empresa</li>
										</ol>
									   </div>
								  <!--//sub-heard-part-->


										
										<form action="<?php echo base_url();?>facturaselectronicas/put_empresa" id="basicBootstrapForm" method="POST"  enctype="multipart/form-data">	



 					<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Registro de Empresa</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Rut</label>    
                                  <input type="text" name="rut" class="form-control" id="rut" placeholder="98.123.456-7" value="<?php echo $datosform['rut'];?>">
                            </div>  
                          </div>

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Raz&oacute;n Social</label>    
                                  <input type="text" name="razon_social" class="form-control" id="razon_social" placeholder="Razón Social" value="<?php echo $datosform['razon_social'];?>">
                            </div>  
                          </div>

                        </div>

  						<div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">C&oacute;digo Actividad</label>    
                                  <input type="text" name="cod_actividad" class="form-control" id="cod_actividad" placeholder="Código Actividad" value="<?php echo $datosform['cod_actividad'];?>">
                            </div>  
                          </div>

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Giro</label>    
                                  <input type="text" name="giro" class="form-control" id="giro" placeholder="Giro" value="<?php echo $datosform['giro'];?>">
                            </div>  
                          </div>

                        </div>                         


						<div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Direcci&oacute;n</label>    
                                  <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" value="<?php echo $datosform['direccion'];?>">
                            </div>  
                          </div>

						<div class='col-md-6'>
                            <div class="form-group">
                                <label for="nombre">Región</label> 
                                <select name="region" id="region" class="form-control">
                                	<option value="">Seleccione Regi&oacute;n</option>
									<?php foreach ($regiones as $region) { ?>
                                      <?php $regionselected = $region->id_region == $datosform['idregion'] ? "selected" : ""; ?>
                                      <option value="<?php echo $region->id_region;?>" <?php echo $regionselected;?> ><?php echo $region->nombre;?></option>
                                    <?php } ?>
								</select>
                            </div>
						</div>                          
							
                        </div>   



                      


                        <div class='row'>

							<div class='col-md-6'>
                            <div class="form-group">
                              
                              <label for="comuna">Comuna</label> 
                                <select name="comuna" id="comuna"  class="form-control">
                                  <option value="">Seleccione Comuna</option>
                                </select>
                                <input type="hidden" id="idcomuna"  value="<?php echo $datosform['idcomuna']; ?>">
                            </div> 
                          </div>                        	
                          <div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Tel&eacute;fono</label>    
                              <div class="input-group">
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>  
                                  <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Teléfono" value="<?php echo $datosform['telefono'];?>">
                              </div>
                            </div>  
                          </div>

                        </div>

                        <div class='row'>



						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Mail</label>    
                              <div class="input-group">
                                <span class="input-group-addon">@</span>
                                  <input type="text" name="mail" class="form-control" id="mail" placeholder="Mail" value="<?php echo $datosform['mail'];?>">
                              </div>
                            </div>  
                          </div>                        	
						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Fecha Resoluci&oacute;n</label>    

                              <div class="input-group">
                                  <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </div>
                                <input placeholder="Fecha Resoluci&oacute;n" class="form-control mask_date" id="fec_resolucion" name="fec_resolucion"   size="30" type="text" value="<?php echo $datosform['fec_resolucion'];?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                </div>                              
                            </div>  
                          </div>


                        </div>  

                        

                        <div class='row'>

							<div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Nº Resoluci&oacute;n</label>    
                                  <input type="text" name="nro_resolucion" class="form-control" id="nro_resolucion" placeholder="Nº Resolución" value="<?php echo $datosform['nro_resolucion'];?>" >
                            </div>  
                          </div>    


						<div class='col-md-6'>
                        <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Adjuntar Logo Empresa</label>
                                  <input type="file" id="logo" name="logo" accept="image/*">
                                </div>
                        </div>                                                	

                         

                        </div> 
                    	</div>

                        <div class="row">
							<div class='col-md-6'>
                            <div class="form-group">
                              <label for="caja">Logo Empresa</label>    
                              <div class="input-group">
                                <img src="<?php echo $datosform['logo'];?>" height="100" width="170"  >
                                <br><br>
                              </div>
                        	</div>   
 
                          </div>
                      </div> 

                        </div>
                           
                      <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;
                      </div>

                 
                  </div>

			</form>


<script>

$(document).ready(function() {



  function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}



$('#region').change(function(){

    if($(this).val() != ''){

      $.get("<?php echo base_url();?>admins/get_comunas/"+$(this).val(),function(data){
               // Limpiamos el select
                    $('#comuna option').remove();
                    var_json = $.parseJSON(data);
                    $('#comuna').append('<option value="">Seleccione Comuna</option>');
                    for(i=0;i<var_json.length;i++){
                      $('#comuna').append('<option value="' + var_json[i].idcomuna + '">' + var_json[i].nombre + '</option>');
                    }
                    $('#basicBootstrapForm').formValidation('revalidateField', 'comuna');
      });
      
    }
});

function checkRut(rut) {
    // Despejar Puntos
    var valor = rut.value.replace('.','');
    // Despejar Guión
    valor = valor.replace('-','');
    
    // Aislar Cuerpo y Dígito Verificador
    cuerpo = valor.slice(0,-1);
    dv = valor.slice(-1).toUpperCase();
    
    // Formatear RUN
    rut.value = cuerpo + '-'+ dv
    
    // Si no cumple con el mínimo ej. (n.nnn.nnn)
    if(cuerpo.length < 7) { rut.setCustomValidity("RUT Incompleto"); return false;}
    
    // Calcular Dígito Verificador
    suma = 0;
    multiplo = 2;
    
    // Para cada dígito del Cuerpo
    for(i=1;i<=cuerpo.length;i++) {
    
        // Obtener su Producto con el Múltiplo Correspondiente
        index = multiplo * valor.charAt(cuerpo.length - i);
        
        // Sumar al Contador General
        suma = suma + index;
        
        // Consolidar Múltiplo dentro del rango [2,7]
        if(multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
  
    }
    
    // Calcular Dígito Verificador en base al Módulo 11
    dvEsperado = 11 - (suma % 11);
    
    // Casos Especiales (0 y K)
    dv = (dv == 'K')?10:dv;
    dv = (dv == 0)?11:dv;
    
    // Validar que el Cuerpo coincide con su Dígito Verificador
    if(dvEsperado != dv) { rut.setCustomValidity("RUT Inválido"); return false; }
    
    // Si todo sale bien, eliminar errores (decretar que es válido)
    rut.setCustomValidity('');
}


FormValidation.Validator.validateRut = {
        validate: function(validator, $field, options) {
          var validador = true;
          $field.Rut();
          var rut = $field.val();
          var cleanRut = replaceAll(rut,".","");
          var cleanRut = replaceAll(cleanRut,"-","");
          if(VerificaRut(cleanRut)){
              return true;

          }else{
              return {
                  valid : false
              }

          }


        }
    };


function VerificaRut(rut) {
    if (rut.toString().trim() != '') {
      
        var caracteres = new Array();
        var serie = new Array(2, 3, 4, 5, 6, 7);
        var dig = rut.toString().substr(rut.toString().length - 1, 1);
        rut = rut.toString().substr(0, rut.toString().length - 1);
        for (var i = 0; i < rut.length; i++) {
            caracteres[i] = parseInt(rut.charAt((rut.length - (i + 1))));
        }
 
        var sumatoria = 0;
        var k = 0;
        var resto = 0;
 
        for (var j = 0; j < caracteres.length; j++) {
            if (k == 6) {
                k = 0;
            }
            sumatoria += parseInt(caracteres[j]) * parseInt(serie[k]);
            k++;
        }
 
        resto = sumatoria % 11;
        dv = 11 - resto;
 
        if (dv == 10) {
            dv = "K";
        }
        else if (dv == 11) {
            dv = 0;
        }

        if (dv.toString().trim().toUpperCase() == dig.toString().trim().toUpperCase())
            return true;
        else
            return false;
    }
    else {
        return false;
    }
  }

 if($('#region').val() != ''){
  $.get("<?php echo base_url();?>admins/get_comunas/"+$('#region').val(),function(data){
           // Limpiamos el select
                $('#comuna option').remove();
                var_json = $.parseJSON(data);
                $('#comuna').append('<option value="">Seleccione Comuna</option>');
                for(i=0;i<var_json.length;i++){
                  $('#comuna').append('<option value="' + var_json[i].idcomuna + '">' + var_json[i].nombre + '</option>');
                }


    			//console.log($('#idcomuna').val());
				$("#comuna").val($('#idcomuna').val());                     
  });
  // seleccionar comuna

}	

 $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            rut: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Rut Empresa es requerido'
                    },
                    stringLength: {
                        min: 0,
                        max: 12,
                        message: 'El largo del Rut es Incorrecto'
                    },
                    validateRut: {
                      message: 'Rut Incorrecto'
                    }

                }
            },

			razon_social: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Razo&oacute;n Social es requerida'
                    }
                }
            },


            cod_actividad: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'C&oacute;digo Actividad es requerida'
                    },
                   numeric: {
                        separator: '.',
                        message: 'C&oacute;digo Actividad s&oacute;lo puede contener n&uacute;meros'
                    }                     
                }
            },


			giro: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Giro es requerido'
                    }
                }
            },   


            direccion: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Direcci&oacute;n es requerida'
                    }
                }
            },

            region: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Regi&oacute;n es requerida'
                    }
                }
            },  

          comuna: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Comuna es requerida'
                    }
                }
            },       


            telefono: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tel&eacute;fono es requerido'
                    },
                   numeric: {
                        separator: '.',
                        message: 'Tel&eacute;fono s&oacute;lo puede contener n&uacute;meros'
                    }                      
                }
            },                             


            mail: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Email es requerido'
                    },                  
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }                    
                }
            },       

            fec_resolucion: {
                row: '.form-group',
                validators: {
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'El valor no es una fecha v&aacute;lida'
                    },
                    notEmpty: {
                        message: 'Fecha de Resoluci&oacute;n es requerido'
                    }                    
                }
            },     

            nro_resolucion: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Nro. Resoluci&oacute;n es requerido'
                    },
                   numeric: {
                        separator: '.',
                        message: 'Nro. Resoluci&oacute;n s&oacute;lo puede contener n&uacute;meros'
                    }                      
                }
            },          
            



        }
    });
    $(".mask_date").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});


})



</script>