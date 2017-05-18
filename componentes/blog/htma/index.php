<?
$articulos = consulta("SELECT `id`,`titulo` FROM `blog_articulos` WHERE `user` = '".__dominio."'",true);
?>

<div class="row">
	<div style="color:#4C5561;font-size:22px;" class="col-xs-12"><a href="/adm/dashboard/herramientas/"><i class="fa fa-backward" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;Articulos en el Blog.<br><br></div>
</div>

<div ng-app="blog" ng-controller="blogArticulos">
	<div ng-show="listado.length==0" class="row">
		<div style="font-size: 20px;color: red;" class="col-xs-12 text-center">
			Aun no se ha creado ningun articulo en el blog.
		</div>
	</div>
	<ul class="list-group listaMenus">
		<li ng-repeat="articulo in listado" class="list-group-item">
			<span ng-click="eliminar(articulo.id)" style="background-color:red;" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span> 
			<span class="badge"><a style="color:white;" href="/adm/blog/edt/{{articulo.id}}/"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> 
			{{articulo.titulo}}
		</li>
	</ul>
</div>


<script>
	var app = angular.module('blog',['ngBootbox']);
	app.controller('blogArticulos',function($scope,$ngBootbox,conexiones){
		$scope.listado = <?=($articulos==false)?'[]':json_encode(Encoding::toUTF8($articulos['resultado']))?>;
		$scope.eliminar = function(id){
			var data = $.param({id:id});
			conexiones.enviarDatos(data,'/adm/blog/eliminarArticulo/').then(function(res){
				alert('res');
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
			<button class="btn btn-default" type="submit" data-toggle="modal" data-target="#myModal">Crear nuevo artículo</button>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear nuevo artículo en el blog</h4>
      </div>
      <div class="modal-body">
        <form id="form_dissmiss" method="post" action="<? echo __url_real; ?>adm/blog/add/">
		  <div class="form-group">
		    <label for="exampleInputEmail1">Titulo:</label>
		    <input name="titulo" type="text" class="form-control" id="exampleInputEmail1" placeholder="Titulo">
		  </div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="$('#form_dissmiss').submit();" class="btn btn-primary">Guardar y editar</button>
      </div>
    </div>
  </div>
</div>




<style>
	.triggerLM{cursor:pointer;}
	.triggerLM:hover{background-color: #E2EEF4;}
	.badge{font-size: 17px !important;cursor:pointer;}
</style>