<?
function blog(){
	require("htma/index.php");
}

function add(){
	$sql = sprintf("INSERT INTO `blog_articulos` (`titulo`,`user`) VALUES (%s,%s)",
		varSQL($_POST['titulo']),
		varSQL(__dominio));
	$res = consulta($sql);
	header('Location: '.__url_real.'adm/blog/edt/'.$res['IDI'].'/');
}
function edt(){
	require('htma/editar.php');
}

function saveEdt(){
	if(isset($_POST['titulo'])){
		$q = sprintf("UPDATE `blog_articulos` SET `titulo` = %s, `contenido` = %s, `subtitulo` = %s WHERE `id` = %s",varSQL($_POST['titulo']),varSQL($_POST['contenido']),varSQL($_POST['subtitulo']),varSQL(base64_decode($_POST['key'])));
		if(consulta($q)){
			if(isset($_FILES['OG'])){
				/*upload cropped image*/
		        $iWidth = $iHeight = 600; // desired image result dimensions
	            // if no errors and size less than 250kb
	            if (! $_FILES['OG']['error'] && $_FILES['OG']['size'] < 800 * 1024) {
	                if (is_uploaded_file($_FILES['OG']['tmp_name'])) {
	                    // new unique filename
	                    $tempF = __upload_dir.'cache/';
	                    $sTempFileName = $tempF.md5(time().rand());
	                    @mkdir($tempF);
						@chmod($tempF,0777);

						$Up = __upload_dir."blog/ogs/";
						@mkdir($Up);
						@chmod($Up,0777);
						$fName = $Up.base64_decode($_POST['key']);
	                    // move uploaded file into cache folder
	                    move_uploaded_file($_FILES['OG']['tmp_name'], $sTempFileName);
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
	                        $sql = sprintf('UPDATE `blog_articulos` SET `og` = %s WHERE `id` = %s',varSQL($sExt),varSQL(base64_decode($_POST['key'])));
	                        consulta($sql);
	                        $img = $sResultFileName;
	                    }
	                }
	            }
				/*end upload cropped image*/
			}
			echo json_encode(array($img));
		}else{
			echo json_encode(array("status"=>'fail'));
		}
	}else{
		echo json_encode(array("status"=>'fail'));
	}
	
}