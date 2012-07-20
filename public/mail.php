                                                            
<?php

// Is the OS Windows or Mac or Linux 
if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
  $eol="\r\n";
} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
  $eol="\r";
} else {
  $eol="\n";
}

/* All form fields are automatically passed to the PHP script through the array $HTTP_POST_VARS. */
$email = "gerry@pattersondesignassociates.co.uk";
$mail = $_POST['mail'];
$subject = "e-mail from website";
$imie = $_POST['imie'];
$tel = $_POST['tel'];
$wiad = $_POST['wiad'];
$message = stripslashes("Message from: ".$mail.$eol."Name: ".$imie.$eol."Phone number: ".$tel.$eol.$eol."Message:".$eol.$eol.$wiad);
$message = wordwrap($message, 70);

// Additional headers
$headers = 'MIME-Version: 1.0'.$eol;
$headers .= 'Content-type:text;charset=utf-8'.$eol;

$headers .= 'From: form from website <'.$mail.'>'.$eol;
$headers .= 'Reply-To: <'.$mail.'>'.$eol; 
															
/* PHP form validation: the script checks that the Email field contains a valid email address and the Subject field isn't empty. preg_match performs a regular expression match. It's a very powerful PHP function to validate form fields and other strings - see PHP manual for details. */

if (!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $mail)) {
	echo "Wrong e-mail address. Try again.";
} 
														
/* Sends the mail and outputs the "Thank you" string if the mail is successfully sent, or the error string otherwise. */

elseif (mail($email,$subject,$message, $headers)) {
	echo "Thank You for your message.";
} else {
	echo "Failed to send the message.";
}
															
?>

