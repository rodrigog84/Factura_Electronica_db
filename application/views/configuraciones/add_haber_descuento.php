<div class="graph-visual tables-main">
											
													<h3 class="inner-tittle two">Creación de Haberes y Descuentos Variables</h3>

													
														  <div class="graph">
														  <form id="basicBootstrapForm" action="<?php echo base_url();?>configuraciones/submit_haber_descuento" method="post">

															<div class="tables">
																<table class="table"> 
																	<thead> 
																		<tr>
																			<th>Tipo Hab/Des:</th> 
																			<th>Código:</th>
																			<th>Descripción</th> 
																		</tr> 
																	</thead> 
																	<tbody> 
																		<tr class="active" id="variable">
																			<td class="form-group">
																				<select name="tipo" id="tipo" class="tipo form-control">
																					<option value="">Seleccione.</option>
																					<option value="HABER">Haber</option>
																					<option value="DESCUENTO">Descuento</option>
																				</select>
																			</td>

																			<td class="form-group">
																				<input type="text" name="codigo" id="codigo" class="form-control codigo" id="codigo" placeholder="Código">
																			</td>

																			<td class="form-group">
																				<input type="text" name="descripcion" id="descripcion" class="descripcion  form-control" id="descripcion" placeholder="Descripción">
																			</td> 
																		</tr>  
																		
																	</tbody> 
																</table>

																<table class="table"> 
																	<thead> 
																		<tr>
																			<th>Tipo de Cálculo:</th> 
																			<th>Forma de Cálculo:</th> 
																		</tr> 
																	</thead> 
																	<tbody> 
																		<tr class="active" id="variable">
																			<td>
																				<div class="form-group">
																					<div class="col-sm-8">
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="tipocalculo" id="tc_calculado" value="calculado" > Calculado
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="tipocalculo" id="tc_informado" value="informado" > Informado
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="tipocalculo" id="tc_tramo" value="tramo" > Tramo
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="tipocalculo" id="tc_calc_inf" value="calculado_informado" > Cálculado/Informado
																							</label>
																						</div>
																					</div>
																				</div>
																			</td>

																			<td>
																				<div class="form-group">
																					<div class="col-sm-8">
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="formacalculo" name="formacalculo" id="fc_fijo" value="fijo"> Fijo
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="formacalculo" id="fc_proporcional"  value="proporcional" > Proporcional
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="formacalculo" id="fc_diario" value="diario"> Diario
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="radio" name="formacalculo" id="fc_ctacte"  value="cuentacorriente"> Cuenta Corriente
																							</label>
																						</div>
																					</div>
																				</div>
																			</td> 
																		</tr>  	
																	</tbody> 
																</table> 

																<table class="table"> 
																	<thead> 
																		<tr>
																			<th>Características:</th> 
																			<th></th>
																			<!--th>INE:</th--> 
																		</tr> 
																	</thead> 
																	<tbody> 
																		<tr class="active" id="variable">
																			<td>
																				<div class="form-group">
																					<div class="col-sm-8">
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="imponible" id="imponible" value="imponible"> Imponible
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="reajustable" id="reajustable" value="reajustable"> Reajustable
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="provision" id="provision" value="provision"> Provisión
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="embargable" id="embargable" value="embargable"> Embargable
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="gratificacion" id="gratificacion" value="gratificacion"> Gratificación
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="insoluto" id="insoluto" value="insoluto"> Insoluto
																						</div>
																					</div>
																				</div>
																			</td>

																			<td>
																				<div class="form-group">
																					<div class="col-sm-8">
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="ret_judicial" id="ret_judicial" value="ret_judicial"> Ret. Judicial
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="tributable"  id="tributable" value="tributable"> Tributable
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="jornada" id="jornada" value="jornada"> Jornada
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="finiquito" id="finiquito" value="finiquito"> Finiquito
																							</label>
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="contable" id="contable" value="contable"> Contable
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="sobregiro" id="sobregiro" value="sobregiro"> Sobregiro
																						</div>
																						<div class="radio block">
																							<label>
																								<input class="form-check-input" type="checkbox" name="liqminimo" id="liqminimo" value="liqminimo"> Liq.Minimo
																						</div>
																					</div>
																				</div>
																			</td>

																			<!--td>
																				<select name="selector1" id="selector1" class="form-control1">
																					<option>Seleccione.</option>
																					<option>Dolore, ab unde modi est!</option>
																					<option>Illum, fuga minus sit eaque.</option>
																					<option>Consequatur ducimus maiores voluptatum min</option>
																				</select>

																				<input type="text" name="" class="form-control" placeholder="Descripción">
																			</td--> 
																		</tr>  	
																	</tbody> 
																</table>

																<!--table class="table"> 
																	<thead> 
																		<tr>
																			<th>Empresa:</th> 
																			<th>Cuenta Contable:</th>
																			<th>Descripción:</th> 
																		</tr> 
																	</thead> 
																	<tbody> 
																		<tr class="active" id="variable">
																			<td>
																				<select name="selector1" id="selector1" class="form-control1">
																					<option>Seleccione.</option>
																					<option>Dolore, ab unde modi est!</option>
																					<option>Illum, fuga minus sit eaque.</option>
																					<option>Consequatur ducimus maiores voluptatum min</option>
																				</select>
																			</td>

																			<td>
																				<select name="selector1" id="selector1" class="form-control1">
																					<option>Seleccione.</option>
																					<option>Dolore, ab unde modi est!</option>
																					<option>Illum, fuga minus sit eaque.</option>
																					<option>Consequatur ducimus maiores voluptatum min</option>
																				</select>

																			</td>

																			<td>
																				<textarea class="form-control" placeholder="Descripción" ></textarea>
																			</td> 
																		</tr>  	
																	</tbody> 
																</table-->
																<br>
																<button type = "submit" class = "btn btn-info" id="comando">Guardar</button>
															</div>

														</form>
															
													</div>
											</div>


<script>



$(document).ready(function() {

$('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {

            tipo: {
                // The children's full name are inputs with class .childFullName
                // The field is placed inside .col-xs-6 div instead of .form-group
                selector: '.tipo',
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo es requerido'
                    },
                },

            },          
           
     		codigo: {
                selector: '.codigo',
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'C&oacute;digo Haber/Descuento es requerido'
                    },
                },

            }, 

     		descripcion: {
                selector: '.descripcion',
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Descripci&oacute;n es requerida'
                    },
                },

            }, 


     		tipocalculo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo C&aacute;lculo es requerido'
                    },
                },

            },   

   			formacalculo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Forma C&aacute;lculo es requerido'
                    },
                },

            },              
           
        }
    })
});



</script>											