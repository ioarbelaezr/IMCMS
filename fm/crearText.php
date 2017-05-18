<div>
	<form ng-submit="guardarArchivo()">
	  <div class="row">
	  	<div class="col-xs-9">
	  		<div class="form-group">
			    <label for="exampleInputEmail1">Nombre del archivo</label>
			    <input ng-model="archivo.nombre" type="text" class="form-control" id="exampleInputEmail1" placeholder="Nombre del archivo de texto">
		    </div>
	  	</div>
	  	<div class="col-xs-3">
	  		<div class="form-group">
			    <label for="exampleInputEmail1">Extensión</label>
			    <select class="form-control" ng-model="archivo.extension" id="">
			    	<option value=".txt">.txt</option>
			    	<option value=".html">.html</option>
			    	<option value=".htm">.htm</option>
			    	<option value=".css">.css</option>
			    	<option value=".js">.js</option>
			    	<option value=".csv">.csv</option>
			    </select>
		    </div>
	  	</div>
	  </div>
	  <div class="form-group">
	    <label for="exampleInputEmail1">Contenido del archivo</label>
	    <textarea rows="10" ng-model="archivo.contenido" class="form-control" name="texto"></textarea>
	  </div>
	  <div class="row">
	  	<div class="col-xs-12 text-right">
	  		<button  ng-click="cerrar()" type="button" class="btn btn-default">Cancelar</button>
	  		<button type="submit" class="btn btn-success">Guardar</button>
	  	</div>
	  </div>
	</form>
</div>