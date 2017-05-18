<div ngf-max-size="5MB" ngf-keep="'distinct'" ng-style="{height: '300px'}" ngf-select="upload($files, $invalidFiles,$newFiles)" ngf-drop="upload($files, $invalidFiles,$newFiles)" class="drop-box" 
        ngf-drag-over-class="'dragover'" ngf-multiple="true" ngf-allow-dir="true">
	<span ng-show="!files">Selecciona archivos o arrastralos aca para guardarlos</span>
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
				Tamaño maximo 5MB
			</div>
			<div class="uploadNombre">{{f.name}}</div>
    	</li> 
	</ul>

</div>

<style>
	.upload{list-style-type: none;padding: 0px;}
	.upload li{width: 124px;height: 100px;background-color: white;display: inline-block;margin: 5px;border: 1px solid #DEDEDE;position: relative;cursor: pointer;}
	.upload li img{width: 100%;height: 100%;object-fit: contain;position: absolute;}
	.uploadNombre{height: 25px;line-height: 25px;position: absolute;left: 0px;bottom: 0px;padding: 0px 5px;width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;background-color: white;}
	.progreso{width: 100%;height: 20px;background-color: #B7B5B5;position: absolute;left: 0px;top: 50%;-webkit-transform: translateY(-50%);-moz-transform: translateY(-50%);-o-transform: translateY(-50%);transform: translateY(-50%);border-radius: 10px;overflow: hidden;}
	.progresoEstado{height: 100%;position: absolute;left: 0px;top: 0px;border-radius: 10px;background-color: #545454;width: 10%;}
	.progresoAvance{height: 100%;width: 100%;color: white;position: absolute;left: 0px;top: 0px;text-align: center;z-index: 2;}
</style>