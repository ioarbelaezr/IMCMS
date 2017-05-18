<?
	$p = consulta(sprintf("SELECT * FROM `usuarios_perfiles` WHERE `cloud` = %s",varSQL(__sistema)),true);
?>
<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12 text-center">Perfiles de usuario disponibles en el portal.<br><br></div>
</div>

<div  ng-app="perfiles" ng-controller="perfilesListado">
	<div>
		<ul class="list-group listaMenus">
			<li ng-repeat="perfil in listado" class="list-group-item">
				<span ng-show="perfil.p!=1" ng-click="eliminar(perfil)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
				<!--<span ng-show="perfil.p!=1" ng-click="eliminar(perfil)" style="background-color:green;" class="badge"><i class="fa fa-pencil" aria-hidden="true"></i></span>-->
				<span ng-show="perfil.p!=1" ng-click="" style="background-color:orange;" class="badge"><a href="/adm/admUsuarios/perfilPermisos/{{perfil.id}}/" style="color:white;"><i class="fa fa-key" aria-hidden="true"></i></a></span> 
				<span class="badge"><a style="color:white;" href="/adm/admUsuarios/usuarios/{{perfil.id}}/"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> 
				{{perfil.nombre}}
			</li>
		</ul>
	</div>

	<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-default" type="submit" ng-click="nuevo()">Crear nuevo perfil de usuarios</button>
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
	        <h4 class="modal-title" id="myModalLabel">Nuevo perfil de usuarios</h4>
	      </div>
	      <div class="modal-body">
	        
			  <div class="form-group">
			    <label for="exampleInputEmail1">Nombre perfil de usuarios:</label>
			    <input required ng-model="nombre" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
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
	var app = angular.module('perfiles',['ngBootbox']);
	app.controller('perfilesListado',function($scope,conexiones,$ngBootbox){
		$scope.listado = <?=($p==false)?'[]':json_encode($p['resultado']); ?>;
		$scope.nombre = '';
		$scope.nuevo = function(){
			$scope.nombre = '';
			$('#myModal').modal();
		}

		$scope.guardar = function(){
			var data = $.param({nombre:$scope.nombre});
			conexiones.enviarDatos(data,'/xadm/admUsuarios/nuevoPerfil/').then(function(res){
				$scope.listado.push({id:res.data.IDI,nombre:$scope.nombre,p:'0'});
				$('#myModal').modal('hide');
			});
		}

		$scope.eliminar = function(u){
			$ngBootbox.confirm('Seguro que deseas eliminar este perfil de usuario?').then(function(){
	        	var i = $scope.listado.indexOf(u);
				var data = $.param({id:u.id});
				conexiones.enviarDatos(data,'/xadm/admUsuarios/eliminarPerfil/').then(function(){
					$scope.listado.splice(i,1);
				});
	        },function(){
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

<style>.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}</style>