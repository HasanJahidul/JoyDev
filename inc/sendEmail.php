﻿<?php
include ('./thanksmail.php'); // include the email sending script


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


// Replace this with your own email address
$siteOwnersEmail = 'jahidul0hasan@gmail.com';
$siteOwnersName = 'Jahidul Hasan';
$siteOwnersPassword = 'BolaJabeNa';
$errorMessage =false;





if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // PHP function to Validate Blcoked User
	function Search($name, $block)
	{
		//return (array_search($name, $array));
		if(array_search($name, $block)!==false){
			return true;
			//echo "true";
		}else{
			return false;
			//echo "false";
		}
	}
	$block = array(
		"HenryVag",
		"aakash",
		"saran",
		"mohan",
		"saran"
	);
	//print_r(Search($name, $block));



   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
		$errorMessage =true;
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
		$errorMessage =true;
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
		$errorMessage =true;
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
   $message = "Email from: " . $name . "<br />";
	$message .= "Email address: " . $email . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


   if ($errorMessage ==false ) {
	   if(Search($name,$block)==true){
		   echo "Sorry, you are blocked from sending messages.";
	   }else{

	   



	//ini_set("sendmail_from", $siteOwnersEmail); // for windows server
    //$mail = mail($siteOwnersEmail, $subject, $message, $headers);

	try {
		
	$mail = new PHPMailer();
		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output  
	$mail->IsSMTP();                                         //Send using SMTP
	$mail->SMTPDebug  = false;
	$mail->SMTPAuth   = true;
	$mail->CharSet = 'UTF-8';                                   //Enable SMTP authentication
	$mail->SMTPSecure = 'ssl;'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'smtp.gmail.com'; //Sets the SMTP hosts of your Email hosting, this for AWS
	$mail->Username = $siteOwnersEmail; //Sets SMTP username
	$mail->Password = $siteOwnersPassword; //Sets SMTP password
	$mail->Port       = 587;
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
		//Recipients
		$mail->SetFrom= $email;
		$mail->FromName = 'HasanJahidul.com'; //Sets the From name of the message
		$mail->addAddress($siteOwnersEmail);     //Add a recipient
		$mail->addReplyTo($email,$name);
		
		
	
	
	
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject  = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
		
	
		
		if($mail->send()==true){
           // echo 'Message has been sent';
		   $ThanksMail = new PHPMailer();
			$ThanksMail->IsSMTP(); //Sets Mailer to send message using SMTP
			$ThanksMail->SMTPDebug = false; //Debug mode. Comment this for production mode 
			$ThanksMail->SMTPAuth   = true;   
			$ThanksMail->CharSet = 'UTF-8';
			$ThanksMail->SMTPSecure = 'ssl'; //Sets connection prefix. Options are "", "ssl" or "tls"
			$ThanksMail->Host = 'smtp.gmail.com'; //Sets the SMTP hosts of your EThanksMail hosting, this for AWS
			$ThanksMail->Username = $siteOwnersEmail; //Sets SMTP username
			$ThanksMail->Password = $siteOwnersPassword; //Sets SMTP password
			$ThanksMail->Port = 465; //Sets the default SMTP server port
			$ThanksMail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			
			
			$ThanksMail->From = $siteOwnersEmail; //Sets the From eThanksMail address for the message
			$ThanksMail->FromName = 'Hasanjahidul.com'; //Sets the From name of the message
			$ThanksMail->AddAddress($email, $siteOwnersName); //Adds a "To" address
			$ThanksMail->IsHTML(true);//Sets message type to HTML
			$ThanksMail->Subject = 'Thanks for contacting to Hasanjahidul.com'; //Sets the Subject of the message
			//An HTML or plain text message body
			$ThanksMail->Body =$body ;
		
			//$ThanksMail->Send();

			if ($ThanksMail->send()==true){
				echo 'Message has been sent';
			}else{
				echo 'We have recieved your message but we are unable to send you a confirmation email';

			}
			
			
            }
            else { echo "Something went wrong. Please try again."; }
			
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
	}

	// 	if ($mail) { echo "OK"; }
    //   else { echo "Something went wrong. Please try again."; }
		
	} # end if - no validation error

}else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

    

	
}

?>