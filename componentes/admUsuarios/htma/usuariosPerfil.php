<?
	$p = consulta(sprintf("SELECT `nombre`,`p` FROM `usuarios_perfiles` WHERE `id` = %s",varSQL($_GET['text1'])),true);
	$u = consulta(sprintf("SELECT * FROM `login` WHERE `perfil` = %s AND `cloud` = %s",varSQL($_GET['text1']),varSQL(__sistema)),true);
?>
<div ng-app="usuarios" ng-controller="usuariosListado">
	<div class="row">
		<div style="font-size: 20px;color: #4C5561;" class="col-xs-12 text-left">
			<a href="/adm/admUsuarios/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Perfil de usuarios: &nbsp;<?=$p['resultado'][0]['nombre']; ?><br><br>
		</div>
	</div>
	
	<div>
		<div ng-show="listado.length==0" class="row">
			<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
				Aun no hay usuarios en este perfil.
			</div>
		</div>
		<ul class="list-group listaMenus">
			<li ng-repeat="usuario in listado" class="list-group-item">
				<span ng-show="(t==1&&listado.length>1)||t!=1" ng-click="eliminar(usuario)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
				<span ng-click="editar(usuario)" class="badge">
					<a style="color:white;" href="/adm/admUsuarios/actualizarPerfil/{{usuario.id}}/">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</a>
				</span> 
				{{usuario.nombres}} {{usuario.apellidos}}
			</li>
		</ul>
	</div>

	<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-default" type="submit" ng-click="nuevo()">Crear un nuevo usuario en este perfil</button>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Crear un nuevo usuario en este perfil</h4>
	      </div>
	      <form ng-submit="crear()" id="form_dissmiss">
		      <div class="modal-body">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Nombres:</label>
				    <input ng-model="nombres" required type="text" class="form-control" id="exampleInputEmail1" placeholder="Nombres">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Apellidos:</label>
				    <input ng-model="apellidos" required type="text" class="form-control" id="exampleInputEmail1" placeholder="Apellidos">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Email:</label>
				    <input ng-model="email" required type="text" class="form-control" id="exampleInputEmail1" placeholder="Email">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Telefono:</label>
				    <input ng-model="telefono" required type="text" class="form-control" id="exampleInputEmail1" placeholder="Telefono">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Contraseña:</label>
				    <input ng-model="contrasena"  type="password" class="form-control" id="exampleInputEmail1" placeholder="Contraseña">
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
	var app = angular.module('usuarios',['ngBootbox']);
	app.controller('usuariosListado',function($scope,conexiones,$ngBootbox){
		$scope.listado = <?=($u==false)?'[]':json_encode(Encoding::toUTF8($u['resultado'])); ?>;
		$scope.t       = '<?=$p['resultado'][0]['p']; ?>';

		$scope.nuevo = function(){
			$('#myModal').modal();
		}

		$scope.crear = function(){
			var data = $.param({nombres:$scope.nombres,apellidos:$scope.apellidos,email:$scope.email,telefono:$scope.telefono,contrasena:$scope.contrasena,perfil:<?=$_GET['text1']; ?>});
			//alert(data);
			conexiones.enviarDatos(data,'/xadm/admUsuarios/crearUsuario/').then(function(res){
				$('#myModal').modal('hide');
				$scope.listado.push({nombres:$scope.nombres,apellidos:$scope.apellidos,email:$scope.email,telefono:$scope.telefono,perfil:<?=$_GET['text1']; ?>,id:res.data.IDI});
			});
		}

		$scope.eliminar = function(u){
			$ngBootbox.confirm('Seguro que deseas eliminar el usuario seleccionado?').then(function(){
	        	var data = $.param({id:u.id});
				conexiones.enviarDatos(data,'/xadm/admUsuarios/eliminarUsuario/').then(function(res){
					$scope.listado.splice($scope.listado.indexOf(u),1);
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