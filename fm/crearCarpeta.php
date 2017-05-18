<div>
	<form ng-submit="carpetaGuardar()">
	  <div class="form-group">
	    <label for="exampleInputEmail1">Nombre de la nueva carpeta</label>
	    <input ng-model="carpetaNombre" type="text" class="form-control" id="exampleInputEmail1" placeholder="Nombre de la carpeta">
	  </div>
	  <div class="row">
	  	<div class="col-xs-12 text-right">
	  		<input value="Cancelar" ng-click="cerrar()" type="button" class="btn btn-default"/>
	  		<button type="submit" class="btn btn-success">Guardar</button>
	  	</div>
	  </div>
	</form>
</div>