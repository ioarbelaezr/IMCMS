<?
//mb_internal_encoding('UTF-8');
require_once('../autoload.php');

$config = array(
	"base_url"=>__url_real.__path,
	"upload_folder"=>"uploads/",
	"base_upload_folder"=>__root.__path."uploads/",
	"base_thumbs_folder"=>__root.__path."uploads/thumbs/",
	"thumbs"=>"uploads/thumbs/",
	"keys"=>array(),
	'ext_img'       => array( 'JPG','jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg' ), //Images
	'ext_file'      => array( 'doc', 'docx', 'rtf', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'htm', 'html', 'xhtml', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'mdb', 'accdb', 'ppt', 'pptx', 'odt', 'ots', 'ott', 'odb', 'odg', 'otp', 'otg', 'odf', 'ods', 'odp', 'css', 'ai' ), //Files
	'ext_video'     => array( 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm" ), //Video
	'ext_music'     => array( 'mp3', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav' ), //Audio
	'ext_misc'      => array( 'zip', 'rar', 'gz', 'tar', 'iso', 'dmg' ),
	);

return array_merge(
	$config,
	array(
		'ext'=> array_merge(
			$config['ext_img'],
			$config['ext_file'],
			$config['ext_misc'],
			$config['ext_video'],
			$config['ext_music']
		)
	)
);