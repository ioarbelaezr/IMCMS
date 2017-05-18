
<? $c = sprintf("SELECT `nombre` FROM `menus` WHERE `id` = %s",varSQL($_GET['text1']));
	$n = consulta($c,true); ?>
<div ng-app="lm" ng-controller="lmAcciones">
	<div class="row">
		<div style="font-size: 20px;color: #4C5561;" class="col-xs-12 text-left">
			<a href="/adm/menus/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Menu: &nbsp;<?=$n['resultado'][0]['nombre']; ?><br><br>
		</div>
	</div>
	<div ng-show="menus.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			No hay items en este menu!
		</div>
	</div>
	<ul class="list-group listaMenus" dnd-list="menus">
		<li dnd-draggable="menu" dnd-moved="movido($index)" dnd-effect-allowed="move" ng-repeat="menu in menus" class="list-group-item">
			<span ng-click="eliminar(menu)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
			<span ng-click="editar(menu)" class="badge"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> 
			{{menu.titulo}}
		</li>
	</ul>

	<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-default" type="submit" ng-click="item()">Crear un nuevo item en el menu</button>
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
			    <label for="exampleInputEmail1">Titulo:</label>
			    <input required ng-model="titulo" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Titulo">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputEmail1">Url:</label>
			    <div class="form-group has-feedback">
				  <label class="control-label sr-only" for="inputGroupSuccess4">Input group with success</label>
				  <div class="input-group">
				    <input ng-model="url" type="text" class="form-control" id="url" aria-describedby="inputGroupSuccess4Status">
				    <span class="input-group-addon triggerLM" onclick="enlazar();">
				    	<i class="fa fa-link" aria-hidden="true"></i>
				    </span>
				  </div>
				</div>
			  </div>
			  <div class="form-group">
			    <label for="exampleInputEmail1">Abrir en:</label>
			    <select ng-model="target" class="form-control" name="" id="">
			    	<option value="0">La ventana activa</option>
			    	<option value="1">Una nueva ventana</option>
			    </select>
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

<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
	/*ocultar el elemento cojido para mover*/
	.listaMenus .dndDraggingSource {display: none;}
	.listaMenus .dndPlaceholder {
    display: block;
    background-color: #ddd;
    min-height: 42px;
}
</style>

<?
	$menus = consulta(sprintf("SELECT * FROM `menus_items` WHERE `id_menu` = %s ORDER BY `orden` ASC",varSQL($_GET['text1'])),true);
?>



<!--Necesary javascript for performance-->
<script>	
var app = angular.module('lm',['dndLists','ngBootbox']);
app.controller('lmAcciones',function($scope,conexiones,$ngBootbox){
	$scope.target   = '0';
	$scope.menus    = <?=($menus==false)?'[]':json_encode(Encoding::toUTF8($menus['resultado'])); ?>;
	$scope.editando = '';
	$scope.p = '';
	$scope.item = function(){
		$scope.url    = '';
		$scope.target = '0';
		$scope.titulo = '';
		$('#myModal').modal();
	}

	$scope.guardar = function(){
		$scope.url = $('#url').val();//fix the bug
		$('#myModal').modal('hide');
		var data = $.param({url:$scope.url,target:$scope.target,titulo:$scope.titulo,padre:'0',menu:'<?=$_GET['text1']; ?>',edt:$scope.editando});
		conexiones.enviarDatos(data,'/xadm/menus/addItem/').then(function(res){
			if($scope.editando == ''){
				$scope.menus.push({id:res.data.IDI,id_menu:'<?=$_GET['text1']; ?>',id_padre:'0',titulo:$scope.titulo,url:$scope.url,target:$scope.target});
			}else{
				$scope.p.titulo = $scope.titulo;
				$scope.p.target = $scope.target;
				$scope.p.url    = $scope.url;
			}
			$scope.editando = '';
		});
	}

	$scope.eliminar = function(menu){
		$ngBootbox.confirm('Seguro que deseas eliminar este item del menu?').then(function(){
        	var data = $.param({id:menu.id});
        	conexiones.enviarDatos(data,'/xadm/menus/delItem/').then(function(res){
        		$scope.menus.splice($scope.menus.indexOf(menu),1);
        	});
        },function(){
        });
	}

	$scope.editar = function(m){
		$scope.url      = m.url;
		$scope.target   = m.target;
		$scope.titulo   = m.titulo;
		$scope.editando = m.id;
		$scope.p        = m;
		$('#myModal').modal();
	}

	$scope.movido = function(i){
		$scope.menus.splice(i, 1);
		var data = $.param({m:JSON.stringify($scope.menus)});
		conexiones.enviarDatos(data,'/xadm/menus/reOrdenar/').then(function(){
			//alert(res.data);
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


var s;
function enlazar(){
	window.open("/fm/ventana.php?tipo=file&menu", "lm", "width=1000,height=500"); 
	return!1;
}

function HandlePopupResult(result) {
    $('#url').val(result);
}
</script>



