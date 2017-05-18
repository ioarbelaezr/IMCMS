<?php

/*
 *detecta si un string esta en utf-8
 */
function is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}
/*
*consultas a la base de datos mysql
*/
   function consulta($sql=false,$F=false,$accesos=false){
        if ($sql==false){return false;}
        if(is_utf8($sql)){ $sql = utf8_decode($sql); }
        if(is_array($accesos) && $accesos!=false){
            $servidor          = (isset($accesos['servidor']))?$accesos['servidor']:__servidor;
            $base               = (isset($accesos['base']))?$accesos['base']:__base;
            $usuario           = (isset($accesos['usuario']))?$accesos['usuario']:__usuario;
            $contrasena     = (isset($accesos['contrasena']))?$accesos['contrasena']:__contrasena;
            
        }else{
            $servidor          = __servidor;
            $base               = __base;
            $usuario           = __usuario;
            $contrasena     = __contrasena;
        }
        $conector         = mysqli_connect($servidor , $usuario , $contrasena , $base);

        $resultado = mysqli_query($conector , $sql);   
        if ($F==false){
            $result['filas_afectadas'] = mysqli_affected_rows($conector);
            $result['IDI'] = mysqli_insert_id($conector); // Ultimo ID insertado
            $qr = $result;
        }else {
            if (!$resultado) {
                $qr = false;
            }else{
                $contador = 0;
                while ($fila = mysqli_fetch_assoc($resultado)){
                    $R['resultado'][$contador] = $fila;
                    $contador++;
                }
                $R['filas']           =  mysqli_num_rows($resultado);
                $R['filas_afectadas'] = mysqli_affected_rows($conector);
       
                if($R['filas']==0){
                    $qr = false;
                }else{
                    $qr = $R;
                }
            }
        }
        mysqli_close($conector);
        return $qr;
    }

/*
obtiene un campo desde la base de datos
 */
function obtener_campo($sql, $campo=false){
    $datos    = consulta($sql,true);
    if (!$datos) {
        return false;
    }else {
        if($campo==false){
            $datos = array_shift ($datos['resultado'][0]);
        }else{
            $datos    = $datos['datos'][0][$campo];
        }
        return    $datos;
    }
}
/*
*textarea con version legible para  humanos de una variable
*/
function dump($string=""){
	echo '<textarea style="width:700px; margin:auto;" rows="15">'.print_r($string,true).'</textarea>';
}
/*
*formateo de variables para ser procesadas en consultas sql
*/
function varSQL($cadena, $tipo="text"){
    $cadena = (!get_magic_quotes_gpc()) ? addslashes($cadena) : $cadena;
    switch ($tipo) {
        case "text":
            $cadena = ($cadena != "") ? "'" . $cadena . "'" : "NULL";
            break;
        case "int":
            $cadena = ($cadena != "") ? intval($cadena) : "NULL";
            break;
        case "double":
            $cadena = ($cadena != "") ? "'" . doubleval($cadena) . "'" : "NULL";
            break;
        case "date":
            $cadena = ($cadena != "") ? "'" . $cadena . "'" : "NULL";
            break;
    }
    return $cadena;
}
/*
 *carga de la plantilla del sitio y del sitio como tal
 */
function _load(){   
    //verificar la session 
    if(isset($_GET['adm'])&&!inicie_sesion()){
        if(isset($_COOKIE['sessionHash'])&&$_COOKIE['sessionHash']!=''){
            login_user_hash($_COOKIE['sessionHash']);
            _load();
            exit;
        }
        if(isset($_GET['ajax'])){
            echo json_encode(array('error'=>'session'));
            exit;
        }else{
            header('Location: '.__url_real.'xlogin/login/'.base64_encode($_SERVER['REQUEST_URI']).'/');
            exit;
        }
    }

    if(isset($_GET['adm'])&&inicie_sesion()){
        //verifica si el usuario que inicio la sesion tiene permiso para acceder
        if(logueado::permitirAcceso()){//logueado::tieneAcceso()
            require('plantillas/admin/admin.php');
        }else{
            header('Location: '.__url_real.'xlogin/login/'.base64_encode($_SERVER['REQUEST_URI']).'/');
            exit;
        }
    }else{
        if (isMobile()) { 
            if(file_exists(__path.'index.mobile.php')){
                require (__path.'index.mobile.php');
            }else{ 
                require (__path.'index.php');
            }
        }else{
            require (__path.'index.php');
        }
    }
}
/*
*carga los contenidos de los sitios
*/
function cargar_contenidos(){ 
    $mod=(isset($_GET['modulo']))?$_GET['modulo']:'index';
    //habilitar limpiado de contenidos
    $c = false;

	$func=(isset($_GET['funcion']))?$_GET['funcion']:$mod;


    if(isset($_GET['adm'])){
        $localreq=__path.'componentes/'.$mod.'/index.admin.php';
        $globalreq='componentes/'.$mod.'/index.admin.php';
    }else{
        $localreq=__path.'componentes/'.$mod.'/index.php';
        $globalreq='componentes/'.$mod.'/index.php';
    }

    if(file_exists($localreq)){
        require($localreq);
    }else{
        if(file_exists($globalreq)){
            require($globalreq);
        }else{
            errLog('ERROR<<< No se reconoce el modulo que debia ejecutarse');
        }
    }

    $funcc=$func;
    if(isset($_GET['ajax'])){
        ob_clean(); 
        //habilitar cors
        header("Access-Control-Allow-Origin: *");
    }
    //habilitar guiones medios en las url
    $funcc = str_replace('-', '_', $funcc);
    if (function_exists($funcc)) {
        $funcc();
    }else{
        errLog('ERROR<<< No se reconoce la acci&oacute;n que debia ejecutarse');
    }

                   
    if(isset($_GET['ajax'])){
        require ('processor.php');
        exit();
    }
}
/*
 *Cargar el linkManager de los componentes del sistema
 */
function LM(){
    $mod=(isset($_GET['modulo']))?$_GET['modulo']:'index';
    $func=(isset($_GET['funcion']))?$_GET['funcion']:$mod;

    $localreq=__path.'componentes/'.$mod.'/LM.php';
    $globalreq='componentes/'.$mod.'/LM.php';

    if(file_exists($localreq)){
        require($localreq);
    }else{
        if(file_exists($globalreq)){
            include_once($globalreq);
            if(function_exists($func)){
                echo json_encode(Encoding::toUTF8($func($res)));
            }else{
                exit();
            }
        }else{
            exit();
        }
    }
}

/*
*obtiene las direcciones ip de los visitantes
*/
function getUserIP(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

/*
 *version minimificada de un codigo html => https://gist.github.com/tovic/d7b310dea3b33e4732c0
 */
function minify_html($input){
    if(trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
        return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if(strpos($input, ' style=') !== false) {
        $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
            return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
        }, $input);
    }
    return preg_replace(
        array(
            // t = text
            // o = tag open
            // c = tag close
            // Keep important white-space(s) after self-closing HTML tag(s)
            '#<(img|input)(>| .*?>)#s',
            // Remove a line break and two or more white-space(s) between tag(s)
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
            // Remove HTML comment(s) except IE comment(s)
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
        ),
        array(
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            ""
        ),
    $input);
}

/*
 * version minimificada de css => https://gist.github.com/tovic/d7b310dea3b33e4732c0
 */
function minify_css($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
        array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
        ),
    $input);
}

/*
 *version minimificada de javascript => https://gist.github.com/tovic/d7b310dea3b33e4732c0
 */
function minify_js($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ),
        array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ),
    $input);
}

/*
*Limpia espacios, saltos de linea y comentarios en cadenas html
*/
function limpiahtml($salida){
    $regex = "/<script.*?>.*?<\/script>/ims";
    preg_match_all($regex, $salida, $matches, null, 0);
    if(count($matches)>0){
        $tags2add = ""; 
        foreach($matches[0] as $tag){
            if(!strstr($tag, "inmovil")){
                $retag = $tag;
                $tag = JSMin::minify($tag);
                $tags2add .= $tag;
                $salida = str_replace($retag, "", $salida);
            }
        }
    }               
    $salida = minify_html($salida);


    $salida = str_replace(array("</body>"),array($tags2add."</body>"),$salida);
    return $salida;
    echo preg_last_error();
}
/*
*funcion que recupera el pais de una IP del visitante
*/
function getPais($ip = false){
    $ip = ($ip==false)?getUserIP():$ip;
    $key="7b471597a8e15e665536cef21de5b54ffc5a38a7";
    $data= file_get_contents("http://api.db-ip.com/addrinfo?addr=$ip&api_key=$key");
    $data = json_decode($data,true);
    return ($data['country']);
}
/*
*funcion para convertir caracteres especiales a codigos html
*/
function to_html($cadena){
    $busqueda = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","¿");
    $reemplazo= array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;",
        "&ntilde;","&Ntilde;","&#191;");
    $resultado = str_replace($busqueda, $reemplazo, $cadena);
    return $resultado;
}
/*
 *Inicia sesion a un usuario en el sistema
 */
function login_user($user,$pass,$activo=false,$h=false){
    $c = ($h)?$pass:md5($pass);
    $sql = sprintf("SELECT * FROM `login` WHERE `email` = %s AND `contrasena` = %s AND `cloud` = %s LIMIT 1",
        varSQL($user),
        varSQL($c),
        varSQL(__sistema));
    $datos = consulta($sql,true);
    if($datos != false){
        $_SESSION['usuario']    = $datos['resultado'][0]['id'];
        $_SESSION['session_key']= md5($pass);
        //obtener los datos adicionales del usuario en cuestion de acuerdo al perfil de usuario que pertenesca
        $sql = sprintf("SELECT * FROM `%s` WHERE `id_login` = %s",(obtener_campo(sprintf("SELECT `tabla` FROM `usuarios_perfiles_link` WHERE `id_perfil` = %s",varSQL($datos['resultado'][0]['perfil'])))),varSQL($datos['resultado'][0]['id']));
        $datos1 = consulta($sql,true);
        if($datos1!=false){
            unset($datos1['resultado'][0]['id']);
            $d = array_merge($datos['resultado'][0],$datos1['resultado'][0]);
            $_SESSION['data_login'] = $d;
        }else{
            $_SESSION['data_login'] = $datos['resultado'][0];
        }
        //obteniendo los detalles de los modulos disponibles para cada usuario
        $con = sprintf("SELECT * FROM `componentes_instalados` WHERE `id` IN(SELECT `id_componente` FROM `usuarios_perfiles_permisos` WHERE `id_perfil` = %s)",varSQL($datos['resultado'][0]['perfil']));
        $r = consulta($con,true);
        //detalles del perfil
        $p = consulta(sprintf("SELECT `nombre`,`p` FROM `usuarios_perfiles` WHERE `id` = %s",varSQL($datos['resultado'][0]['perfil'])),true);
        $_SESSION['usuario_perfil']      = $p['resultado'][0];
        $_SESSION['usuario_componentes'] = $r['resultado'];
        $detalles = array("estado"=>"ok","detalles"=>"");
    }else{
        $_SESSION['usuario']='';
        $_SESSION['session_key']='';
        $detalles = array("estado"=>"error","detalles"=>"no_log_data","mensaje"=>"Nombre de usuario o contrase&ntilde;a incorrecta");
    }
    return $detalles;
}

function login_user_hash($hash){
    $data = consulta(sprintf("SELECT `user`,`pass` FROM `sessiones_activas` WHERE `hash` = %s", varSQL($hash)),true);
    if($data!=false){
        login_user($data['resultado'][0]['user'],$data['resultado'][0]['pass'],false,true);
        return true;
    }else{
        return false;
    }
}
/*
 *Registrar un usuario en la tabla login
 */
function registrar_usuario($datos = array()){
    if(empty($datos)||!($datos['perfil']!=''&&$datos['email']!=''&&$datos['contrasena']!='')){return false;}
    $sql = sprintf("INSERT INTO `login` (`cloud`,`perfil`,`nombres`,`apellidos`,`email`,`telefono`,`contrasena`) SELECT * FROM (SELECT %s,%s,%s,%s,%s,%s,%s) AS `tmp` WHERE NOT EXISTS (SELECT `email` FROM `login` WHERE `email` = %s AND `cloud` = %s)",varSQL(__sistema),varSQL($datos['perfil']),varSQL((!isset($datos['nombres']))?'':$datos['nombres']),varSQL((!isset($datos['apellidos']))?'':$datos['apellidos']),varSQL($datos['email']),varSQL((!array_key_exists('telefono',$datos))?'':$datos['telefono']),varSQL(md5($datos['contrasena'])),varSQL($datos['email']),varSQL(__sistema));
    return consulta($sql);
}
/*
 *verifica si inicie sesion en el sistema
 */
function inicie_sesion(){
    return (isset($_SESSION['usuario'])&&$_SESSION['usuario']!=''&&isset($_SESSION['session_key']));
}

/*
 *errores del sistema
 */
function errLog($text='Error'){
    echo "<div class='text-center' style='color:red;background-color:white;'>".$text."</div>";
}

/*
 *detectar dispositivos moviles
 */
function isMobile(){
    $detect = new Mobile_Detect();   
    return $detect->isMobile();
}

/*
 *Convertir un string a un formato compatible con urls
 */
function urlTitulo($str, $replace=array(), $delimiter='-') {
	setlocale(LC_ALL, 'en_US.UTF8');
    if( !empty($replace) ) {
    	$str = str_replace((array)$replace, ' ', $str);
 	}
    $str  = (is_utf8($str))? $str : utf8_encode($str);
 	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
 	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
 	$clean = strtolower(trim($clean, '-'));
 	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
 	return $clean;
}

/*
 *Limpia el html de una cadena y permite recortarla
 */
 
function limpiarHTML($cadena,$largo=0){
	$cadena	= preg_replace(array('@<style[^>]*?>.*?</style>@si','@<[\/\!]*?[^<>]*?>@si'),array("",""),$cadena);
	if($largo!=0){
		$cadena = substr ($cadena, 0,$largo);
	}
	return $cadena;
}

/*
 *convierte un timestamp de mysql a un formato legible
 */
function time2string($timestamp=''){
    $meses = array('01' => 'enero','02'=>'febrero','03'=>'marzo','04'=>'abril','05'=>'mayo','06'=>'junio','07'=>'julio','08'=>'agosto','09'=>'septiembre','10'=>'octubre','11'=>'noviembre','12'=>'diciembre');
    $partes = preg_split('/[\s,-]+/',$timestamp);
    $fecha = $partes[2].' de '.$meses[$partes[1]].' de '.$partes[0].' a las '.$partes[3];
    return $fecha;
}

/*
 *Establece el titulo del documento html resultante
 */

function tituloHTML($titulo){
    $_SESSION['configuracion']['titulo'] = $titulo;
}

function descripcionHTML($des){
    $_SESSION['configuracion']['descripcion'] = $des;
}

function ogImage($res){
    $_SESSION['configuracion']['og'] = $res;
}
/* 
 *obtiene el contenido de una pagina en especial
 */
function pagina($id){
    $pg = consulta(sprintf("SELECT `contenido` FROM `contenidos` WHERE `id` = %s LIMIT 1",varSQL($id)),true);
    return $pg['resultado'][0]['contenido'];
}

function salir(){
    exit();
}


/*
 *editor de contenidos
 */
function editor($selector='textarea'){
    if(!defined('__editor_script')){
        echo '<script src="/frontend/tinymce/tinymce.min.js"></script>';
        define('__editor_script', 'true');
    }
    ?>
        <script type="text/javascript">
        $('document').ready(function(){
            tinymce.init({
            selector: "<? echo $selector; ?>",
            language : "es",
            theme: "modern",
            plugins: [
                "advlist noneditable autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern imagetools fontawesome example "
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons fontawesome code",
            image_advtab: true,
            content_style : '.mceNonEditable{background-color:red;}',
            height : "480",
            relative_urls: false,

            filemanager_title:"Administrador de enlaces",
            external_plugins: {"filemanager":"/fm/filemanager.js"},
            paste_enable_default_filters: false,
            extended_valid_elements : "script[inmovil|language|src|async]",
            content_css: '//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
            fontsize_formats: "6px 8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 33px 36px 39px 42px 45px 48px 50px",
            templates : [
                {title:'Anuncio de Adsense',url:'/sites/IMCMS/anuncio.html'}
            ],
            setup : function(ed) {
                  ed.on('change', function(e) {
                     //alert("Cambio");
                  });
            }
        });
        });
        
        
        
        </script>
    <?php
}

/*
 *Permite copiar un directorio completo de forma recursiva
 */

function copiar ($desde, $hasta){  
    mkdir($hasta, 0777);  
    $this_path = getcwd();  
    if(is_dir($desde)) {  
        chdir($desde);  
        $handle=opendir('.');  
        while(($file = readdir($handle))!==false){  
            if(($file != ".") && ($file != "..")){  
                if(is_dir($file)){  
                    copiar($desde.$file."/", $hasta.$file."/");  
                    chdir($desde);  
                }  
                if(is_file($file)){  
                    copy($desde.$file, $hasta.$file);  
                }  
            }  
        }  
        closedir($handle);  
    }  
}  

/*
 *Permite eliminar un directorio completo 
 */

function eliminarDirectorio($directorio){
    foreach(glob($directorio . "/*") as $archivos_carpeta){
        if (is_dir($archivos_carpeta)){
            eliminarDirectorio($archivos_carpeta);
        }
        else{
            unlink($archivos_carpeta);
        }
    }
    rmdir($directorio);
}

/*
 *genera el listado de categorias
 */

function listarCategoriasComponente($componente){
    $sql = sprintf("SELECT * FROM `categorias` WHERE `componente` = %s AND `nivel` = 0",varSQL($componente));
    $c   = consulta($sql,true);
    if($c!=false){
        $c = $c['resultado'][0]['id'];
        $listado = array();
        lcc($c,$listado);
        return $listado;
    }else{
        return false;
    }
}

function lcc($ca,&$lista){
    $sql = sprintf("SELECT * FROM `categorias` WHERE `id_padre` = %s",varSQL($ca));
    $c   = consulta($sql,true);
    if($c!=false){
        foreach($c['resultado'] as $i){   
            $lista[] = $i;
            lcc($i['id'],$lista);
        }
    }
}

/*
 *Genera repeticiones de caracteres  
 */

function repetir($caracteres,$repeticiones=1){
    for ($i=0; $i < $repeticiones; $i++) { 
        echo $caracteres;
    }
}

function post($url,$datos,$extras=false){
    $s = '';
    foreach($datos as $key=>$value) { $s .= $key.'='.$value.'&'; }
    rtrim($s, '&');
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($datos));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $s);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($extras != false && is_array($extras)){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $extras);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function crear_img($imgfile, $imgthumb, $newwidth, $newheight = null, $option = "crop"){
    $result = false;
    if(file_exists($imgfile) || strpos($imgfile,'http')===0){
        $timeLimit = ini_get('max_execution_time');
        set_time_limit(30);
        if (strpos($imgfile,'http')===0 || image_check_memory_usage($imgfile, $newwidth, $newheight)){
            $magicianObj = new imageLib($imgfile);
            $magicianObj->resizeImage($newwidth, $newheight, $option);
            $magicianObj->saveImage($imgthumb, 80);
            $result = true;
        }
        set_time_limit($timeLimit);
    }
    return $result;
}


function folder_info($path){
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/') . '/';
    $files_count = 0;
    $folders_count = 0;
    foreach ($files as $t)
    {
        if ($t != "." && $t != ".." && $t != "thumbs")
        {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile))
            {
                list($size,$tmp,$tmp1) = folder_info($currentFile);
                $total_size += $size;
                $folders_count ++;
            }
            else
            {
                $size = filesize($currentFile);
                $total_size += $size;
                $files_count++;
            }
        }
    }

    return array($total_size,$files_count,$folders_count);
}

function fix_filename($str, $transliteration, $convert_spaces = false, $replace_with = "_", $is_folder = false){
    if ($convert_spaces){
        $str = str_replace(' ', $replace_with, $str);
    }

    if ($transliteration){
        if (function_exists('transliterator_transliterate'))
        {
             $str = transliterator_transliterate('Accents-Any', utf8_encode($str));
        }
        else
        {
            $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        }

        $str = preg_replace("/[^a-zA-Z0-9\.\[\]_| -]/", '', $str);
    }

    $str = str_replace(array( '"', "'", "/", "\\" ), "", $str);
    $str = strip_tags($str);

    if (strpos($str, '.') === 0 && $is_folder === false){
        $str = 'file' . $str;
    }

    return trim($str);
}


//
function eliminarDir($carpeta){
    foreach(glob($carpeta . "/*") as $archivos_carpeta){ 
        if (is_dir($archivos_carpeta)){
            eliminarDir($archivos_carpeta);
        }
        else{
            unlink($archivos_carpeta);
        }
    }
    rmdir($carpeta);
}

function image_check_memory_usage($img, $max_breedte, $max_hoogte){
    if (file_exists($img)){
        $K64 = 65536; // number of bytes in 64K
        $memory_usage = memory_get_usage();
        $memory_limit = abs(intval(str_replace('M', '', ini_get('memory_limit')) * 1024 * 1024));
        $image_properties = getimagesize($img);
        $image_width = $image_properties[0];
        $image_height = $image_properties[1];
        if (isset($image_properties['bits'])) 
            $image_bits = $image_properties['bits']; 
        else 
            $image_bits = 0;
        $image_memory_usage = $K64 + ($image_width * $image_height * ($image_bits) * 2);
        $thumb_memory_usage = $K64 + ($max_breedte * $max_hoogte * ($image_bits) * 2);
        $memory_needed = intval($memory_usage + $image_memory_usage + $thumb_memory_usage);

        if ($memory_needed > $memory_limit){
            ini_set('memory_limit', (intval($memory_needed / 1024 / 1024) + 5) . 'M');
            if (ini_get('memory_limit') == (intval($memory_needed / 1024 / 1024) + 5) . 'M'){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return true;
        }
    }
    else{
        return false;
    }
}

function lower($str){
    if (function_exists('mb_strtoupper')){
        return mb_strtolower($str);
    }
    else{
        return strtolower($str);
    }
}

