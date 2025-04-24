<div class="row">
	<div class="col-md-12" id="contenido_registro" style="display:none;">
		<fieldset>
			<legend>Datos Usuario</legend>
			<div class="row" style="margin-top: -8px;" id="formularioRegistrarUsuario">
				<input type="hidden" id="txtUsuario" value="<?php echo $user_sesion; ?>">
				
				<div class="col-md-3">
					<label class="label-texto">Categoría <small id="cbxCargoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
					<select id="cbxCargo" class="cbx-texto">
						<option selected="true" value="" disabled="disabled">Seleccione...</option>
						<?php
						$cargo = new ControllerCategorias();
						$Vercargo = $cargo->VerCargo();
						foreach ($Vercargo as $carg) {
							?>
						<option value="<?php echo $carg['ID']; ?>"><?php echo $carg['Nombre']; ?></option>
						<?php }?>
					</select>
				</div>			
				
				<div class="col-md-2">
					<label class="label-texto">Nombre <small id="txtDatoUserHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
					<input id="txtDatoUser" type="text" class="caja-texto" placeholder="Usuario">
				</div>
				<div class="col-md-2">
					<label class="label-texto">Abreviatura <small id="txtDatoUserHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
					<input id="txtDatoUser" type="text" class="caja-texto" placeholder="Usuario">
				</div>
			
				<div class="col-md-2">
					<label class="label-texto">Estado</label>
					<select id="cbxEstado" class="cbx-texto">
						<option selected="true" value="1">ACTIVO</option>
						<option value="0">INACTIVO</option>
					</select>
				</div>
			
			</div>
		</fieldset>
	</div>
</div>	
