<?php
	include_once('class.phpmailer.php');
		
	function sendMail($to,$subject,$message,$fromAddress='',$fromUserName='',$toName='',$bcc='',$upload_dir='', $filename='',$mailConfig)
	{	
		$mail = new PHPMailer();
		$mail->IsSMTP(); // send via SMTP
		$mail->IsHTML(true); // [optional] send as HTML
		$mail->ClearAddresses();
		$find = strpos($to,',');		
		
                $mail->SMTPAuth =  true;
		$mail->Host     =  $mailConfig['Host'];
		$mail->Username =  $mailConfig['Username']; //"support@extramarks.com";
		$mail->Password =  $mailConfig['Password']; //"Alok*1234";
                $mail->Port     =  $mailConfig['Port'];


		if($find)
		{
			$ids = explode(',',$to);
			for($i=0;$i<count($ids);$i++)
			{
				$mail->AddAddress($ids[$i]);
			}
		}
		else
		{
			$mail->AddAddress($to);
		}	
		
		if($fromAddress!=''){
			$mail->From     = $fromAddress;
		} else {
			$mail->From     = "info@extramarks.com";
		}
		if($fromUserName!=''){
			$mail->FromName = $fromUserName;
		} else {
			$mail->FromName = "Extramarks";	
		}
		
		$mail->WordWrap = 50; 
		$mail->IsHTML(true);
		
		$mail->Subject = $subject;			
		$mail->Body = $message;
		if($upload_dir!='')
		{
			foreach($upload_dir as $uploaddirs)
			{
                            
                            $mail->AddAttachment($uploaddirs.'/'.$filename, $filename); 
			}
		}
		if($mail->Send())
		{
			return 1;	
		}
		else
		{
			return 0;	
		}
		
	}
?>
