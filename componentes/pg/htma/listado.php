<?
$pags = consulta("SELECT `id`,`titulo` FROM `contenidos` WHERE `cloud` = '".__sistema."'",true);
?>
<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12"><a href="/adm/dashboard/herramientas/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Paginas disponibles en el portal.<br><br></div>
</div>
<div ng-app="paginas" ng-controller="paginasListado">
	<div class="row">
		<div class="col-md-4 col-sm-12 form-group">
			<button data-toggle="modal" data-target="#myModal" class="btn btn-default" ><span class="glyphicon glyphicon-plus"></span> Crea Página</button>
		</div>
		<div class="col-md-4 col-sm-6 text-right">
			<form class="ng-pristine ng-valid">
				<div class="form-group">
					<select ng-model="categoria" class="form-control ng-pristine ng-valid ui-corner-all formularios ancho100 inputGradient ng-touched" style="margin-top: 0;">
						<option value="" ng-selected="selected" class="" selected="selected">Todas las categorías</option>
						<option value="" label="Inicio del Portal">Paginas sin categoria</option>
					</select>
				</div>
			</form>
		</div>
		<div class="col-md-4 col-sm-6 text-right">
			<form class="form-inline ng-pristine ng-valid" ng-submit="buscar();">
			  <div class="form-group">
			    <div class="input-group">
			      <input type="text" class="form-control ng-pristine ng-valid ui-corner-all formularios ancho100 inputGradient ng-touched" ng-model="buscar" placeholder="Buscar..." style="margin-top: 0;">
			    </div>
			  </div>
			</form>
		</div>
	</div>

	<div ng-show="listado.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			No hay paginas disponibles en el portal!
		</div>
	</div>
	<ul class="list-group listaMenus">
		<li ng-repeat="pagina in filtradas =(listado | filtroPaginas) | filter:buscar" class="list-group-item">
			<span ng-click="eliminar(pagina)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
			<span class="badge"><a style="color:white;" href="/adm/pg/edt/{{pagina.id}}/"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> 
			{{pagina.titulo}}
		</li>
	</ul>
</div>
<script>
	var app = angular.module('paginas',['ngBootbox']);
	app.controller('paginasListado',function($scope,$ngBootbox,conexiones){
		$scope.listado = <? echo ($pags==false)?'[]':json_encode(Encoding::toUTF8($pags['resultado'])); ?>;

		$scope.eliminar = function(p){
			$ngBootbox.confirm('Seguro que deseas eliminar la pagina seleccionada?').then(function(){
	        	var data = $.param({id:p.id});
	        	conexiones.enviarDatos(data,'/xadm/pg/eliminar/').then(function(){
	        		$scope.listado.splice($scope.listado.indexOf(p),1);
	        	});
	        },function(){
	        });
		}
	});
	//filtrar las paginas 
	app.filter('filtroPaginas', function() {
		return function(input) {
			return input;
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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear nueva pagina</h4>
      </div>
      <div class="modal-body">
        <form id="form_dissmiss" method="post" action="<? echo __url_real; ?>adm/pg/add/">
		  <div class="form-group">
		    <label for="exampleInputEmail1">Titulo:</label>
		    <input name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Titulo">
		  </div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="$('#form_dissmiss').submit();" class="btn btn-primary">Guardar y configurar</button>
      </div>
    </div>
  </div>
</div>


<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
	.btn-buscar{background: transparent;border: none;padding: 0;}
</style>