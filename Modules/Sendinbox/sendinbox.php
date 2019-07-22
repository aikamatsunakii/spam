<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2018-06-27 00:47:34
 * @Last Modified by:   Eka Syahwan
 * @Last Modified time: 2018-08-12 23:06:16
 */
class Modules
{
	public function cover(){
		$template[0] .= $this->color("random" , "==================================================\r\n");
        $template[0] .= $this->color("random" , "      _______    || ".$this->color('string' , $this->versi()['name'])." ".$this->versi()['versi']." (issue ".$this->versi()['issue'].")\r\n");
        $template[0] .= $this->color("random" , "     |==   []|   || (c) ".date(Y)." ".$this->color("random","emailist").".org\r\n");
        $template[0] .= $this->color("random" , "     |  ==== |   || www.bmarket.or.id\r\n");
        $template[0] .= $this->color("random" , "     '-------'   || it's full of great features!\r\n");
        $template[0] .= $this->color("random" , "==================================================\r\n");

        print_r($template[0]);
        echo "\r\n";
	}
	public function versi(){
		return array(
			'name' 		=> 'Sendinbox',
			'versi' 	=> 'ProV2', 
			'issue' 	=> '2.2',
			'codename'  => false,
		);
	}
	public function stuck($msg){
        echo $this->color("green","[Sendinbox] ").$this->color("purple",$msg);
        $answer =  rtrim( fgets( STDIN ));
        return $answer;
    }
    public function color($color = "random" , $text){
    	$new 	= new Config;
    	$conf 	= $new->config_sender();
    	if($conf['sender']['color'] == true){
	    	$arrayColor = array(
				'grey' 		=> '1;30',
				'red' 		=> '1;31',
				'green' 	=> '1;32',
				'yellow' 	=> '1;33',
				'blue' 		=> '1;34',
				'purple' 	=> '1;35',
				'nevy' 		=> '1;36',
				'white' 	=> '1;1',
				'bgred' 	=> '1;41',
				'bggreen' 	=> '1;42',
				'bgyellow' 	=> '1;43',
				'bgblue' 	=> '1;44',
				'bgpurple' 	=> '1;45',
				'bgnavy' 	=> '1;46',
				'bgwhite' 	=> '1;47',
			);	
			if($color == 'random'){
				$arrayColor = array(
					'red' 		=> '1;31',
					'green' 	=> '1;32',
					'yellow' 	=> '1;33',
					'nevy' 		=> '1;36',
					'white' 	=> '1;1',
				);	
				$arrayColor[$color] = $arrayColor[array_rand($arrayColor)];
				$res .=  "\033[".$arrayColor[$color]."m".$text."\033[0m";

			}else if($color == 'string'){
				$arrayColor = array(
					'grey' 		=> '1;30',
					'red' 		=> '1;31',
					'green' 	=> '1;32',
					'yellow' 	=> '1;33',
					'blue' 		=> '1;34',
					'purple' 	=> '1;35',
					'nevy' 		=> '1;36',
					'white' 	=> '1;1',
				);	
				foreach (str_split($text) as $key => $value) {
					$arrayColor[$color] = $arrayColor[array_rand($arrayColor)];
					$res .= "\033[".$arrayColor[$color]."m".$value."\033[0m";
				}

			}else{
				
				$res .=  "\033[".$arrayColor[$color]."m".$text."\033[0m";
			
			}
			return $res;
    	}else{
    		return $text;
    	}
		
	}
	public function required(){
		echo $this->color("green","[Sendinbox] ".$this->color('bggreen', "Sedang mencari file emailist\r\n"));

		$locdir_list 	= SENDINBOX_PATH.'/Emailist';
		$list_load 		= scandir($locdir_list);
		foreach ($list_load as $key => $value) {
			if(is_file($locdir_list."/".$value)){
				$arrayList[] = $locdir_list."/".$value;
			}
		}
		if(count($arrayList) == 0){
			echo $this->color("green","[Sendinbox] ".$this->color('bgred', "Masukan file emailist di folder Emailist\r\n"));
			echo $this->color("green","[Sendinbox] ".$this->color('bgred', "Tidak ditemukan file emailist di folder Emailist\r\n"));
			die();
		}
		echo $this->color("green","[Sendinbox] ".$this->color('bggreen', "Terdapat ".count($arrayList)." file emailist.")."\r\n\n");
		echo $this->color("green","====================================\r\n");
		foreach ($arrayList as $key => $value) {
			echo $this->color("nevy","[Emailist] [$key] ".pathinfo($value)[filename]."\r\n");
		}
		echo $this->color("green","====================================\r\n");
		echo "\r\n";
		$pil = $this->stuck("Masukan nomor list : ");
		$fgt = file_get_contents($arrayList[$pil]);
		if(empty($fgt)){
			echo $this->color("red","[Sendinbox] Nomor pilihan anda salah!!!\r\n");
			die();
		}
		
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fgt = explode("\r\n", $fgt);
        } else {
            $fgt = explode("\n", $fgt);
        }

		echo $this->color("green","[Sendinbox] Terdapat ".$this->color('red',count($fgt))." emailist.\r\n\n");
		$pil = $this->stuck("Hapus duplikat email ? 0 = Tidak , 1 = YA : ");
		if($pil == 1){
			$fgt = array_unique($fgt);
		}
		return $fgt;
	}
	public function saves($data , $name){
		$fopn = fopen($name, "a+");
		fwrite($fopn, $data);
		fclose($fopn);
	}
	public function rebuildCenter($text = "" , $status_data = ""){
		$s  = 25;
		$ar = str_split($text);
		for ($i=0; $i <$s; $i++) { 
			if($ar[$i] == NULL){
				$data .= ' ';
			}else{
				$data .= $ar[$i];
			}
		}
		return $data." ".$status_data."\r\n";
   	}
	public function report($type , $arrayList , $error = ""){

		if($type == 'success'){
			foreach ($arrayList as $key => $email) {
	   			echo $this->color('white' , "[Sendinbox]").$this->color('bgnavy' , "[".$email['info']['line']."/".$email['info']['totalmail']."]")." ".$this->color('bggreen' , trim($this->rebuildCenter($email['email'] , "=> Delivered !")))."\r\n";

	   			$this->saves($email['email']."\r\n" , "Result/email-success.txt");

	   		}
		}
		if($type == 'error'){
			if($error){
				$this->saves(date('d/m/Y h:i:s')."  ==> ".$error."\r\n--------------------------------------\r\n" , "Result/smtp-log.txt");
			}
			foreach ($arrayList as $key => $email) {
	   			echo $this->color('white' , "[Sendinbox]").$this->color('bgnavy' , "[".$email['info']['line']."/".$email['info']['totalmail']."]")." ".$this->color('bgred' , trim($this->rebuildCenter($email['email'],"=> ".$error)))."\r\n";
	   			$this->saves($email['email']."\r\n" , "Result/email-failed.txt");

	   		}
		}
	}
	public function arrayrandom($array){
        $random = array_rand($array);
        return array(
            'value' => $array[$random], 
            'key'   => $random
        );
    }
    public function shuffle_assoc($list) { 
      if (!is_array($list)) return $list; 
      $keys = array_keys($list); 
      shuffle($keys); 
      $random = array(); 
      foreach ($keys as $key) {
        $random[$key] = $list[$key];
      }
      $key = (count($list)-1);
      return $random[rand(0,$key)]; 
    } 
    public function sensor_email($emails = ""){
		$email  = explode("@", $emails);
		$sp 	= str_split($email[0]);
		$hitsp  = ceil((count($sp)/2)); 
		for ($i=0; $i <count($sp); $i++) { 
			if($i > $hitsp){
				$x .= "*";
			}else{
				$x .= $sp[$i];
			}
		}
		return $x."@".$email[1];
    }
    public function alias($data  , $email = "" , $encryp = false , $smtp_domain = ""){
    	$nwConf = new Config;
    	$prefix = explode("@", $smtp_domain);
    	if($smtp_domain){
    		$data   = str_replace('{domainsmtp}', $prefix[1] , $data);
    	}

    	$data   = str_replace("{sensor_email}", $this->sensor_email($email) , $data);
    	if(preg_match("/{shortlink}/", $data)){
    		$linkconvert = $this->shortlink($nwConf->config_sender()['sender']['shortlink']['link']."?sendinbox=".$this->random_text('textrandom') , $nwConf->config_sender()['sender']['shortlink']['shortlink']);
    		if($linkconvert != false){
    			$data   = str_replace("{shortlink}", $linkconvert , $data);
			}else{
				$data   = str_replace("{shortlink}", $nwConf->config_sender()['shortlink']['link']."?sendinbox=".$this->random_text('textrandom') , $data);
			}
    	}

        $data   = str_replace("{email}", $email , $data);
        $data   = str_replace("{date}", date("F j, Y, g:i a") , $data);
        $data   = str_replace("{ip}", rand(10,999).".".rand(10,999).".".rand(10,999).".".rand(10,999) , $data);
        $data   = str_replace("{negara}", strtoupper($this->negara()[value]) , $data);
        $data   = str_replace("{device}", strtoupper($this->device()[value]) , $data);
        $data   = str_replace("{browser}", $this->browser()[value] , $data);
        $data   = str_replace("{link}", $this->arrayrandom(  $nwConf->config_sender()['sender']['random_link'] )[value] , $data);
        $data   = $this->check_random($data , 'low'); // up = untuk random text huruf besar , low = huruf kecil
        if( $encryp == true){
            foreach ($config['encrypt_kata'] as $key => $katayangdienc) {
                $data   = str_replace($katayangdienc, $this->enc_letter($katayangdienc), $data);
            }
        }
        return $data; 
    }
	public function check_random($data , $options){ 
	        preg_match_all('/{(.*?)}/', $data, $matches);
	        foreach ($matches[1] as $key => $value) {
	            $explode    = explode(",", $value);
	            $jenis      = $explode[0];
	            $panjang    = $explode[1];
	            if($explode[3]){
	           	 	$options 	= $explode[3];
	            }
	            $random     = $this->random_text($jenis , $panjang , $options);
	            $data       = str_replace($value, $random, $data);
	        }
	        return str_replace("{", "", str_replace("}", "", $data));
	    }
	public function random_text($jenis , $length = 10 , $lowup = 'up'){
		 switch ($jenis) {
            case 'textrandom':
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
            case 'numrandom':
                $characters = '0123456789';
            break;
            case 'textnumrandom':
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
            
            default:
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        switch ( strtolower($lowup) ) { 
            case 'low':
                $randomString = strtolower( $randomString );
            break;
            case 'up':
                $randomString = strtoupper( $randomString );
            break;
            
            default:
                $randomString = strtolower( $randomString );
            break;
        }
        return $randomString;
	}
	public function enc_letter($kata){
        foreach (str_split($kata) as $key => $value) {
          $fText .= $value."<font style='color:transparent;font-size:0px'>".rand(100,9999)."<!--".rand(100,9999)."--></font>"."<!-- ".md5($text.md5(rand(10,999999)))."-->";
        }
        return $fText;
    }
    public function browser(){
        $browser = array('Mozilla Firefox' , 'Chrome' , 'Safari');
        return $this->arrayrandom($browser);
    }
    public function device(){
        $device = array(
        	'iPhone 6S Plus','iPhone 6S','iPhone SE','iPad Pro 9.7','iPhone 7 Plus',
        	'iPhone 7','IPad Pro','IPhone 8','IPhone 8+','IPhone 7+','Iphone X'
        );
        return $this->arrayrandom($device);
    }
    public function enc_subject($u = ""){
		$u = str_split($u);
    	$unicode = array(
			'a' => '&#1072;', 
			'c' => '&#1010;',
			'e' => '&#1077;',
			'o' => '&#1086;',
			'p' => '&#1088;',
			's' => '&#1109;',
			'd' => '&#1281;',
			'q' => '&#1307;',
			'w' => '&#1309;',	
		);

		foreach ($u as $key => $value) {
			if($unicode[$value]){
				$text .= preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $unicode[$value] );
			}else{
				$text .= $value;
			}
		}
		return $text;
    }
    public function negara(){
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        return $this->arrayrandom($countries);
    }
    public function headBmarket($MailData = ""){
		if(preg_match("/hotmail|live|outlook|msn/", $MailData[temp_email][data][0][email] )){
            $customheader[] = array(
				'Message-ID' 						=> '<{textnumrandom,12,1}.{textnumrandom,13,1}@{textnumrandom,15,1}>', 
				'List-Unsubscribe' 					=> 'mailto:bounce-{textnumrandom,16,1}@apcprd06.prod.outlook.com?subject=list-unsubscrib',
			);
	    };
	    if(preg_match("/yahoo|ymail|rocketmail/", $MailData[temp_email][data][0][email] )){
            $customheader[] = array(
				'Message-ID' 						=> '<{textnumrandom,12,1}.{textnumrandom,13,1}@{textnumrandom,15,1}>', 
				'List-Unsubscribe' 					=> 'mailto:bounce-{textnumrandom,16,1}@apcprd06.prod.outlook.com?subject=list-unsubscrib',
			);
	    };
	    if(preg_match("/me.com|icloud.com|mac.com/", $MailData[temp_email][data][0][email] )){
            $customheader[] = array(
				'Message-ID' 						=> '<{textnumrandom,12,1}.{textnumrandom,13,1}@{textnumrandom,15,1}>', 
				'List-Unsubscribe' 					=> 'mailto:bounce-{textnumrandom,16,1}@apcprd06.prod.outlook.com?subject=list-unsubscrib',
			);
	    };
	    if(!preg_match("/hotmail|live|outlook|yahoo|ymail|me.com|icloud.com|mac.co|rocketmail|msn|me.com|icloud.com|mac.com/", $MailData[temp_email][data][0][email] )){
            $customheader[] = array(
            	'Message-ID' 						=> '<{textnumrandom,12,1}.{textnumrandom,13,1}@{textnumrandom,15,1}>', 
				'List-Unsubscribe' 					=> 'mailto:bounce-{textnumrandom,16,1}@apcprd06.prod.outlook.com?subject=list-unsubscrib',
			);
	    };
	    return $customheader;
    }
    public function headerSendinbox(){
    	$array = array(
			'66.231.','204.79.','65.55.','10.152.','209.85.','40.97.'
		);
		$result = array_rand($array);
		$customheader[] = array(
	    	'X-Mailer' 							=> 'Sendinbox Prov2 (https://github.com/radenvodka/Sendinbox-)',
			'X-Sender-IP' 						=>  $array[$result].rand(10,244).'.'.rand(1,255),
		);
		return $customheader;
    }
    public function shortlink($urls = "" , $service = ""){
    	$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://www.expandurl.net/shorten",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "url=".$urls."&service=".$service,
		  CURLOPT_HTTPHEADER => array(
		    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
		    "cache-control: no-cache",
		    "connection: keep-alive",
		    "content-type: application/x-www-form-urlencoded",
		    "host: www.expandurl.net",
		    "origin: https://www.expandurl.net",
		    "referer: https://www.expandurl.net/shorten",
		    "upgrade-insecure-requests: 1",
		    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		  	return false;
		} else {
		  	preg_match_all('/onclick=\'this\.select\(\)\' type=\'text\' value=\'(.*?)\'>/m', $response, $matches);
			return $matches[1][0];
		}
    }
}

if($argv[1] == '--cover'){
	$Modules = new Modules;
	$Modules->cover();
	die();
}