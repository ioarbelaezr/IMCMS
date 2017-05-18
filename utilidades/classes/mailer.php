<?

class mailer{
	var $sender;
	var $sender_name;
	var $asunto;
	var $mensaje;
	var $tags;
	var $apiKey = '2ef61710-10ed-49ed-ac58-22d6cf600889';
	var $api    = 'elasticemail';
	
	function establecerApi($api,$apikey){
		$this->api    = $api;
		$this->apiKey = $apikey;
	}
	function mailer($sender='no-responder@igiamedia.com.co',$sender_name='Igia media mobility'){
		$this->sender = $sender;
		$this->sender_name = $sender_name;
		$this->tags = array(uniqid());}
	function mensaje($asunto='Sin asunto',$mensaje=''){
		$this->mensaje = $mensaje;
		$this->asunto = $asunto;}
	function add_copia($to='',$nombre=''){
		if($to!=''){
			$_SESSION['destinatarios'][] = array(
				"email" => $to,
				"name"  => $nombre,
				"type"  => "to"
			);
		}}
	function enviar(){
		if(file_exists(__path.'email-template.html')){
			$txt = file_get_contents(__path.'email-template.html');
			$txt = str_replace(array('{mensaje}','{titulo}','{{__url_real}}'),array($this->mensaje,$this->asunto,__url_real),$txt);
		}else{
			$txt = $this->mensaje;
		}

		$this->enviar_mail($this->asunto,$txt,$this->sender,$this->sender_name,$_SESSION['destinatarios'],$this->tags);	
		$_SESSION['destinatarios']=array();
	}

	function tags($tags){
		$this->tags = $tags;
	}
	/*
	 *implementacion en mandrill
	 */
	private function enviar_mail($asunto,$mensaje,$sender,$sender_name,$destinatarios,$tags){
		if($this->api == 'mandrillApp'){
			$t = ($tags!='')?$tags:array(uniqid());
			try {
			    $mandrill = new Mandrill($this->apiKey);
			    $message = array(
			        'html' => $mensaje,
			        'subject' => $asunto,
			        'from_email' => $sender,
			        'from_name' => $sender_name,
			        'to' => $destinatarios,
			        'important' => true,
			        'track_opens' => true,
			        'track_clicks' => true,
			        'auto_text' => null,
			        'auto_html' => null,
			        'inline_css' => null,
			        'url_strip_qs' => null,
			        'preserve_recipients' => false,
			        'view_content_link' => null,
			        'tracking_domain' => 'mailer.igiamedia.com.co',
			        'signing_domain' => 'igiamedia.com.co',
			        'return_path_domain' => null,
			        'merge' => true,
			        'merge_language' => 'mailchimp',
			        'tags' => $t,
			        'metadata' => array('website' => 'www.igiamedia.com.co'),
			        'recipient_metadata' => array(
			            array(
			                'values' => array('user_id' => 123456)
			            )
			        )
			    );
			    $async = true;
			    $ip_pool = '';
			    $send_at = '';
			    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
			} catch(Mandrill_Error $e) {
			    echo "Impopsible enviar >>".$e;
			}
		}elseif($this->api == 'elasticemail'){
			require_once('ElasticEmailClient.php');
			ElasticEmailClient\ApiClient::SetApiKey($this->apiKey);
			//
			$EEemail = new ElasticEmailClient\Email();
			$recipients = array();
			foreach($destinatarios as $destinatario){
				$recipients[] = $destinatario['email'];	
			}
				        try{
	            $response = $EEemail->Send($asunto, $sender, $sender_name, null, null, null, null, null, null, $recipients, array(), array(), array(), array(), array(), null, null, $mensaje, '');		
	        }
	        catch (Exception $e){
	            echo 'Something went wrong: ', $e->getMessage(), '\n';
	            return;
	        }		
	        echo 'MsgID to store locally: ', $response->messageid, '\n'; // Available only if sent to a single recipient
	        echo 'TransactionID to store locally: ', $response->transactionid;
			//
		}	
	}
}


