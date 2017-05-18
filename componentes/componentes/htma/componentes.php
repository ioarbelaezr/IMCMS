<?
	$c = consulta(sprintf("SELECT * FROM `componentes_disponibles`"),true);
	//dump($c);
?>

<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12 text-center">Componentes disponibles en el cloud.<br><br></div>
</div>

<div ng-app="componentes" ng-controller="componentesListado">
	<ul class="list-group listaMenus">
		<li ng-repeat="componente in listado" class="list-group-item"> 
			<!--<span class="badge"><i class="fa fa-level-down" aria-hidden="true"></i></span>-->
			{{componente.componente}}
		</li>
	</ul>

	<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-default" type="submit" data-toggle="modal" data-target="#myModal">Crear un nuevo componente en el cloud</button>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Componente en el cloud</h4>
	      </div>
	      <form id="form_dissmiss" ng-submit="guardar()">
		      <div class="modal-body">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Nombre del componente:</label>
				    <input ng-model="nombre" required name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Carpeta de ubicación:</label>
				    <input ng-model="carpeta" required name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Carpeta de ubicacion del componente">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Función:</label>
				    <input ng-model="funcion" ng-disabled="carpeta==''" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Función">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Text 1:</label>
				    <input ng-model="t1" ng-disabled="funcion==''" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Text 1">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Text 2:</label>
				    <input ng-model="t2" ng-disabled="t1==''" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Text 2">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Text 3:</label>
				    <input ng-model="t3" ng-disabled="t2==''" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Text 3">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Text 4:</label>
				    <input ng-model="t4" ng-disabled="t3==''" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Text 4">
				  </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		        <button type="submit" class="btn btn-primary">Guardar y configurar</button>
		      </div>
	      </form>
	    </div>
	  </div>
	</div>

</div>

<script>
	var app = angular.module('componentes',[]);
	app.controller('componentesListado',function($scope,conexiones){
		$scope.listado = <?=($c==false)?'[]':json_encode(Encoding::toUTF8($c['resultado']))?>;
		$scope.carpeta = '';
		$scope.funcion = '';
		$scope.t1 = '';
		$scope.t2 = '';
		$scope.t3 = '';
		$scope.t4 = '';

		$scope.guardar = function(){
			var data = $.param({nombre:$scope.nombre,carpeta:$scope.carpeta,funcion:$scope.funcion,t1:$scope.t1,t2:$scope.t2,t3:$scope.t3,t4:$scope.t4});
			conexiones.enviarDatos(data,'/xadm/componentes/crearComponente/').then(function(res){
				alert(res.data);
			});
		}
	});

	//poder enviar y recibir datos al servidor
	app.factory("conexiones",function($http){
		return {
			enviarDatos : function(data,uri){
				return $http({
							method: 'POST',
						    url: uri,
						    data:data,
						    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
						}).success(function(response) {
							return response;
						});
			}
		}
	});
</script>

<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
</style>