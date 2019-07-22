<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2018-06-26 23:43:28
 * @Last Modified by:   Eka Syahwan
 * @Last Modified time: 2018-08-13 00:48:45
 */
##########################################################################
##################     BACA SEBELUM BERTANYA         #####################
##########################################################################
# FUNGSI YANG BISA DI RANDOM SILAHKAN
# COPY DARI ----(COPY)--- SAMPAI ---(CUT)--- LALU TARUH DI BAWAHNYA
#
#
##########################################################################

class Config
{
	public function config_sender(){
		$config['sender']['info_compose'] 			= false; 	// true/false for enable/disable (show info fromname , email , subject , letter)
		$config['sender']['info_header'] 			= false; 	// menampilkan informasi header.
		$config['sender']['debug_smtp'] 			= false; 	// aktifkan debug smtp (Respons pengiriman email) : true / false
		$config['sender']['color'] 					= true; 	// aktifkan warna : true / false
		$config['sender']['info_update'] 			= false; 	// untuk mengecek update sendinbox : true / false

		$config['sender']['rotate'] 				= 1; 		// setelah mengirim 10 email maka smtp akan (after send 10 email , smtp a change to b)
		$config['sender']['number'] 				= 1; 		// jumlah pengiriman email (total email per sec)
		$config['sender']['delay'] 					= 1; 		// waktu jeda (time delay)
		$config['sender']['metode'] 				= 2; 		// 1 = Add BCC , 2 = Add Address , 3 = add CC
		$config['sender']['manipulation_subject'] 	= false; 	// true/false for enable/disable // untuk manipulasi subject
		$config['sender']['manipulation_fromname'] 	= false; 	// true/false for enable/disable // untuk manipulasi from name
		$config['sender']['manipulation_reaply'] 	= false; 	// untuk mengaktifkan fitur reaply : true / false http://prntscr.com/k6l8fd
		$config['sender']['headerbmarket'] 			= true; 	// membuat header sesuai email (silahkan false untuk mematikan)
		$config['sender']['ipsender_trusted'] 		= false; 	// membuat ip sender terpercaya.

		$config['sender']['random_link'] 			= array(''); // gunakan {link} untuk memuat link secara acak.
		$config['sender']['shortlink'] 				= array( // autogenerate shortlink. (mengunakan fitur ini dapat memperlambat pengiriman !!!)
			'link' 			=> '', 	// url scampage (add tag {shortlink} in letter) // kosongkan jika tidak ingin mengunakanya.
			'shortlink'		=> '', 	// bitly , jmp, tinyurl , google
		);
		return $config;
	}
	public function config_smtp()
	{
		$user = array(
			######## COPY DISINI UNTUK MULTY SMTP ######
			[
				'smtp_user' 	=>  'no-teropoyqdohh79166-uuwswloskt.hmmnp@tsukanbesan.com
', 		// username SMTP KALIAN
				'smtp_pass' 	=>  'Kangkung15' 		// password SMTP KALIAN
			],
			######## SELESAI DISINI UNTUK MULTY SMTP ######
		);

		foreach ($user as $key => $datauser) {

			$config['smtp']['host'] 			= 'smtp.gmail.com';
			$config['smtp']['secure'] 			= 'tls';
			$config['smtp']['username'] 		= $datauser['smtp_user']; // jangan edit user disini
			$config['smtp']['password'] 		= $datauser['smtp_pass']; // jangan edit password disini
			$config['smtp']['port'] 			= '587'; // 587 / 465 [587 = tls | 465 = ssl]
			$config['sender']['priority'] 		= '3'; //(1 = High, 2 = Medium, 3 = Low)
			$config['sender']['encoding'] 		= 'binary'; // Options: "8bit", "7bit", "binary", "base64", and "quoted-printable".

			#----------------------(COPY)-----------------------------#
			$config['compose']['content']['random'][] = array(
				'fromname' 		=> 'AppleID ',
				'fromemail' 	=> '{numrandom,12,3}{textnumrandom,18,2}@{numrandom,12,3}{textnumrandom,18,2.com',
				'subject' 		=> '=?UTF-8?B?W1N0YXRlbWVudCBPcmRlcl0gWW91ciByZWNlaXB0IGZyb20gQXBwbGUgb24=?= {date}',
				'letter' 		=> '712.html',
			);
			$config['compose']['attachment'] = array(	
				'name_pdf' => 'Invoice-PubgMobilecoint.docx',  // jika file tidak ada makaphp tidak akan terkirim attachment nya
			);
			#----------------------(CUT)-----------------------------#
						



			/** ----------------------------------
				THIS CONFIG (ini config ) : manipulation_reaply
				FOR USE PLEAS (untuk mengunakan nya) $config['sender']['manipulation_reaply'] = TRUE for active and FALSE for disable
				NOT SUPPORT METODE 1 (BCC)
				----------------------------------
			**/

			$config['compose']['reaply'] = array(
				'fake_name_recived' => 'Apple Support', // recived name
				'reaply-name'  		=> 'Itunes Support',
				'reaply-email' 		=> 'sand.{numrandom,7,3}kerenagtu.kesdkas-aksdow@jansdwk.mudiskalsasana.{textnumrandom,5,2}.{textnumrandom,4,2}.istikaolahaangurahyangada.apple.com',
			);
			$smtpconfig[] = $config;

		}
		return $smtpconfig;
	}
	public function config_CustomHeader($MailData = ""){
		################################################################
		# Remove // for active666
		# You can modif custom header in here
		#
		#################### CUSTOM HEADER DISINI #####################
		$customheader[] = array(
	    /* 'Message-ID' 						=> '<{numrandom,10,1}.{numrandom,10,2}.{numrandom,13,3}.JavaMail.email@email.apple.com>',
        	'In-Reply-To' 						=> '<{textnumrandom,40,1}@{textnumrandom,16,1}.apcprd06.prod.outlook.com>',
			'X-SID-PRA' 						=> 'MICROSOFTOFFICE365@outlook.com',
			'X-SID-Result' 						=> 'PASS',
			'X-OriginatorOrg' 					=> 'outlook.com',
			'Return-Path' 						=>  'bounce-810_HTML-{textnumrandom,9,1}-{textnumrandom,6,2}-{textnumrandom,7,3}-1@bounce.email.office.com',
			'List-Unsubscribe' 					=> '<mailto:leave-{textnumrandom,20,1}-{textnumrandom,22,2}-{textnumrandom,16,3}-{textnumrandom,18,4}-{textnumrandom,6,5}@leave.email.office.com>',
			'DomainKey-Signature' 				=> 'a=rsa-sha1; c=nofws; q=dns; s=200608; d=email.office.com;',
			'Received-SPF' 						=> 'Pass (protection.outlook.com: domain of bounce.email.office.com',
			//'References' 						=> '<{textnumrandom,40,1}@{textnumrandom,16,1}.apcprd06.prod.outlook.com><{textnumrandom,40,1}@{textnumrandom,10,1}.apcprd06.prod.outlook.com>',
			//'Errors-To' 						=> $this->mail['config']['config']['from_email'],*/
		);
		return $customheader;
	}
}