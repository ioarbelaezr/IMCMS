<?
function admUsuarios(){
	require('htma/perfiles.php');
}

function usuarios(){
	require('htma/usuariosPerfil.php');
}

function actualizarPerfil(){
	require('htma/perfil.php');
}

function perfilPermisos(){
    require('htma/permisos.php');
}

function actualizarPermisos(){
    if($_POST['accion']==1){
        consulta(sprintf('INSERT INTO `usuarios_perfiles_permisos` (`id_perfil`,`id_componente`) VALUES(%s,%s)',varSQL($_POST['perfil']),varSQL($_POST['componente'])));
    }else{
        consulta(sprintf('DELETE FROM `usuarios_perfiles_permisos` WHERE `id_perfil` = %s AND `id_componente` = %s',varSQL($_POST['perfil']),varSQL($_POST['componente'])));
    }
}

function guardarImagenPerfil(){
	if (! $_FILES['perfil']['error']) {
        if (is_uploaded_file($_FILES['perfil']['tmp_name'])) {
            //obtener imagen del usuario actiual el usuario
            $sql = sprintf("SELECT `imagen` FROM `login` WHERE `cloud` = %s AND `id` = %s LIMIT 1",
            varSQL(__sistema),
            varSQL(base64_decode($_POST['user'])));
            $datos = consulta($sql,true);
            $imgu = $datos['resultado'][0]['imagen'];
            //fin
        	$iWidth = $iHeight = $_POST['w'];
            // new unique filename
            $tempF = __root.'imagenesDePerfil/cache/';
            $sTempFileName = $tempF.md5(time().rand());
            @mkdir($tempF);
			@chmod($tempF,0777);

			$Up = __root.'imagenesDePerfil/';
			@mkdir($Up);
			@chmod($Up,0777);
			$idr = uniqid();
			$fName = $Up.$idr;
            // move uploaded file into cache folder
            move_uploaded_file($_FILES['perfil']['tmp_name'], $sTempFileName);
            // change file permission to 644
            @chmod($sTempFileName, 0644);
            if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                $aSize = getimagesize($sTempFileName); // try to obtain image info
                if (!$aSize) {
                    @unlink($sTempFileName);
                    return;
                }
                // check for image type
                switch($aSize[2]) {
                    case IMAGETYPE_JPEG:
                        $sExt = '.jpg';
                        // create a new image from file
                        $vImg = @imagecreatefromjpeg($sTempFileName);
                        break;
                    case IMAGETYPE_PNG:
                        $sExt = '.png';
                        // create a new image from file
                        $vImg = @imagecreatefrompng($sTempFileName);
                        break;
                    default:
                        @unlink($sTempFileName);
                        return;
                }
                if($imgu!=''){
                	@unlink(__root.'imagenesDePerfil/'.$imgu);
                }
                // create a new true color image
                $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );
                // copy and resize part of an image with resampling
                $scale = $_POST['Nw']/$_POST['Rw'];
                $x1    = (int)($_POST['x1']*$scale);
                $y1    = (int)($_POST['y1']*$scale);
                $w     = (int)($_POST['w']*$scale);
                $h     = (int)($_POST['h']*$scale);
                imagecopyresampled($vDstImg, $vImg, 0, 0, $x1, $y1, $iWidth, $iHeight, $w, $h);

                // define a result image filename
                $sResultFileName = $fName.$sExt;
                if(file_exists($sResultFileName)){
                	@unlink($sResultFileName);
                }
                // output image to file
                imagejpeg($vDstImg, $sResultFileName, 100);
                @unlink($sTempFileName);
                $sql = sprintf('UPDATE `login` SET `imagen` = %s WHERE `id` = %s',varSQL($idr.$sExt),varSQL(base64_decode($_POST['user'])));
                consulta($sql);
                if($_SESSION['data_login']['id']===base64_decode($_POST['user']))
                    $_SESSION['data_login']['imagen'] = $idr.$sExt;
				echo json_encode(array('imagen'=>($idr.$sExt)));               
            }
        }
    }
}

function eliminarImagenPerfil(){
    //obtener imagen del usuario actiual el usuario
    $sql = sprintf("SELECT `imagen` FROM `login` WHERE `cloud` = %s AND `id` = %s LIMIT 1",
    varSQL(__sistema),
    varSQL(base64_decode($_POST['user'])));
    $datos = consulta($sql,true);
    $imgu = $datos['resultado'][0]['imagen'];
    //fin
	$sql = sprintf("UPDATE `login` SET `imagen` = NULL WHERE `id` = %s",varSQL(base64_decode($_POST['user'])));
	consulta($sql);
	@unlink(__root.'imagenesDePerfil/'.$imgu);
    if($_SESSION['data_login']['id']===base64_decode($_POST['user']))
	   $_SESSION['data_login']['imagen'] = '';
}

function actualizarDatos(){
	$sql = sprintf("UPDATE `login` SET `nombres` = %s, `apellidos` = %s, `email` = %s, `telefono` = %s WHERE `id` = %s",varSQL($_POST['nombres']),varSQL($_POST['apellidos']),varSQL($_POST['email']),varSQL($_POST['telefono']),varSQL(base64_decode($_POST['user'])));
	consulta($sql);
    if($_SESSION['data_login']['id']===base64_decode($_POST['user'])){
    	$_SESSION['data_login']['nombres']   = $_POST['nombres'];
    	$_SESSION['data_login']['apellidos'] = $_POST['apellidos'];
    	$_SESSION['data_login']['email']     = $_POST['email'];
    	$_SESSION['data_login']['telefono']  = $_POST['telefono'];
    }
}

function actualizarContrasena(){
	if(md5($_POST['contrasena'])===$_SESSION['data_login']['contrasena']||$_SESSION['data_login']['id']!=base64_decode($_POST['user'])){
		$sql = sprintf("UPDATE `login` SET `contrasena` = %s WHERE `id` = %s",varSQL(md5($_POST['nContrasena'])),varSQL(base64_decode($_POST['user'])));
		consulta($sql);
		echo "Se ha actualizado tu contraseña";
	}else{
		echo "La contraseña que ingresaste es incorrecta";
	}
}

function nuevoPerfil(){
	$sql = sprintf("INSERT INTO `usuarios_perfiles` (`nombre`,`cloud`) VALUES(%s,%s)",varSQL($_POST['nombre']),varSQL(__sistema));
	$r = consulta($sql);
	echo json_encode($r);
}

function eliminarPerfil(){
	$sql = sprintf("DELETE FROM `usuarios_perfiles` WHERE `id` = %s",varSQL($_POST['id']));
	consulta($sql);
}

function crearUsuario(){
	$sql = sprintf("INSERT INTO `login` (`nombres`,`apellidos`,`email`,`telefono`,`contrasena`,`cloud`,`perfil`) VALUES(%s,%s,%s,%s,%s,%s,%s)",varSQL($_POST['nombres']),varSQL($_POST['apellidos']),varSQL($_POST['email']),varSQL($_POST['telefono']),varSQL(md5($_POST['contrasena'])),varSQL(__sistema),varSQL($_POST['perfil']));
	$r = consulta($sql);
	echo json_encode($r);
}

function eliminarUsuario(){
	$sql = sprintf("DELETE FROM `login` WHERE `id` = %s",varSQL($_POST['id']));
	consulta($sql);
}