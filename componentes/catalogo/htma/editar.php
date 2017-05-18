<?
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$sql = sprintf("UPDATE `catalogo` SET `referencia` = %s, `titulo` = %s, `descripcion` = %s, `unidades` = %s, `precio` = %s WHERE `id` = %s",varSQL($_POST['referencia']),varSQL($_POST['titulo']),varSQL($_POST['descripcion']),varSQL($_POST['unidades']),varSQL($_POST['precio']),varSQL($_GET['text1']));
		consulta($sql);
	}

	$sql = sprintf("SELECT * FROM `catalogo` WHERE `cloud` = %s AND `id` = %s", varSQL(__sistema), varSQL($_GET['text1']));
	$datos = consulta($sql,true);
	$p = $datos['resultado'][0];
?>
<script src="/fm/js/ngFileUpload.js"></script>


<div ng-app="imagenes" ng-controller="imagenesSubir" ng-init="listar()">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#basico" aria-controls="home" role="tab" data-toggle="tab">Aspectos basicos</a></li>
    <li role="presentation"><a href="#imagenes" aria-controls="profile" role="tab" data-toggle="tab">Imagenes</a></li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="basico">
    	<br><br>
		<form method="POST" action="">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
						<label for="exampleInputEmail1">Titúlo</label>
						<input value="<?=$p['titulo']?>" type="text" class="form-control" name="titulo">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Unidades disponibles</label>
						<input name="unidades" value="<?=$p['unidades']?>" type="number" class="form-control">
					</div>	
				</div>
				<div class="col-xs-6">
					<div class="form-group">
						<label for="exampleInputEmail1">Referencia</label>
						<input value="<?=$p['referencia']?>" type="text" class="form-control" name="referencia">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Precio</label>
						<input name="precio" value="<?=$p['precio']?>" type="number" class="form-control">
					</div>
				</div>
			</div>
			
			<textarea id="descripcion" name="descripcion" style="width:100%">
	            <?=$p['descripcion'];?>
	        </textarea>
	        <? editor('#descripcion'); ?>
	        <br><br>
	        <div class="text-center">
	        	<button type="submit" class="btn btn-default">Guardar cambios</button>
	        </div>
		</form>
    </div>
    <div role="tabpanel" class="tab-pane" id="imagenes">
    	<div>
        	<ul class="upload">
				<li data-controls-modal="ventanaSubida" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#ventanaSubida" style="cursor:pointer;line-height: 100px;text-align: center;font-size: 60px;color: #1f1f1f;">			<i class="fa fa-plus"></i>
				</li>
				<li ng-repeat="imagen in imagenes">
					<span ng-click="eliminar(imagen)">
						<i class="fa fa-close"></i>
					</span>
					<img src="/<?=__path?>uploads/__catalogo/thumbs/{{imagen.imagen}}">
				</li>
				<div class="clear"></div>
			</ul>
		</div>
    </div>
  </div>



<!-- Modal -->
<div class="modal fade" id="ventanaSubida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-body">
        
      	<div ngf-pattern="image/*" ngf-max-size="5MB" ngf-keep="'distinct'" ng-style="{height: '300px'}" ngf-select="upload($files, $invalidFiles,$newFiles)" ngf-drop="upload($files, $invalidFiles,$newFiles)" class="drop-box" 
        ngf-drag-over-class="'dragover'" ngf-multiple="true" ngf-allow-dir="true">
			<span ng-show="!files">Selecciona imagenes o arrastralas aca para agregarlas</span>
			<ul class="upload">
				<li ng-repeat="f in filesToUpload">			
			        <img
					  ngf-thumbnail="f || '/fm/icons/folder.png'"
					>
					<div class="uploadNombre">{{f.name}}</div>
					<div ng-show="f.progress >= 0" class="progreso">
						<div style="width:{{f.progress}}%" class="progresoEstado"></div>
						<div ng-bind="f.progress + '%'" class="progresoAvance"></div>
					</div>
			      </span>
				</li>

				<li ng-repeat="f in errFiles" style="font:smaller">
					<img
					  ngf-thumbnail="f || '/fm/icons/folder.png'"
					>
					<div class="progreso text-center" style="background-color: red;">
						No valido!
					</div>
					<div class="uploadNombre">{{f.name}}</div>
		    	</li> 
			</ul>

		</div>

      </div>
      <div class="modal-footer">
        <button ng-click="regresar()" type="button" class="btn btn-default" >Regresar al listado </button>
      </div>
    </div>
  </div>
</div>

</div>

<script>
	var app = angular.module('imagenes',['ngFileUpload']);
	app.controller('imagenesSubir',function($scope,Upload,$timeout,conexiones,$interval){
		$scope.imagenes = [];
		
		$scope.listar = function(){
			conexiones.enviarDatos('','/xadm/catalogo/listarImagenes/<?=$_GET['text1']; ?>/').then(function(res){
				$scope.imagenes = res.data;
			});
		}
		$scope.upload = function(archivos,Earchivos,archivosN){
		    	$scope.files = archivosN;
		    	$scope.filesToUpload = archivos;
		        $scope.errFiles = Earchivos;

		        angular.forEach(archivosN, function(file) {
		        	if($scope.errFiles.indexOf(file)==-1){
		        		file.upload = Upload.upload({
			                url: '/xadm/catalogo/subirImagenes/<?=$_GET['text1']; ?>/',
			                data: {file: file}
			            });

			            file.upload.then(function (res) {
			                //console.log(res);

			            }, function (response) {
			            	//console.log(response);
			                if (response.status > 0)
			                    $scope.errorMsg = response.status + ': ' + response.data;
			            }, function (evt) {
			                file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
			            });
		        	}
		        });
		    };

		$scope.regresar = function(f){
			$('#ventanaSubida').modal('hide');
			$scope.listar();
			$scope.filesToUpload = [];
			$scope.errFiles      = [];
		}

		$scope.eliminar = function(e){
			conexiones.enviarDatos($.param(e),'/xadm/catalogo/eliminarImagen/').then(function(res){
				if(res.data.estado=='ok'){
					$scope.listar();
				}
			});
		}

	});

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
	.upload{list-style-type: none;padding: 0px;}
	.upload li{width: 124px;height: 100px;background-color: white;display:block;margin: 5px;border: 1px solid #DEDEDE;position: relative;float:left;}
	.upload li img{width: 100%;height: 100%;object-fit: contain;position: absolute;}
	.upload li span{position: absolute;right: 2px;top: 2px;z-index: 2;background-color: red;border-radius: 50%;height: 20px;width: 20px;text-align: center;color: white;cursor: pointer;}
	.uploadNombre{height: 25px;line-height: 25px;position: absolute;left: 0px;bottom: 0px;padding: 0px 5px;width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;background-color: white;}
	.progreso{width: 100%;height: 20px;background-color: #B7B5B5;position: absolute;left: 0px;top: 50%;-webkit-transform: translateY(-50%);-moz-transform: translateY(-50%);-o-transform: translateY(-50%);transform: translateY(-50%);border-radius: 10px;overflow: hidden;}
	.progresoEstado{height: 100%;position: absolute;left: 0px;top: 0px;border-radius: 10px;background-color: #545454;width: 10%;}
	.progresoAvance{height: 100%;width: 100%;color: white;position: absolute;left: 0px;top: 0px;text-align: center;z-index: 2;}
	.dragover{background-color: gray;}
</style>