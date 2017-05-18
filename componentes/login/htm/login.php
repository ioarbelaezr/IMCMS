<!DOCTYPE html>
<html lang="en" style="height:100%;">
<head>
	<meta charset="UTF-8">
	<script type="text/javascript">
  WebFontConfig = {
    google: { families: [ 'Roboto:300,400:latin' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })(); </script>
</head>
<body>
	<?
		tituloHTML("Iniciar sesión");
		if($_SERVER['REQUEST_METHOD']=='POST'){
			login_user($_POST['user'],$_POST['pass'],(isset($_POST['activo'])&&$_POST['activo']=='on'));
			//dump($_POST);
			if(inicie_sesion()){
				if(isset($_GET['text1'])){
					header('Location: '.base64_decode($_GET['text1']));
				}else{
					header('Location: '.__url_real.'adm/');
				}
			}else{
				errLog("Nombre de usuario o contraseña incorrectos");
			}
		}
	?>
	<div class="login_page">
		<div class="contenedor">
			<div class="form">
				<img src="/componentes/login/res/logo64.png" alt="">
				<form method="post" action="<?=$_SERVER['REQUEST_URI']; ?>">
					<input required name="user" type="text" id="exampleInputEmail1" placeholder="Nombre de usuario">
					<input required name="pass" type="password" id="exampleInputPassword1" placeholder="Contraseña">
					<div class="checkbox text-left">
					    <label>
					    	<input name="activo" type="checkbox"> Mantener la sesión activa
					    </label>
					</div>
					<button type="submit" >Iniciar sesión</button>
				</form>
			</div>
		</div>
	</div>
	<style>
		body{background-color: #E2EEF4;padding: 0px;margin: 0px;height: 100%;}
		.login_page{height: 100%;width: 100%;position: relative;background-color: transparent;}
		.contenedor{max-width: 360px;background-color: white;margin: auto;position: relative;top: 50%;transform: translateY(-50%);width: 100%;box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);}
		.form{margin: 0 auto 100px;padding: 45px;text-align: center;padding-top: 15px;}
		.form input:not([type="checkbox"]){font-family: "Roboto", sans-serif;outline: 0;background: #f2f2f2;width: 100%;border: 0;margin: 0 0 15px;padding: 15px;box-sizing: border-box;font-size: 15px;}
		.form button{font-family: "Roboto", sans-serif;text-transform: uppercase;outline: 0;background: #4CAF50;width: 100%;border: 0;padding: 15px;color: #FFFFFF;font-size: 14px;-webkit-transition: all 0.3 ease;transition: all 0.3 ease;cursor: pointer;}
		.form button:hover{background: #43A047;color: white;}
	</style>
</body>
</html>