<?
	$c = consulta(sprintf("SELECT * FROM `categorias` WHERE `cloud` = %s AND `nivel` = 0",varSQL(__sistema)),true);
?>
<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12"><a href="/adm/dashboard/herramientas/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Categorias disponibles en el portal.<br><br></div>
</div>

<div ng-app="categorias" ng-controller="categoriasListado">
	<div ng-show="listado.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			Aun no se ha creado ninguna categoria en el portal.
		</div>
	</div>
	<ul class="list-group listaMenus">
		<li ng-repeat="categoria in listado" class="list-group-item">
			<span style="background-color:green;" class="badge"><a href="/adm/categorias/categoria/{{categoria.id}}/{{categoria.componente}}/" style="color:white;"><i class="fa fa-list" aria-hidden="true"></i></a></span> 
			{{categoria.nombre}}
		</li>
	</ul>

</div>

<script>
	var app = angular.module('categorias',[]);
	app.controller('categoriasListado',function($scope){
		$scope.listado = <?=($c==false)?'[]':json_encode($c['resultado'])?>;
		$scope.nombre = '';
		$scope.id = false;
		$scope.e = '';
	});
</script>

<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
</style>