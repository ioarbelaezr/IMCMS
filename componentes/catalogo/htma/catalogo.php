<?
$articulos = consulta("SELECT `id`,`referencia`,`titulo`,`descripcion`,`unidades`,`precio`,`fecha_ingreso` FROM `catalogo` WHERE `cloud` = '".__sistema."' ORDER BY `id` DESC",true);
?>

<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12"><a href="/adm/dashboard/herramientas/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Catalogo de productos.<br><br></div>
</div>

<div ng-app="menus" ng-controller="menusListado">
	<div ng-show="listado.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			Aun no hay ningun articulo en el catalogo.
		</div>
	</div>
	<ul class="list-group listaMenus">
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2">
					<b>Referencia</b>
				</div>
				<div class="col-xs-5">
					<b>Titúlo</b>
				</div>
				<div class="col-xs-2">
					<b>Precio</b>
				</div>
				<div class="col-xs-2">
					<b>Cantidad</b>
				</div>
			</div>
		</li>
		<li ng-repeat="articulo in listado" class="list-group-item">
			<div class="row">
				<div class="col-xs-2">
					<b>{{articulo.referencia}}</b>
				</div>
				<div class="col-xs-5">{{articulo.titulo}}</div>
				<div class="col-xs-2">$ {{articulo.precio}}</div>
				<div class="col-xs-2">{{articulo.unidades}}</div>
				<div class="col-xs-1">
					<span style="background-color:green;" class="badge">
						<a style="color:white;" href="/adm/catalogo/editar/{{articulo.id}}/"><i class="fa fa-pencil" aria-hidden="true"></i></a>
					</span> 
				</div>
			</div>
		</li>
	</ul>

</div>

<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
	<div class="row">
		<div class="col-xs-12 text-center">
			<button class="btn btn-default" type="submit" data-toggle="modal" data-target="#myModal">Crear un nuevo producto</button>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear un nuevo producto</h4>
      </div>
      <form id="form_dissmiss" method="post" action="<? echo __url_real; ?>adm/catalogo/crear/">
	      <div class="modal-body">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Titúlo del articulo:</label>
			    <input required name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Titulo">
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

<script>
	var app = angular.module('menus',['ngBootbox']);
	app.controller('menusListado',function($scope,$ngBootbox,conexiones){
		$scope.listado = <?=($articulos==false)?'[]':json_encode(Encoding::toUTF8($articulos['resultado']))?>;
		$scope.uso = new Array;



		$scope.eliminar = function(menu){
			$ngBootbox.confirm('Seguro que deseas eliminar el menu seleccionado?').then(function(){
	        	var data = $.param({id:menu.id});
	        	conexiones.enviarDatos(data,'/xadm/menus/delMenu/').then(function(res){
	        		$scope.listado.splice($scope.listado.indexOf(menu),1);
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


<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
</style>