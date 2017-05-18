<?
	$sites = consulta("SELECT `id`,`folder`,`nombre`,`dominio`,`s`,`rol` FROM `clouds`",true);
	//dump($sites['resultado']);
?>
<div ng-app="paginas" ng-controller="paginasListado">
	<div class="row">
		<div style="color:#4C5561;font-size:22px;" class="col-xs-12 text-center">Listado de sitios instalados actualmente en el sistema.<br><br></div>
	</div>
	<div>
		<ul class="list-group listaMenus">
			<li ng-repeat="sitio in listado" class="list-group-item">
				<span ng-click="eliminar(sitio)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
				<span style="background-color:orange;" class="badge"><a style="color:white;" href="/adm/componentes/componentes_instalar/{{sitio.id}}/"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></a></span> <!--componentes disponibles en este sitio e instalar nuevo componente-->
				<span ng-click="editar(sitio)" class="badge"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> 
				{{sitio.nombre}} &nbsp;&nbsp; (<span style="color:gray;">{{sitio.dominio}}</span>)
			</li>
		</ul>
	</div>

	<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-default" type="submit" ng-click="sitio()">Instalar un nuevo sitio en el sistema</button>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	    <form ng-submit="guardar();" id="form_dissmiss">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Nuevo item</h4>
	      </div>
	      <div class="modal-body">
	        
			  <div class="form-group">
			    <label for="exampleInputEmail1">Nombre del sitio:</label>
			    <input required ng-model="nombre" name="nombre" type="text" class="form-control" id="exampleInputEmail1" placeholder="Nombre del sitio">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputEmail1">Dominio ó subdominio en el cual se servira el sitio (sin <b>www.</b>):</label>
			    <input type="text" required ng-model="dominio" class="form-control" placeholder="Dominio en el cual se servira el sitio">
			  </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	        <button type="submit" class="btn btn-primary">Guardar</button>
	      </div>
	      </form>
	    </div>
	  </div>
	</div>
</div>
<script>
	var app = angular.module('paginas',['ngBootbox']);
	app.controller('paginasListado',function($scope,$ngBootbox,conexiones){
		$scope.listado = <? echo ($sites==false)?'[]':json_encode(Encoding::toUTF8($sites['resultado'])); ?>;
		$scope.nombre  = '';
		$scope.dominio = '';
		$scope.id = false;
		$scope.tmp = '';
		$scope.sitio = function(){
			$scope.id = false;
			$scope.nombre  = '';
			$scope.dominio = '';
			$('#myModal').modal();
		}

		$scope.editar = function(item){
			$scope.tmp = item;
			$scope.id = item.id;
			$scope.nombre = item.nombre;
			$scope.dominio = item.dominio;
			$('#myModal').modal();
		}

		$scope.guardar = function(){
			var uri  = $scope.id?'/xadm/config/edtSite/':'/xadm/config/instalarSitio/';
			var data = $.param({id:$scope.id,nombre:$scope.nombre,dominio:$scope.dominio});
			conexiones.enviarDatos(data,uri).then(function(res){
				if($scope.id){
					$scope.tmp.dominio = $scope.dominio;
					$scope.tmp.nombre = $scope.nombre;
				}else{
					$scope.listado.push({id:res.data.IDI,nombre:$scope.nombre,dominio:$scope.dominio,folder:'sites/'+$scope.dominio+'/'});
				}
				$('#myModal').modal('hide');
			});
		}

		$scope.eliminar = function(item){
			$ngBootbox.confirm('Seguro que deseas eliminar '+item.nombre+' ('+item.dominio+') del cloud. Recuerda que se eliminaran todos los datos asociados a este sitio y no sera posible deshacer esta acción!').then(function(){
				var data = $.param({id:item.id,folder:item.folder});
				conexiones.enviarDatos(data,'/xadm/config/eliminarCloud/').then(function(){
					$scope.listado.splice($scope.listado.indexOf(item));
				});
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


<style>.triggerLM{cursor:pointer;}.triggerLM:hover{background-color: #E2EEF4;}.badge{font-size: 17px !important;cursor:pointer;}
</style>