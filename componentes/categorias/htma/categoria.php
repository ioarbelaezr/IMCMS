<?
	$n = sprintf("SELECT `nombre` FROM `categorias` WHERE `id` = %s",varSQL($_GET['text1']));
	$n = consulta($n,true);

	function listarCategorias($c){
		$sql = sprintf("SELECT * FROM `categorias` WHERE `id_padre` = %s",varSQL($c));
		$c   = consulta($sql,true);
		if($c!=false){
			foreach($c['resultado'] as $i){
				?> 
					<li class='list-group-item'>
					<span ng-click="eliminar(<?=$i['id']; ?>)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
					<?
						if($i['nivel']<3){?>
							<span ng-click="nueva(<?=$i['id'];?>,<?=$i['nivel'];?>)" style="background-color:green;"  class="badge"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></span> 
					<?}
				repetir('&nbsp;&nbsp;|&nbsp;',$i['nivel']);
				echo "<b>".$i['nombre']."</b>";
				listarCategorias($i['id']);
				?>
					</li>
				<?
			}
		}
	}
?>
<div ng-app="categorias" ng-controller="categoriasAcciones">
	<div class="row">
		<div style="font-size: 20px;color: #4C5561;" class="col-xs-12 text-left">
			<a href="/adm/categorias/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Categoria: &nbsp;<?=$n['resultado'][0]['nombre']; ?><br><br>
		</div>
	</div>

	<ul class="list-group">
		<? listarCategorias($_GET['text1']); ?>
	</ul>

	<div class="ancho_base" style="margin-bottom: 30px;margin-top:30px;">
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-default" type="submit" ng-click="nueva(<?=$_GET['text1'];?>,0)">Crear una nueva categoria</button>
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
		        <h4 class="modal-title" id="myModalLabel">Categoria</h4>
		      </div>
		      <div class="modal-body">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Titulo:</label>
				    <input required ng-model="nombre" name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Titulo">
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
	var app = angular.module('categorias',['ngBootbox']);
	app.controller('categoriasAcciones',function($scope,$ngBootbox,conexiones){
		$scope.nombre = '';
		$scope.eliminar = function(id){
			$ngBootbox.confirm('Seguro que deseas eliminar la categoria seleccionada?').then(function(){
	        	var data = $.param({id:id});
	        	conexiones.enviarDatos(data,'/xadm/categorias/eliminarCategoriaPrincipal/').then(function(){
	        		window.location.reload();
	        	})
	        },function(){
	        });
		}

		$scope.nueva = function(padre,nivel){
			$scope.id = padre;
			$scope.nombre = '';
			$scope.nivel = nivel;
			$scope.componente = <?=$_GET['text2']; ?>;
			$('#myModal').modal();
		}

		$scope.guardar = function(){
			var data = $.param({padre:$scope.id,nombre:$scope.nombre,nivel:$scope.nivel,componente:$scope.componente});
			conexiones.enviarDatos(data,'/xadm/categorias/agregarCategoriaHija/').then(function(){
				//window.location.reload();
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
	.badge{font-size: 17px!important;cursor: pointer;}
</style>
