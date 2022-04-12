<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require getcwd().'/PHPMailer/src/Exception.php';
require getcwd().'/PHPMailer/src/PHPMailer.php';
require getcwd().'/PHPMailer/src/SMTP.php';

define ('GUSER','matias.etcheverry9@gmail.com');
define ('GPWD','yjylgssykktdgtse');

$mail = new PHPMailer(TRUE);


if(!isset($_POST['submit']))
{
	//This page should not be accessed directly. Need to submit the form.
	echo "error; you need to submit the form!";
}
$name = $_POST['name'];
$visitor_email = $_POST['email'];
$message = $_POST['message'];

//Validate first
if(empty($name)||empty($visitor_email)) 
{
    echo "Name and email are mandatory!";
    exit;
}

if(IsInjected($visitor_email))
{
    echo "Bad email value!";
    exit;
}

$email_from = 'matias.etcheverry9@gmail.com';//<== update the email address
$email_subject = "New Form submission";
$email_body = "You have received a new message from the user $name, $visitor_email\n".
    "Here is the message:\n $message".
// create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true;  // authentication enabled
$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
$mail->SMTPAutoTLS = false;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;

$mail->Username = GUSER;  
$mail->Password = GPWD;           
$mail->SetFrom($email_from, $email_from);
$mail->Subject = $email_subject;
$mail->Body = $email_body;
$mail->AddAddress(GUSER);
$mail->Send();
header('Location: index.html');
// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
   
?> 