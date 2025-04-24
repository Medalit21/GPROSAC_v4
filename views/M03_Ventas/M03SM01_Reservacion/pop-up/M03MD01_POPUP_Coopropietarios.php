<div class="modal-dialog modal-sm modal-dialog-centered justify-content-center" role="document" style="width: 1300px;">

    <div class="modal-content" style="width: 1300px;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fa fa-list" aria-hidden="true"></i> Agregar Copropietarios</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-registro-success" id="btnGuardarTarea"><i class="fa fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body" style="width: 900px;">
			<input type="hidden" id="__ID_USER" value="<?php echo $variable; ?>">
            <input type="hidden" id="txtidReserva">
            <input type="hidden" id="txtidCliente">
            
            <label for="" class="label-texto">DATOS DEL CLIENTE</label>
            <div class="form-row separador"> 
                <div class="col-md">
                    <label for="" class="label-texto">Nro Documento</label>
                    <input type="text"  name="txtNroDocC" id="txtNroDocC" class="caja-texto" readonly>
                </div>
                <div class="col-md">
                    <label for="" class="label-texto">Apellidos y Nombres</label>
                    <input type="text"  name="txtApellidoNombreC" id="txtApellidoNombreC" class="caja-texto" readonly>
                </div>
                <div class="col-md">
                    <label for="" class="label-texto">Teléfono/Celular</label>
                    <input type="text"  name="txtTelefonoC" id="txtTelefonoC" class="caja-texto" readonly>
                </div>
                <div class="col-md">
                    <label for="" class="label-texto">Correo</label>
                    <input type="text"  name="txtCorreoC" id="txtCorreoC" class="caja-texto" readonly>
                </div>
            </div>
            
            <br>
            
            <label for="" class="label-texto">AGREGAR COPROPIETARIO</label>
            <div class="form-row separador"> 
                <div class="col-md">
                    <label for="" class="label-texto">Nro Documento</label>
                    <input type="text"  name="txtNroDocCop" id="txtNroDocCop" class="caja-texto" maxlength="20" placeholder="DNI/Carnet Ext./Otros">
                </div>
                <div class="col-md">
                    <label for="" class="label-texto">Apellidos</label>
                    <input type="text"  name="txtApellido" id="txtApellido" class="caja-texto" placeholder="Escriba aqui">
                </div>
				<div class="col-md">
                    <label for="" class="label-texto">Nombres</label>
                    <input type="text"  name="txtNombreC" id="txtNombreC" class="caja-texto" placeholder="Escriba aqui">
                </div>
                <div class="col-md">
                    <label for="" class="label-texto">Teléfono/Celular</label>
                    <input type="number" name="txtTelefonoCop" id="txtTelefonoCop" class="caja-texto" maxlength="9" placeholder="Ejm: 999999999">
                </div>
                <div class="col-md">
                    <label for="" class="label-texto">Correo</label>
                    <input type="text"  name="txtCorreoCop" id="txtCorreoCop" class="caja-texto" placeholder="Ejm: correo@hotmail.com">
                </div>
                <div class="col-md" >
                    <label class="col-md label-texto-sm">Documento <small>(DNI/Carnet Extr./Otros)</small></label>
                    <form class="col-md mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                        <div class="col-md" style="margin-left: -7px;">
                            <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                            <input type="file" id="documento" name="documento" accept=".pdf" class="caja-texto" style="width: 280px;">
                            <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <label for="" class="label-texto">Copropietarios Registrados</label>
                <div class="form-row">     
                    <div class="col-md-12">
                        <div class="table-responsive tamanio-tabla">
                            <table class="table table-striped table-bordered w-100" cellspacing="0"
                                id="TablaCopropietarios">
                                <thead class="cabecera">
                                    <tr>
                                        <th></th>
                                        <th>Nro Documento</th>
                                        <th>Apellidos y Nombres</th>
                                        <th>Teléfono/Celular</th>
                                        <th>Correo</th>
                                        <th>Adjunto</th>
                                    </tr>
                                </thead>
                                <tbody class="control-detalle">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>