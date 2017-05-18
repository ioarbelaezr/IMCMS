
<? $c = sprintf("SELECT `nombre` FROM `usuarios_perfiles` WHERE `id` = %s",varSQL($_GET['text1']));
	$n = consulta($c,true); ?>
<div ng-app="lm" ng-controller="lmAcciones">
	<div class="row">
		<div style="font-size: 20px;color: #4C5561;" class="col-xs-12 text-left">
			<a href="/adm/admUsuarios/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Perfil de usuarios: &nbsp;<?=$n['resultado'][0]['nombre']; ?><br><br>
		</div>
	</div>
	<div ng-show="menus.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			No hay componentes instalados en este sitio!
		</div>
	</div>
	<ul class="list-group listaMenus">
		<li ng-class="{'permitido':class(modulo.id),'noPermitido':!class(modulo.id)}" ng-repeat="modulo in modulos" class="list-group-item">
			<span style="background-color:transparent;" class="badge">
				<input ng-model="con[modulo.id]" ng-change="actualizar(modulo.id);" type="checkbox">
			</span>
			{{modulo.nombre}}
		</li>
	</ul>
</div>

<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
	/*ocultar el elemento cojido para mover*/
	.listaMenus .dndDraggingSource {display: none;}
	.listaMenus .dndPlaceholder {
    display: block;
    background-color: #ddd;
    min-height: 42px;}
    .noPermitido{background-color: #ffe5e5;}
    .permitido{background: #c4ffc4;}
}
</style>

<?
	$modulos  = consulta(sprintf("SELECT * FROM `componentes_instalados` WHERE `cloud` = %s ",varSQL(__sistema)),true);
	$permisos = consulta(sprintf("SELECT `id_componente` FROM `usuarios_perfiles_permisos` WHERE `id_perfil` = %s",varSQL($_GET['text1'])),true);
?>



<!--Necesary javascript for performance-->
<script>	
var app = angular.module('lm',[]);
app.controller('lmAcciones',function($scope,conexiones){
	$scope.modulos    = <?=($modulos==false)?'[]':json_encode(Encoding::toUTF8($modulos['resultado'])); ?>;
	$scope.permitidos = <?=($permisos==false)?'[]':json_encode(Encoding::toUTF8($permisos['resultado'])); ?>;
	$scope.con   = new Array;	
	$scope.perfil= '<?=$_GET['text1']; ?>'; 
	$scope.actualizar = function(id){
		if($scope.con[id]){
			var ac = 1;
		}else{
			var ac = 0;
		}
		var data = $.param({accion:ac,perfil:$scope.perfil,componente:id});
		conexiones.enviarDatos(data,'/xadm/admUsuarios/actualizarPermisos/').then(function(res){
			if(res.data.error){
				alert('debes iniciar sessión para continuar');
			}
		});
	}

	$scope.class = function(id){
		return $scope.con[id];
	};

	(function(){
		angular.forEach($scope.permitidos,function(val){
			$scope.con[val['id_componente']]=true;
		});
	})();
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



