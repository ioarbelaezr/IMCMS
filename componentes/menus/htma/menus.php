<?
$menus = consulta("SELECT * FROM `menus` WHERE `cloud` = '".__sistema."'",true);
?>

<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12"><a href="/adm/dashboard/herramientas/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Menus disponibles en el portal.<br><br></div>
</div>

<div ng-app="menus" ng-controller="menusListado">
	<div ng-show="listado.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			Aun no se ha creado ningun menu en el portal.
		</div>
	</div>
	<ul class="list-group listaMenus">
		<li ng-repeat="menu in listado" class="list-group-item">
			<span ng-click="eliminar(menu)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
			<span ng-click="usar(menu)" style="background-color:green;" class="badge"><i class="fa fa-plug" aria-hidden="true"></i></span> 
			<span class="badge"><a style="color:white;" href="/adm/menus/edt/{{menu.id}}/"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> 
			{{menu.nombre}}
		</li>
	</ul>

	<!-- Modal -->
	<div class="modal fade" id="usarMenu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Usar el menu: <b>{{uso.nombre}}</b> </h4>
	      </div>
	      <form id="form_dissmiss" method="post" ng-submit="return!1;">
		      <div class="modal-body">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Clase CSS:</label>
				    <input ng-model="uso.class" type="text" class="form-control" id="exampleInputEmail1" placeholder="Clase CSS">
				  </div>				  
				  <div class="form-group">
				    <label for="exampleInputEmail1">ID CSS:</label>
				    <input ng-model="uso.idcss" type="text" class="form-control" id="exampleInputEmail1" placeholder="ID CSS">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Copia y pega el siguiente codigo:</label>
				    <textarea readonly onClick="this.select();" class="form-control">
				    	{{codigo()}}
				    </textarea>
				  </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Listo!</button>
		      </div>
	      </form>
	    </div>
	  </div>
	</div>

</div>

<script>
	var app = angular.module('menus',['ngBootbox']);
	app.controller('menusListado',function($scope,$ngBootbox,conexiones){
		$scope.listado = <?=($menus==false)?'[]':json_encode(Encoding::toUTF8($menus['resultado']))?>;
		$scope.uso = new Array;
		$scope.usar = function(menu){
			$('#usarMenu').modal();
			$scope.uso.nombre = menu.nombre;
			$scope.uso.id     = menu.id;
			$scope.uso.class  = "";
			$scope.uso.idcss  = "";
		}

		$scope.codigo = function(){
			return "\<\?php menu::mostrar("+$scope.uso.id+",'"+$scope.uso.class+"','"+$scope.uso.idcss+"'); \?\>";
		}

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


<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
	<div class="row">
		<div class="col-xs-12 text-center">
			<button class="btn btn-default" type="submit" data-toggle="modal" data-target="#myModal">Crear un nuevo menu</button>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear un nuevo menu</h4>
      </div>
      <form id="form_dissmiss" method="post" action="<? echo __url_real; ?>adm/menus/add/">
	      <div class="modal-body">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Nombre del menu:</label>
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



<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
</style>