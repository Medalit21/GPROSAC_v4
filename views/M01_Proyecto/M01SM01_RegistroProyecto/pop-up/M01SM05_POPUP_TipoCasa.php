<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="width: 900px; height: 585px; margin-left: 20%;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fa fa-edit" aria-hidden="true"></i> Registrar nuevo tipo de casa</span>
        </div>
        <div class="head-model cabecera-modal-accion text-left">
            <button class="btn btn-model-info" id="btnGuardarTipoCasa"><i class="fa fa-save"></i> Guardar</button>
            <button class="btn btn-model-info" id="btnNuevoTipoCasa"><i class="fa fa-sync"></i> Nuevo</button>
            <button class="btn btn-model-info" id="btnModificarTipoCasa" disabled><i class="fas fa-edit"></i> Modificar</button>
        </div>
        <div>
            <div class="p-campos-3 margen-pop-up" id="panel-options">

                <!-- SECCION DE DATOS DEL PROYECTO -->
                <div class="form-row" style="margin-top: 10px;">

                    <div class="col-md-5">
                        <fieldset>
                            <legend>Datos</legend>
                            <input id="txtidTipoCasa" type="text" class="caja-texto" hidden>
                            <div class="row">
                                <div class="col-md">
                                    <label class="label-texto">Nombre Tipo Casa <small id="txtNombreTipoCasaPopupHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                    <input id="txtNombreTipoCasaPop" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <label class="label-texto">Habitaciones <small id="txtNroHabitacionesHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                    <input id="txtNroHabitaciones" type="number" class="caja-texto" placeholder="Ejm: 4" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                </div>
                                <div class="col-md">   
                                    <label class="label-texto">Baño(s) <small id="txtNroBaniosHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                    <input id="txtNroBanios" type="number" class="caja-texto" placeholder="Ejm: 2" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-md">
                                    <label class="label-texto">Cocina <small id="txtNroCocinaHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                    <input id="txtNroCocina" type="number" class="caja-texto" placeholder="Ejm: 1" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                </div>
                                <div class="col-md">
                                    <label class="label-texto">Sala <small id="txtNroSalaHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                    <input id="txtNroSala" type="number" class="caja-texto" placeholder="Ejm: 1" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                </div>
                            </div>
                            <div class="row">
                                <form class="col-md-12" action="" method="POST" enctype="multipart/form-data">
                                    <div class="col-md">
                                        <label class="label-texto">Plano <small id="ficherosHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>   
                                        <input type="file" id="ficheros" name="ficheros" class="caja-texto" accept=".pdf">                                    
                                    </div>
                                </form> 
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <label class="label-texto">Descripcion <small id="txtAreaDescripcionHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                    <textarea class="caja-texto" name="" id="txtAreaDescripcion" cols="30" rows="10" maxlength="120"></textarea>
                                </div>
                            </div>

                        </fieldset>
                        <fieldset style="margin-top: 5px;">
                            <legend>Registrados</legend>
                            <div style="margin-top: -10px;">
                                <table class="table table-striped table-bordered" cellspacing="0" id="TablaRegTipoCasa" style="width: 100%;">
                                    <thead class="cabecera">
                                        <tr>
                                            <th></th>
                                            <th>NOMBRE</th>
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-7">
                        <fieldset>
                            <div id="my_pdf_viewer">

                            </div>
                        </fieldset>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
</div>