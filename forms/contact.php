<?php

  $receiving_email_address     = 'mvfproims@gmail.com';
  
  // Sender 
  $from = 'info@mvfproims.com'; 
  $fromName = 'MFVProIMS'; 
    
  
  $header = "From:info@mvfproims.com \r\n";
 // $header = "From: $fromName"." <".$from.">"; 
  $header .= "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html\r\n";
  $msg = wordwrap($_POST['message'],470);
  
  $mail = mail($receiving_email_address, $_POST['subject'], $msg, $header);  
  
  if( $mail == true ) {
	  echo "OK";
  }else {
	  echo "Message could not be sent. Please try again.";
  }
?>
