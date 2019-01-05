<?php

namespace SMTP;

require_once 'BulkEmail.php';
require_once 'GenLib.php';

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

class Bulk {
  protected static $mail;

  public static function init() {
    Bulk::$mail = new PHPMailer();
  
    //// Server settings
    Bulk::$mail->SMTPDebug = 2;				// Enable verbose debug output
    Bulk::$mail->isSMTP();					// Set mailer to use SMTP
    Bulk::$mail->SMTPAuth = true;				// Enable SMTP authentication
    // Specify main and backup SMTP servers, separated by ;
    Bulk::$mail->Host = \BulkEmail\CONF['SMTP']['SRV'];
    Bulk::$mail->Username = \BulkEmail\CONF['SMTP']['USR'];		// SMTP username
    Bulk::$mail->Password = \BulkEmail\CONF['SMTP']['PWD'];		// SMTP password
    Bulk::$mail->SMTPSecure = \BulkEmail\CONF['SMTP']['ENC'];
    Bulk::$mail->Port = \BulkEmail\CONF['SMTP']['PORT'];			// TCP port to connect to
  
    //// Recipients
    Bulk::$mail->setFrom(\BulkEmail\CONF['SMTP']['FROM'], \BulkEmail\CONF['SMTP']['FROMNAME']);
  
    //// Content
    Bulk::$mail->isHTML(true);
  }

  public static function send($log_prefix, $subject, $html_body, $to, $name = '',
    $keep_alive = false) {
    //// Recipients
    Bulk::$mail->ClearAllRecipients();		// Reset the `To:` list to empty
    Bulk::$mail->addAddress($to, $name);	// Add recipient
  
    //// Content
    Bulk::$mail->Subject = $subject;
    Bulk::$mail->Body = $html_body;

    // SMTP connection will not close after each email sent
    Bulk::$mail->SMTPKeepAlive = $keep_alive;

    if (Bulk::$mail->send() === FALSE) {
      \GenLib\log_line("__ERROR: $log_prefix: $name <$to> - " . ($error = Bulk::$mail->ErrorInfo));
      return $error;
    } else {
      \GenLib\log_line("$log_prefix: $name <$to>");
      return FALSE;
    }
  }
}
?>
