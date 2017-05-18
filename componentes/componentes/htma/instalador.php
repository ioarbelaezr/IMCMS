<?
	$c = consulta(sprintf("SELECT `id`,`componente` FROM `componentes_disponibles` WHERE `id` NOT IN(SELECT `id_componente` FROM `componentes_instalados` WHERE `cloud` = %s)",varSQL($_GET['text1'])),true);
	$i = consulta(sprintf("SELECT `id`,`nombre`,`id_componente` FROM `componentes_instalados` WHERE `cloud` = %s",varSQL($_GET['text1'])),true);
?>

<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12 text-center">Componentes disponibles para instalar en el cloud.<br><br></div>
</div>

<div ng-app="componentes" ng-controller="componentesListado">
	<ul class="list-group listaMenus">
		<li ng-repeat="componente in listado" class="list-group-item"> 
			<span ng-click="instalar(componente)" class="badge"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
			{{componente.componente}}
		</li>
	</ul>
	<div class="row">
		<div style="color:#4C5561;font-size:22px;" class="col-xs-12 text-center">Componentes instalados en <b><?=obtener_campo(sprintf("SELECT `nombre` FROM `clouds` WHERE `id` = %s",varSQL($_GET['text1']))); ?></b>.<br><br></div>
	</div>	
	<ul class="list-group listaMenus">
		<li ng-repeat="componente in instalados" class="list-group-item"> 
			<span ng-click="desinstalar(componente)" class="badge"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
			{{componente.nombre}}
		</li>
	</ul>
</div>

<script>
	var app = angular.module('componentes',[]);
	app.controller('componentesListado',function($scope,conexiones){
		$scope.listado    = <?=($c==false)?'[]':json_encode(Encoding::toUTF8($c['resultado']))?>;
		$scope.instalados = <?=($i==false)?'[]':json_encode(Encoding::toUTF8($i['resultado']))?>;

		$scope.instalar   = function(componente){
			var data = $.param({id:componente.id,cloud:<?=$_GET['text1'];?>});
			conexiones.enviarDatos(data,'/xadm/componentes/instalar/').then(function(res){
				if(res.data.id!=0){
					$scope.instalados.push({id:res.data.id,nombre:componente.componente,id_componente:componente.id});
					$scope.listado.splice($scope.listado.indexOf(componente),1);
				}else{
					alert('Hubo un error mientras se instalaba el componente');
				}
			});
		}
		$scope.desinstalar = function(componente){
			var data = $.param({id:componente.id,cloud:<?=$_GET['text1'];?>});
			conexiones.enviarDatos(data,'/xadm/componentes/desinstalar/').then(function(res){
				if(res.data.s==1){
					$scope.instalados.splice($scope.instalados.indexOf(componente),1);
					$scope.listado.push({id:componente.id_componente,componente:componente.nombre});
				}else{
					alert('Hubo un error mientras se desinstalaba el componente');
				}
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
	.badge{font-size: 17px !important;cursor:pointer;}
</style>