<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2018-06-26 03:57:16
 * @Last Modified by:   Eka Syahwan
 * @Last Modified time: 2018-08-12 23:18:44
 */
date_default_timezone_set('Asia/Jakarta'); //http://php.net/manual/en/timezones.php
define('SENDINBOX_PATH', realpath(dirname(__FILE__)));
error_reporting(E_PARSE); // Error engine - always ON!
ini_set('display_errors', FALSE); // Error display - OFF in production env or real server
ini_set('log_errors', TRUE); // Error logging
ini_set('error_log', SENDINBOX_PATH.'/Result/sendinbox-error.txt'); // Logging file
require_once("autoload.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Sendinbox extends Config
{
    function __construct()
    {
     	$this->modules 	= new Modules; 
    	if($this->config_sender()['sender']['info_update']){

    		 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/radenvodka/Sendinbox-/master/versi');
			 curl_setopt($ch, CURLOPT_HEADER, 0);
			 ob_start();
			 curl_exec($ch);
			 $file = preg_replace("/[\n\r]/","", ob_get_clean());
			 curl_close($ch);

    		if( $file > $this->modules->versi()['issue'] ){
    			die($this->modules->color("red","[!] PEMBARUAN SUDAH TERSEDIA , DOWNLOAD DI BMARKET.OR.ID\r\n[+] Issue sekarang : ".$this->modules->versi()['issue']." | Issue Baru : ".$file));
    		}

    	}

     	$this->modules->cover();
     	$this->data 	= $this->modules->required();
     	$this->start();
   	}
   	function builtcenter($text = ""){
   		$s = 32;
		for ($yt=0; $yt <($s-strlen($text)); $yt++) { 
			$com .= ".";
		}
		return $text." ".$com;
   	}
   	function start(){
   		echo "\r\n";
   		$email 		= $this->data;
   		$totalmail 	= count($email);

   		$config_smtp 	= $this->config_smtp(); 
   		$config_sender 	= $this->config_sender();

   		$send_count 	= 1;
   		$send_to 		= 1;
   		
   		echo "[+] ".$this->modules->color("string","========================================================")." [+]\r\n";
   		echo "[+] ".$this->modules->color("string","           [ S E N D I N B O X - P R O V 2 ]")."\r\n";
   		echo "[+] ".$this->modules->color("string","----------------------------------------------------------")."\r\n";
   		echo "[+] ".$this->modules->color("red","Selalu gunakan sendinbox yang legal. jika kamu masih numpang")."\r\n";
   		echo "[+] ".$this->modules->color("white","kami akan kasih kamu 20% diskon produk sendinbox")."\r\n";
   		echo "[+] ".$this->modules->color("white","suruh kawan mu (pembeli legal) untuk membelikan sendinbox untuk mu.")."\r\n";
   		echo "[+] ".$this->modules->color("white","untuk mu. itu lebih baik dari pada joinan sungkan minta update.")."\r\n";
   		echo "[+] ".$this->modules->color("green","** 1 kawan hanya bisa request 1x untuk diskon 20% **")."\r\n";
   		echo "[+] ".$this->modules->color("string","========================================================")." [+]\r\n";
   		foreach ($this->config_sender()['sender'] as $key => $value) {
   			if(!is_array($value)){
   				if($value == TRUE && !is_numeric($value)){
   					echo $this->modules->color("green",'[i] ').$this->modules->color("nevy",$this->builtcenter("$key"))." ".$this->modules->color("bggreen","TRUE")."\r\n";
   				}else if($value == FALSE && !is_numeric($value)){
   					echo $this->modules->color("green",'[i] ').$this->modules->color("nevy",$this->builtcenter("$key"))." ".$this->modules->color("bgred","FALSE")."\r\n";
   				}else{
   					echo $this->modules->color("green",'[i] ').$this->modules->color("nevy",$this->builtcenter("$key"))." ".$this->modules->color("bggreen",$value)."\r\n";
   				}
   			}
   		}
   		echo "[+] ".$this->modules->color("string","========================================================")." [+]\r\n";

   		while (TRUE) {
   			foreach ($config_smtp as $key => $smtp) {
				foreach ($email as $key => $elist) {
		   			
		   			$emailist[] = array(
		   				'email' 	=> $elist,
		   				'line' 		=> ($key+1), 
		   				'totalmail' => $totalmail,
		   				'offcount' 	=> count($email),
		   			);

		   			unset($email[$key]);
		   			if($send_count == $config_sender['sender']['rotate']){
		   				$send_count = 1;
		   				break;
		   			}
		   			$send_to++; 
		   			$send_count++;
		   		}

		   		$smtp['compose']['content'] 	= $this->modules->shuffle_assoc($smtp['compose']['content']['random']);
		   		$smtp['compose']['frnameem'] 	= $smtp['compose']['content'];

		   		$r_fromname =  $smtp['compose']['frnameem']['fromname'];

		   		if($config_sender['sender']['manipulation_subject']){
		   			$r_subject = $this->modules->enc_subject($this->modules->alias($smtp['compose']['content']['subject']));
		   		}else{
		   			$r_subject = $this->modules->alias($smtp['compose']['content']['subject']);
		   		}

		   		if($config_sender['sender']['manipulation_fromname']){
		   			$r_fromname = $this->modules->enc_subject( $this->modules->alias($r_fromname) );
		   		}else{
		   			$r_fromname = $this->modules->alias($r_fromname);
		   		}

		   		$send['mailer']['total'] 	= $send_to;
		   		$send['mailer']['emailist'] = $emailist;
		   		$send['mailer']['config'] = array(
		   			'from_name' 	=> $r_fromname,
		   			'from_email' 	=> $smtp['compose']['frnameem']['fromemail'], 
		   			'subject' 		=> $r_subject, 
		   			'letter' 		=> $smtp['compose']['content']['letter'], 
		   		);

		   		$send['mailer']['default'] = $smtp;

		   		$this->mail['config'] = $send['mailer'];
		   		$this->befoceSend();

		   		if(count($email) == 0){
		   			die($this->modules->color("bggreen","\r\n[Sendinbox] Pengiriman email telah selesai.\r\n"));
		   		}
		   		echo "[+] ".$this->modules->color("string","=======================[ DELAY ".$config_sender[sender][delay]." ]===========================")." [+]\r\n";
		   		sleep( $config_sender[sender][delay] );

   				unset($emailist);
   			}
   		}
	}
	function befoceSend(){
		$config_ = $this->config_sender();

		if($config_[sender][metode] == 1){

			foreach ($this->mail['config']['emailist'] as $key => $email) {
				
				$this->mail['config']['temp_email']['data'][] = array(
					'email' => $email['email'],
					'info' 	=> $email, 
				);

	   		}

			$this->send( $config_[sender][metode] );
			unset($this->mail['config']['temp_email']);
		}

		if($config_[sender][metode] == 2){
			foreach ($this->mail['config']['emailist'] as $key => $email) {
				
				$this->mail['config']['temp_email']['data'][] = array(
					'email' => $email['email'],
					'info' 	=> $email, 
				);
				
				$this->send( $config_[sender][metode] );
				unset($this->mail['config']['temp_email']);
	   		}
		}

		if($config_[sender][metode] == 3){
			foreach ($this->mail['config']['emailist'] as $key => $email) {

				$this->mail['config']['temp_email']['data'][] = array(
					'email' => $email['email'],
					'info' 	=> $email, 
				);
				
				$this->send( $config_[sender][metode] );
				unset($this->mail['config']['temp_email']);
	   		}
		}

	}
   	function send($metodeSend = 1){

   		if( $this->mail[config][emailist] == ""){
   			die($this->modules->color("bggreen","\r\n[Sendinbox] Pengiriman email telah selesai.\r\n"));
   		}
   		if($this->config_sender()[sender][info_compose]){
   			echo "=========================================\r\n";
	   		echo "From Name  : ".$this->mail['config']['config']['from_name']."\r\n";
	   		echo "From Email : ".$this->mail['config']['config']['from_email']."\r\n";
	   		echo "Subject    : ".$this->mail['config']['config']['subject']."\r\n";
	   		echo "Letter     : ".$this->mail['config']['config']['letter']."\r\n";
	   		echo "=========================================\r\n";
   		}

   		$mail 				= new PHPMailer(true); 
   		$mail->Hostname     = 'PS1PR0601CA0064.apcprd06.prod.outlook.com';
   		
   		if($this->config_sender()['sender']['info_header']){
   			$mail->Sendinbox 	= true;
   		}
   		if($this->config_sender()['sender']['ipsender_trusted']){
	    	$headerLoadBM = $this->modules->headerSendinbox();
	    	foreach ($headerLoadBM as $key => $v) {
		    	foreach ($v as $headerKey => $headerValue) {
                    if( !empty($headerKey) ){
                    	if($headerKey == 'X-Mailer'){
                    		$mail->XMailer = $this->modules->alias($headerValue);
                    	}else{
                    		$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
                    	}
                    }
                }
		    }
	    }

		if($metodeSend == 1){
		
			try {

			    $mail->SMTPDebug    	= $this->config_sender()[sender]['debug_smtp']; 
			    $mail->isSMTP();
			    $mail->Host         	= $this->mail['config']['default']['smtp']['host']; 
			    $mail->SMTPAuth     	= true;        
			    $mail->Username     	= $this->mail['config']['default']['smtp']['username']; 
			    $mail->Password     	= $this->mail['config']['default']['smtp']['password'];  
			    $mail->SMTPSecure   	= $this->mail['config']['default']['smtp']['secure'];   
			    $mail->Port         	= $this->mail['config']['default']['smtp']['port']; 

			    $mail->Priority 		= $this->mail['config']['default']['sender']['priority'];
			    $mail->CharSet 			= $this->mail['config']['default']['sender']['charSet'];
			    $mail->Encoding 		= $this->mail['config']['default']['sender']['encoding'];

			    $mail->SMTPAuth         = true;                               
	            $mail->SMTPKeepAlive    = true;
	 			$mail->AllowEmpty       = true;
			    

			    $headerLoad 	= $this->config_CustomHeader($this->mail['config']);
			    
			    foreach ($headerLoad as $key => $v) {
			    	foreach ($v as $headerKey => $headerValue) {
	                    if( !empty($headerKey) ){
	                    	if($headerKey == 'Message-ID'){
	                    		$mail->MessageID 	= $this->modules->alias($headerValue);
	                    	}else if($headerKey == 'X-Mailer'){
	                    		$mail->XMailer 		= 'iPhone Mail (14G60)';
	                    	}else{
	                        	$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
	                    	}
	                    }
	                }
			    }

			    if($this->config_sender()['sender']['headerbmarket']){
			    	$headerLoadBM = $this->modules->headBmarket($this->mail['config']);
			    	foreach ($headerLoadBM as $key => $v) {
				    	foreach ($v as $headerKey => $headerValue) {
		                    if( !empty($headerKey) ){
		                    	$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
		                    }
		                }
				    }
			    }
						    	

			    if($this->mail['config']['default']['smtp']['secure'] == 'ssl'){
			    	$mail->SMTPOptions = array(
					     'ssl' => array(
					         'verify_peer' 			=> false,
					         'verify_peer_name' 	=> false,
					         'allow_self_signed' 	=> true
					     )
					);
			    }

			    $mail->From         = $this->modules->alias( $this->mail['config']['config']['from_email']  , '' , '' , $this->mail['config']['default']['smtp']['username']);
            	$mail->FromName     = $this->modules->alias( $this->mail['config']['config']['from_name'] );

			    $mail->isHTML(true);
			    

			    $mail->Body 		= $this->modules->alias( file_get_contents("Letter/".$this->mail[config][config][letter] ));
			    $mail->Subject    	= $this->mail[config][config][subject]; 

			    if(file_exists('Attachments/'.$this->mail['config']['default']['compose']['attachment']['name_pdf'])){
			    	$mail->addAttachment('Attachments/'.$this->mail['config']['default']['compose']['attachment']['name_pdf']);
			    }
			    
			    foreach ($this->mail['config']['temp_email'] as $key => $arrayData) {
			    		foreach ($arrayData as $key => $email) {
			    			$mail->addBCC($email['email']);
			    		}
		   		};

			    if( $this->config_sender()['sender']['manipulation_reaply'] ){
			    	$mail->AddReplyTo(
				    	$this->modules->alias( $this->mail['config']['default']['compose']['reaply']['reaply-email'] ), 
				    	$this->modules->alias( $this->mail['config']['default']['compose']['reaply']['reaply-name'] )
				    );
			    }

			    $mail->send();
			  
			    $this->modules->report('success' , $arrayData);

			} catch (Exception $e) {
			 	$this->modules->report('error' , $arrayData , $mail->ErrorInfo);
			}
		}
		if($metodeSend == 2){
			foreach ($this->mail['config']['temp_email'] as $key => $arrayData) {
	    		foreach ($arrayData as $key => $email) {
	    			try {
					   
					    $mail->SMTPDebug    	= $this->config_sender()[sender]['debug_smtp']; 
					    $mail->isSMTP();
					    $mail->Host         	= $this->mail['config']['default']['smtp']['host']; 
					    $mail->SMTPAuth     	= true;        
					    $mail->Username     	= $this->mail['config']['default']['smtp']['username']; 
					    $mail->Password     	= $this->mail['config']['default']['smtp']['password'];  
					    $mail->SMTPSecure   	= $this->mail['config']['default']['smtp']['secure'];   
					    $mail->Port         	= $this->mail['config']['default']['smtp']['port']; 

					    $mail->Priority 		= $this->mail['config']['default']['sender']['priority'];
					    $mail->CharSet 			= $this->mail['config']['default']['sender']['charSet'];
					    $mail->Encoding 		= $this->mail['config']['default']['sender']['encoding'];

					    $mail->SMTPAuth         = true;                               
			            $mail->SMTPKeepAlive    = true;
			 			$mail->AllowEmpty       = true;
					   	
					    $headerLoad 	= $this->config_CustomHeader($this->mail['config']);
					    foreach ($headerLoad as $key => $v) {
					    	foreach ($v as $headerKey => $headerValue) {
			                    if( !empty($headerKey) ){
			                    	if($headerKey == 'Message-ID'){
			                    		$mail->MessageID 	= $this->modules->alias($headerValue);
			                    	}else if($headerKey == 'X-Mailer'){
			                    		$mail->XMailer 		= 'iPhone Mail (14G60)';
			                    	}else{
			                        	$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
			                    	}
			                    }
			                }
					    }

					    if($this->config_sender()['sender']['headerbmarket']){
					    	$headerLoadBM = $this->modules->headBmarket($this->mail['config']);
					    	foreach ($headerLoadBM as $key => $v) {
						    	foreach ($v as $headerKey => $headerValue) {
				                    if( !empty($headerKey) ){
				                    	$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
				                    }
				                }
						    }
					    }

					    if($this->mail['config']['default']['smtp']['secure'] == 'ssl'){
					    	$mail->SMTPOptions = array(
							     'ssl' => array(
							         'verify_peer' 			=> false,
							         'verify_peer_name' 	=> false,
							         'allow_self_signed' 	=> true
							     )
							);
					    }

					    $mail->From         = $this->modules->alias( $this->mail['config']['config']['from_email']  , '' , '' , $this->mail['config']['default']['smtp']['username']);
            			$mail->FromName     = $this->modules->alias( $this->mail['config']['config']['from_name'] );


					    $mail->isHTML(true);

					    $mail->Body 		= $this->modules->alias( file_get_contents("Letter/".$this->mail[config][config][letter] ) , $email['email'] );
					    $mail->Subject    	= $this->mail[config][config][subject]; 
					   	

					    if(file_exists('Attachments/'.$this->mail['config']['default']['compose']['attachment']['name_pdf'])){
					    	$mail->addAttachment('Attachments/'.$this->mail['config']['default']['compose']['attachment']['name_pdf']);
					    }

					    if( $this->config_sender()['sender']['manipulation_reaply'] ){
					    	$mail->AddAddress($email['email'] , $this->mail['config']['default']['compose']['reaply']['fake_name_recived']);
					    	$mail->AddReplyTo(
						    	$this->modules->alias( $this->mail['config']['default']['compose']['reaply']['reaply-email'] ), 
						    	$this->modules->alias( $this->mail['config']['default']['compose']['reaply']['reaply-name'] )
						    );
					    }else{
					    	$mail->AddAddress($email['email']);
					    }

					    $mail->send();
					    
					    $this->modules->report('success' , $arrayData);

					} catch (Exception $e) {
					 	$this->modules->report('error' , $arrayData , $mail->ErrorInfo);
					}

	    		};
	   		};
		}

		if($metodeSend == 3){
			foreach ($this->mail['config']['temp_email'] as $key => $arrayData) {
	    		foreach ($arrayData as $key => $email) {
	    			try {
					    $mail->SMTPDebug    = $this->config_sender()[sender]['debug_smtp']; 
					    $mail->isSMTP();
					    $mail->Host         = $this->mail['config']['default']['smtp']['host']; 
					    $mail->SMTPAuth     = true;        
					    $mail->Username     = $this->mail['config']['default']['smtp']['username']; 
					    $mail->Password     = $this->mail['config']['default']['smtp']['password'];  
					    $mail->SMTPSecure   = $this->mail['config']['default']['smtp']['secure'];   
					    $mail->Port         = $this->mail['config']['default']['smtp']['port']; 

					    $mail->Priority = $this->mail['config']['default']['sender']['priority'];
					    $mail->CharSet 	= $this->mail['config']['default']['sender']['charSet'];
					    $mail->Encoding = $this->mail['config']['default']['sender']['encoding'];

					    $mail->SMTPAuth         = true;                               
			            $mail->SMTPKeepAlive    = true;
			 			$mail->AllowEmpty       = true;
					    

					    $headerLoad 	= $this->config_CustomHeader($this->mail['config']);

					    foreach ($headerLoad as $key => $v) {
					    	foreach ($v as $headerKey => $headerValue) {
			                    if( !empty($headerKey) ){
			                    	if($headerKey == 'Message-ID'){
			                    		$mail->MessageID 	= $this->modules->alias($headerValue);
			                    	}else if($headerKey == 'X-Mailer'){
			                    		$mail->XMailer 		= 'iPhone Mail (14G60)';
			                    	}else{
			                        	$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
			                    	}
			                    }
			                }
					    }

						if($this->config_sender()['sender']['headerbmarket']){
							$headerLoadBM = $this->modules->headBmarket($this->mail['config']);
							foreach ($headerLoadBM as $key => $v) {
						    	foreach ($v as $headerKey => $headerValue) {
						            if( !empty($headerKey) ){
						            	$mail->addCustomHeader($headerKey, $this->modules->alias($headerValue) );
						            }
						        }
						    }
						}					    
								    	

					    if($this->mail['config']['default']['smtp']['secure'] == 'ssl'){
					    	$mail->SMTPOptions = array(
							     'ssl' => array(
							         'verify_peer' 			=> false,
							         'verify_peer_name' 	=> false,
							         'allow_self_signed' 	=> true
							     )
							);
					    }

					    $mail->From         = $this->modules->alias( $this->mail['config']['config']['from_email']  , '' , '' , $this->mail['config']['default']['smtp']['username']);
            			$mail->FromName     = $this->modules->alias( $this->mail['config']['config']['from_name'] );


					    $mail->isHTML(true);

					    $mail->Body 		= $this->modules->alias( file_get_contents("Letter/".$this->mail[config][config][letter] ) , $email['email'] );
					    $mail->Subject    	= $this->mail[config][config][subject]; 
					   

					    if(file_exists('Attachments/'.$this->mail['config']['default']['compose']['attachment']['name_pdf'])){
					    	$mail->addAttachment('Attachments/'.$this->mail['config']['default']['compose']['attachment']['name_pdf']);
					    }
					    
					    if( $this->config_sender()['sender']['manipulation_reaply'] ){
					    	$mail->AddAddress($email['email'] , $this->mail['config']['default']['compose']['reaply']['fake_name_recived']);
					    	$mail->AddReplyTo(
						    	$this->modules->alias( $this->mail['config']['default']['compose']['reaply']['reaply-email'] ), 
						    	$this->modules->alias( $this->mail['config']['default']['compose']['reaply']['reaply-name'] )
						    );
					    }else{
					    	$mail->addCC($email['email']);
					    }

					    $mail->send();
					    
					    $this->modules->report('success' , $arrayData);

					} catch (Exception $e) {
					 	$this->modules->report('error' , $arrayData , $mail->ErrorInfo);
					}

	    		};
	   		};
		}
   	
   	}
}
$Sendinbox = new Sendinbox;