
<?php
$textos=ob_get_contents();
ob_clean();
//correjir las rutas a los recursos de cada sitio
$textos = str_replace("/recursos/","/".__path."recursos/", $textos);

$header = "<head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <!--<link rel=\"canonical\" href=\"".__url_canonica."\"/>-->
    <meta property=\"og:url\" content=\"".__url_canonica."\" />
    <meta property=\"og:type\" content=\"website\"/>
    <meta name=\"robots\" content=\"INDEX,FOLLOW,ARCHIVE\"/>
    <meta name=\"Distribution\" content=\"global\"/>
    <meta name=\"language\" content=\"es\"/>
    <meta name=\"rating\" content=\"general\"/>
    <meta name=\"description\" content=\"".$_SESSION['configuracion']['descripcion']."\"/>
    <meta name=\"keywords\" content=\"".(__palabras_clave)."\"/>
    <meta name=\"Generator\" content=\"IMCMS BY @ioarbelaezr\"/><!--POR FAVOR NO MODIFIQUES ESTA LINEA-->
    <meta name=\"Author\" content=\"@ioarbelaezr\"/>
    <meta property=\"og:title\" content=\"".$_SESSION['configuracion']['titulo']."\"/>
    <meta property=\"og:site_name\" content=\"".$_SESSION['configuracion']['titulo']."\"/>
    <meta property=\"og:description\" content=\"".$_SESSION['configuracion']['descripcion']."\"/>
    <meta name=\"twitter:site\" content=\"@ioarbelaezr\">
    <meta name=\"twitter:creator\" content=\"@ioarbelaezr\">
    <meta name=\"twitter:title\" content=\"".$_SESSION['configuracion']['titulo']."\">
    <meta name=\"twitter:description\" content=\"".$_SESSION['configuracion']['descripcion']."\">
    <meta name=\"twitter:card\" content=\"summary_large_image\">
    <title>".$_SESSION['configuracion']['titulo']."</title>";
if ($_SESSION['configuracion']['og']!='') {
  $header .= "
  <meta name=\"twitter:image:src\" content=\"".$_SESSION['configuracion']['og']."\">
  <meta property=\"og:image\" content=\"".$_SESSION['configuracion']['og']."\"/>";
}
if (file_exists(__path."recursos/icons/favicon.ico")){
  $header .= "
  <link rel=\"icon\" type=\"image/x-icon\" href=\"/".__path."recursos/icons/favicon.ico\" />"; 
}
if($_SESSION['configuracion']['bootstrap']||isset($_GET['adm'])){
  $header .= "<link inmovil rel=\"stylesheet\" type=\"text/css\" href=\"/frontend/bootstrap/css/bootstrap.min.css\"><script src=\"/frontend/bootstrap/js/bootstrap.min.js\"></script>";
}
$header .= "<script inmovil src=\"/frontend/jquery/jquery-2.1.4.min.js\"></script><script src=\"/frontend/js_libs/funciones_genericas.js\"></script>";
$textos=str_replace("<head>", $header, $textos);
$foo = "<link rel=\"stylesheet\" href=\"/frontend/css/animate.css\"><link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\">";
$textos = str_replace('</body>',$foo.'</body>',$textos);
if(file_exists(__path.'manifest.php')){
  $manifiesto = "<script>!function(){var e=document.createElement('iframe');e.setAttribute('style','display:none'),e.setAttribute('src','/manifest/'),document.body.appendChild(e)}();</script>";
  $textos = str_replace('</body>',$manifiesto.'</body>',$textos);
}




$textos = limpiahtml($textos);
$textos = to_html($textos);
//cache::set($textos);
echo utf8_encode($textos);
?>