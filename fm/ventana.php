<?
session_start();
require_once('config/config.php');

$tipo = 'file';
if(isset($_GET['tipo'])){
	$tipo = $_GET['tipo'];
}

if (!isset($conf)){
		$conf = include 'config/config.php';
		extract($conf, EXTR_OVERWRITE);
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Administrador de enlaces</title>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular.min.js"></script>
	<script src="/frontend/jquery/jquery-2.1.4.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/frontend/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/fm/css/angularTooltips.css">
	<script src="/frontend/bootstrap/js/bootstrap.min.js"></script>
	<script src="/fm/js/anguBootBox.js"></script>
	<script src="/fm/js/ngFileUpload.js"></script>
	<script src="/fm/js/contextMenu.js"></script>
	<script src="/fm/js/angularTooltips.js"></script>
</head>
<body ng-app="enlaces" ng-controller="enlacesListado" ng-init="listar()">
	<div class="h">
		<div class="header">
		<div class="actions_container">
			<div class="row">
				<div class="col-xs-4">
					<div class="buttons">
						<ul  ng-show="Aa">
							<li tooltips tooltip-template="Subir archivos al servidor" tooltip-side="bottom" tooltip-class="appTooltips" ng-click="subirVentana()"><i class="fa fa-cloud-upload"></i></li>
							<li tooltips tooltip-template="Nuevo archivo" tooltip-side="bottom" tooltip-class="appTooltips" ng-click="crearTexto()"><i class="fa fa-file"></i></li>
							<li tooltips tooltip-template="Nueva carpeta" tooltip-side="bottom" tooltip-class="appTooltips" ng-click="crearCarpeta()"><i class="fa fa-folder-open-o"></i></li>
						</ul>
					</div>
				</div>
				<div class="col-xs-2 text-center">
					<?
						if(!isset($_GET['adm'])&&$tipo=='file'){?>
							<ul>
								<li ng-click="Aa=true" tooltips tooltip-template="Archivos" tooltip-side="bottom" tooltip-class="appTooltips" ng-class="Aa?'active':''"><i class="fa fa-files-o"></i></li>
								<li ng-click="Aa=false" tooltips tooltip-template="Componentes del sistema" tooltip-side="bottom" tooltip-class="appTooltips" ng-class="Aa?'':'active'"><i class="fa fa-puzzle-piece"></i></li>
							</ul>
						<?}
					?>
				</div>
				<div class="col-xs-6 text-right">
					<span style="display: inline-block;padding: 4px 12px;">Filtros: </span>
					<ul style="display: inline-block;">
						<?
							if($tipo=='file'){?>
								<li ng-show="Aa" ng-click="filtrar('file')" ng-class="filterType=='file'?'active':''" tooltips tooltip-template="Archivos" tooltip-side="bottom" tooltip-class="appTooltips"><i class="fa fa-file"></i></li>
								<li ng-show="Aa"  ng-click="filtrar('image')"  ng-class="filterType=='image'?'active':''" tooltips tooltip-template="Archivos de imagen" tooltip-side="bottom" tooltip-class="appTooltips"><i class="fa fa-file-image-o"></i></li>
								<li ng-show="Aa"  ng-click="filtrar('music')"  ng-class="filterType=='music'?'active':''" tooltips tooltip-template="Archivos de audio" tooltip-side="bottom" tooltip-class="appTooltips"><i class="fa fa-file-audio-o"></i></li>
								<li ng-show="Aa"  ng-click="filtrar('video')"  ng-class="filterType=='video'?'active':''" tooltips tooltip-template="Archivos de video" tooltip-side="bottom" tooltip-class="appTooltips"><i class="fa fa-file-video-o"></i></li>
							<?}
						?>
						<li tooltips tooltip-template="Filtrar por nombre" tooltip-side="bottom" tooltip-class="appTooltips" style="width: 100px;height: 30px;"><input placeholder="Nombre del elemento" ng-model="nombreBuscado" style="width: 100%;height: 20px;" type="text"></li>
						<li ng-click="filterType='';nombreBuscado=''" tooltips tooltip-template="Eliminar filtros" tooltip-side="bottom" tooltip-class="appTooltips"><i class="fa fa-times"></i></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

		<div class="navegador">
			<div class="actions_container">
				<div class="historial" ng-show="Aa">
					<a ng-click="navegar('')">
						<span>
							<i class="fa fa-home"></i> /
						</span>
					</a>
					<a ng-click="navegar(c)" ng-if="$index!=0" ng-repeat="c in current">
						<span>
							{{c}}
						</span>
					</a>
					<span ng-show="trabajando" style="float:right;"><i style="font-size: 20px;margin-right: 5px;margin-top: 5px;" class="fa fa-spinner fa-spin"></i></span>
				</div>	
				<ul class="componentesHeader" ng-show="!Aa">
					<li ng-click="linkearComponente({ruta:'/fm/listarComponentes.php'})">Ver componentes</li>
					<li ng-click="linkearComponente(item,$index)" ng-repeat="item in cabecera">{{item.titulo}}</li>
				</ul>

			</div>
		</div>
	</div>
	

	<div class="bodyContainer" ng-show="Aa">
		<!--listado de ficheros en el servidor-->
		<ul class="ficherosListado">
			<li ng-click="back()" ng-show="current.length > 1 && !trabajando" class="ffichero" >
				<img src="/fm/icons/back_icon.png" alt="">
			</li>

			<li  context-menu="menuOptions" ng-click="linkear(fichero)" ng-repeat="fichero in ficheros | orderBy:['+tipo','+nombre'] | filter:filtrarListado" class="ffichero" >
				<img src="{{miniatura(fichero)}}" alt="">
				<div class="ficheroNombre">{{fichero.nombre}}</div>
			</li>	
		</ul>
	</div>
	<div class="bodyContainer" ng-show="!Aa">
		<!--listado de componentes disponibles-->


		<ul class="ficherosListado">
			<li tooltips tooltip-template="{{componente.nombre||componente.titulo}}" tooltip-side="bottom" tooltip-class="appTooltips" ng-click="linkearComponente(componente)" class="ffichero" ng-repeat="componente in cuerpo | filter:nombreBuscado">
				<img src="{{componente.icon}}" alt="">
				<div class="ficheroNombre">
					{{componente.nombre||componente.titulo}}
				</div>
			</li>
		</ul>
	</div>

<script>
		var app = angular.module('enlaces',['ngBootbox','ngFileUpload','ui.bootstrap.contextMenu','720kb.tooltips']);
		app.controller('enlacesListado',function($rootScope,$scope,conexiones,$ngBootbox,Upload,variables,$timeout){
			$scope.Aa = true;
			$scope.ficheros = variables.ficheros;
			$scope.current = variables.current;
			//$scope.componentes = variables.componentes;
			$scope.extensionArchivo = '.txt';
			$scope.trabajando = false;
			$scope.tipo = '<?=$tipo; ?>';
			$scope.nombreBuscado = '';
			$scope.filterType = '';
			$scope.ext_img = ['<?=implode("','", $ext_img)?>'];
			$scope.ext_video = ['<?=implode("','", $ext_video)?>'];
			$scope.ext_music = ['<?=implode("','", $ext_music)?>'];
			$scope.ext_file = ['<?=implode("','", $ext_misc); ?>','<?=implode("','", $ext_file)?>'];
			$scope.archivo = {"extension": ".txt"};

			$scope.cabecera = variables.cabecera;
			$scope.cuerpo = variables.componentes;

			$scope.listar = function(){
				$scope.ficheros = [];
				var d = $scope.current.join("");
				$scope.trabajando = true;
				conexiones.enviarDatos('','/fm/listarDirectorio.php?tipo='+$scope.tipo+'&current='+d).then(function(res){
					$scope.ficheros = res.data.archivos;
					$scope.trabajando = false;
				});
				//listado de modulos disponibles
				conexiones.enviarDatos('','/fm/listarComponentes.php').then(function(res){
					$scope.cuerpo = res.data.cuerpo;
				});
			}
			$scope.miniatura = function(item){
				switch(item.extension){
					<?
					foreach($config['ext_img'] as $ex){?>
					case '<?=$ex; ?>':
						<?}
					?>
						return item.thumb;
						break;
					<?
					foreach($config['ext_music'] as $ex){?>
					case '<?=$ex; ?>'://ext_video
						<?}
					?>
						return '/fm/icons/music.png';
						break;
					<?
					foreach($config['ext_video'] as $ex){?>
					case '<?=$ex; ?>':
					<? }
					?>
						return '/fm/icons/video.png';
						break;
					case 'pdf':
						return '/fm/icons/pdf.png';
						break;
					case 'docx':
						return '/fm/icons/docx.png';
						break;
						case 'html':
						case 'htm':
						return '/fm/icons/html.png';
						break;
					case 'zip':
						return '/fm/icons/zip.png';
						break;
					case 'rar':
						return '/fm/icons/rar.png';
						break;
					case 'txt':
						return '/fm/icons/txt.png';
						break;
					case '.':
						return '/fm/icons/folder.png';
					break;
				}
			}
			//opciones en el menu
			$scope.menuOptions = [
	            ['Eliminar', function ($itemScope) {
	            	var msj = ($itemScope.fichero.tipo==0)?'Seguro que quieres eliminar esta carpeta y todos los elementos dentro de esta?':'Seguro que quieres eliminar este archivo?';
	                $ngBootbox.confirm(msj).then(function(){
	                	var data = $.param({tipo:$itemScope.fichero.tipo,'nombre':$itemScope.fichero.nombre});
	                	var current = $scope.current.join("");
	                	var url = "/fm/actions.php?accion=eliminar&current="+current;
	                	$scope.trabajando = true;
	                	conexiones.enviarDatos(data,url).then(function(res){
	                		var index = $scope.ficheros.indexOf($itemScope.fichero);
	                		$scope.ficheros.splice(index, 1); 
	                		$scope.trabajando = false;
	                	})
	                },function(){
	                });
	            }],
	            null,
	            ['Renombrar', function ($itemScope) {
	                alert('renombrar');
	            }],
	            null,
	            ['Copiar', function ($itemScope) {
	                alert('Copiar');
	            }],
	            null,
	            ['Mover', function ($itemScope) {
	                alert('Mover');
	            }],
	            null,
	            ['Propiedades', function ($itemScope) {
	                alert('propiedades');
	            }]
	        ];
	        //linkear archivos
			$scope.linkear = function(item){
				if(item.tipo==1){
					<?
						if(isset($_GET['menu'])){?>
							try {
						        window.opener.HandlePopupResult(item.direccion);
						    }
						    catch (err) {}
						    window.close();
						    return false;
						<?}else{?>
							var args = top.tinymce.activeEditor.windowManager.getParams();
						    win = (args.window);
						    input = (args.input);
						    win.document.getElementById(input).value = item.direccion;
						    top.tinymce.activeEditor.windowManager.close();
						<?}
					?>
				}else{
					$scope.current.push(item.nombre+"/");
					$scope.listar();
				}
			}
			//linkear componentes
			$scope.linkearComponente = function(item,index){
				if(item.hasOwnProperty('id')){
					<?
						if(isset($_GET['menu'])){?>
							try {
						        window.opener.HandlePopupResult(item.ruta);
						    }
						    catch (err) {}
						    window.close();
						    return false;
						<?}else{?>
							var args = top.tinymce.activeEditor.windowManager.getParams();
						    win = (args.window);
						    input = (args.input);
						    win.document.getElementById(input).value = item.ruta;
						    top.tinymce.activeEditor.windowManager.close();
						<?}
					?>
				}else{
					//retrieve a new object whit a list of elements
					conexiones.enviarDatos('',item.ruta).then(function(res){
						$scope.cuerpo = res.data.cuerpo;
						$scope.cabecera = res.data.cabecera;
					});
				}	
			}
			$scope.navegar = function(i){
				var index      = $scope.current.indexOf(i);
				$scope.current = $scope.current.splice(0,(index+1));
				$scope.listar();
			}

			$scope.back = function(){
				var index      = ($scope.current.length) - 2;
				$scope.current = $scope.current.splice(0,(index+1));
				$scope.listar();
			}
			//carpetas
			$scope.crearCarpeta = function(){
				var options = {
				    title: 'Crear nueva carpeta',
					templateUrl: '/fm/crearCarpeta.php',
					scope : $scope
				};
				$ngBootbox.hideAll();
				$ngBootbox.customDialog(options);
			}

			$scope.carpetaGuardar = function(){
				if($scope.carpetaNombre !=''&&$scope.carpetaNombre != undefined){
					var data = $.param({carpeta:$scope.carpetaNombre});
					var current = $scope.current.join("");
					var url = "/fm/actions.php?accion=crearCarpeta&current="+current;
					$scope.trabajando = true;
					conexiones.enviarDatos(data,url).then(function(res){
						if(res.data.estado=='ok'){
							$scope.ficheros.push({ "nombre": $scope.carpetaNombre, "tipo": 0, "tamano": 0, "archivos": 0, "carpetas": 0, "extension": ".", "direccion": ".", "thumb": "." });
							$ngBootbox.hideAll();
							$scope.trabajando = false;
							$scope.carpetaNombre = '';
						}else{
							alert('Hubo un error creando la nueva carpeta');
						}
					});
				}else{
					alert('Debes especificar un nombre para la nueva carpeta');
				}
			}
			//archivos de texto
			$scope.crearTexto = function(){
				var options = {
				    title: 'Crear nuevo archivo',
					templateUrl: '/fm/crearText.php',
					scope: $scope
				};
				$ngBootbox.hideAll();
				$ngBootbox.customDialog(options);
			}
			$scope.guardarArchivo = function(){
				//alert('Guardar el archivo');
				if($scope.archivo.nombre == "" || $scope.archivo.nombre == false || $scope.archivo.nombre == undefined){
					alert('debes proporcionar un nombre de archivo');
				}else if(false){

				}else{
					var data = $.param({nombre:$scope.archivo.nombre,extension:$scope.archivo.extension,contenido:$scope.archivo.contenido});
					var current = $scope.current.join("");
					var url = "/fm/actions.php?accion=crearArchivo&current="+current;
					conexiones.enviarDatos(data,url).then(function(res){
						if(res.data.estado == 'ok'){
							$ngBootbox.hideAll();
							$scope.archivo = {"extension": ".txt"};
							$scope.listar();
						}else{
							alert('No se ha podido crear el archivo!');
						}
					});
				}
			}
			//subida de archivos al servidor
			$scope.subirVentana = function(){
				var options = {
				    title: 'Seleccionar o arrastrar archivos para subir',
					templateUrl: '/fm/uploadWindow.php',
					size : 'large',
					scope: $scope,
					 buttons: {
					    success: {
					        label: "Regresar al listado de archivos",
					        className: "btn-success",
					        callback: function() {
					       		$scope.listar();
					        }
					    }
					}
				};
				$ngBootbox.hideAll();
				$ngBootbox.customDialog(options);
				$scope.files = false;
		    	$scope.filesToUpload = false;
		        $scope.errFiles = false;
			}

		    $scope.upload = function(archivos,Earchivos,archivosN){
		    	$scope.files = archivosN;
		    	$scope.filesToUpload = archivos;
		        $scope.errFiles = Earchivos;
		        var current = $scope.current.join("");
		        angular.forEach(archivosN, function(file) {
		        	if($scope.errFiles.indexOf(file)==-1){
		        		file.upload = Upload.upload({
			                url: '/fm/actions.php?accion=upload&current='+current,
			                data: {file: file}
			            });

			            file.upload.then(function (res) {
			                
			            }, function (response) {
			                if (response.status > 0)
			                    $scope.errorMsg = response.status + ': ' + response.data;
			            }, function (evt) {
			                file.progress = Math.min(100, parseInt(100.0 * 
			                                         evt.loaded / evt.total));
			            });
		        	}
		        });
		    }
		    //varios
		    $scope.cerrar = function(){
				$ngBootbox.hideAll();
			};

			$scope.trustSrc = function(src) {
				return $sce.trustAsResourceUrl(src);
			}
			//filtrar los elementos de la lista
			$scope.filtrarListado = function(item){
				if($scope.filterType==''){
					return (item.nombre.toLowerCase().indexOf($scope.nombreBuscado)!=-1);
				}else{
					switch(true){
						case (($scope.filterType=='image'&&(item.nombre.toLowerCase().indexOf($scope.nombreBuscado)!=-1)&&$scope.ext_img.indexOf(item.extension)!=-1)||item.tipo==0):
							return true;
							break;
							case (($scope.filterType=='video'&&(item.nombre.toLowerCase().indexOf($scope.nombreBuscado)!=-1)&&$scope.ext_video.indexOf(item.extension)!=-1)||item.tipo==0):
							return true;
							break;
							case (($scope.filterType=='music'&&(item.nombre.toLowerCase().indexOf($scope.nombreBuscado)!=-1)&&$scope.ext_music.indexOf(item.extension)!=-1)||item.tipo==0):
							return true;
							break;
							case (($scope.filterType=='file'&&(item.nombre.toLowerCase().indexOf($scope.nombreBuscado)!=-1)&&$scope.ext_file.indexOf(item.extension)!=-1)||item.tipo==0):
							return true;
							break;
						default:
							return false;
					}
				}
			}
			$scope.filtrar = function(tipo){
				$scope.filterType = ($scope.filterType!=tipo||$scope.filterType=='')?tipo:'';
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

		app.factory("variables",function(){
			return {
				ficheros    : [],
				cuerpo      : [],
				current     : [""],
				cabecera    : []
			}
		});

		
	</script>
</body>
<style>
	.header, .navegador{height:40px;background-color: #F7F7F7;width: 100%;top:0px;left: 0px;    border-bottom: 1px solid #bbb;padding: 4px 0px;}
	.h{position: fixed;top: 0px;width: 100%;left: 0px;}
	.header div,.navegador div{height: 100%;}
	.navegador{top:40px;}
	.header ul{list-style-type: none;padding: 0px;}
	.actions_container{padding: 0px 15px;}
	.header ul li,.componentesHeader li{display: inline-block;padding: 4px 12px;margin-bottom: 0;font-size: 14px;line-height: 20px;text-align: center;vertical-align: middle;cursor: pointer;color: #333;background-color: #f5f5f5;background-image: linear-gradient(to bottom,#fff,#e6e6e6);background-repeat: repeat-x;filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);border: 1px solid #ccc;}
	.header ul li:hover, li.active,.componentesHeader li:hover,.componentesHeader li.active{background: #000000!important;color: white !important;}
	.historial{line-height: 31px;}
	.historial a{font-size: 18px;color: black;}
	.hostorial a:active,.hostorial a:hover,.hostorial a:visited{color: black !important;}
	.bodyContainer{margin-top: 90px;padding: 15px;}
	.ficherosListado{list-style-type:none;padding:0px;margin:0px;text-align: left;}

	.ffichero{width: 124px;height: 100px;background-color: white;display: inline-block;margin: 5px;border: 1px solid #DEDEDE;position: relative;cursor: pointer;text-align: center;}
	.ffichero img{width: 70%;height: 70%;object-fit: contain;}
	.ffichero:hover{border: 1px solid #9E9E9E;}
	.ficheroNombre{height: 25px;line-height: 25px;position: absolute;left: 0px;bottom: 0px;padding: 0px 5px;width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;background-color: white;}
	.dragover{background-color: red;}
	.historial a:hover{text-decoration: none;color: gray;cursor: pointer;}
	.historial a:active,.historial a:visited,.historial a:focus{text-decoration: none;}
	.appTooltips{z-index: 100;height: auto;}

	.componentesHeader{height:100%;list-style-type: none;padding: 0px;margin: 0px;width: 100%;text-align: center;}
	.componentesHeader li{padding: 0px 24px;height: 100%;line-height: 29px}
</style>
</html>