<?php

namespace BulkEmail;

require_once 'GenLib.php';
require_once 'DB.php';

use DB\MySQL;

const PRE_HTML = <<<'EOH'
<!DOCTYPE html>
<html>
<head>
<style>
.b {
  font-size: 1.2em;
}
</style>
</head>
<body>
EOH
  ,

  POST_HTML = <<<'EOH'

</body>
</html>
EOH
  ,

  CONF = [
    'LOG'     => 'send.log',

    // Maximum number of email sent per run. Adjust to email-provider limits.
    'LIM'     => 1000,	// Google

    #'LIM'     => 500,	// Outlook

    // Beware if you send more than 200 yahoo will delete your account
    #'LIM'     => 200,	// Yahoo

    'TZ'      => 'Europe/Rome',	// Time Zone is used just for date logging purposes

    // MySQL
    'DB'   => [
      'HOST' => 'localhost',
      'USR'  => 'bulkemail',
      'PWD'  => 'MYSQL_PWD;',
      'NAME' => 'bulkemail',
    ],

    'SMTP' => [
      //// Settings
      // For GMAIL settings see: https://support.google.com/a/answer/176600?hl=it
      // and (IMPORTANT) https://myaccount.google.com/lesssecureapps
      'SRV'    => 'smtp.gmail.com',
      'USR'    => 'YOUR_USER@gmail.com',
      'PWD'    => 'YOUR_PWD',
      'PAUSE'  => 5, // pause between connections in seconds
      // How many email to send in one connection, 1 disables keep-alive
      'BURST'  => 73,

      #'SRV'   => 'smtp-mail.outlook.com',
      #'USR'   => 'YOUR_USER@outlook.com',
      #'PWD'   => 'YOUR_PWD',
      #'PAUSE' => 0, // 5
      #'BURST' => 1, // 10

      // See: http://help.yahoo.com/kb/index?page=content&id=SLN27791&locale=en_US&y=PROD_MAIL_ML
      #'SRV'   => 'smtp.mail.yahoo.com',
      #'USR'   => 'YOUR_USER@yahoo.com',
      #'PWD'   => 'YOUR_PWD',
      #'PAUSE' => 0,
      #'BURST' => 200,

      // Enable TLS encryption, `ssl` also accepted (but obsolete)
      'ENC'  => 'tls',
      'PORT' => '587',

      //// Recipients
      'FROMNAME' => 'NAME SURNAME',	// optional
      'FROM'     => 'YOUR_USER@gmail.com',

      #'FROMNAME' => 'NAME SURNAME',	// optional
      #'FROM'     => 'YOUR_USER@outlook.com',

      #'FROMNAME' => 'NAME SURNAME',	// optional
      #'FROM'     => 'YOUR_USER@yahoo.com',

      //// Content
      'SUBJECT_EN'  => 'SUBJECT_IN_ENGLISH',
      'SUBJECT_OT'  => 'SUBJECT_IN_OTHER_LANG',
      'BODY_EN'     => PRE_HTML . <<<'EOB'
<p><em>Hi friend</em>,<br>
this is the HTML email text.</p>

<p>Another paragraph:</p>

<ul>
<li><a href="http://link1">LINK1</a></li>
<li><a href="http://link2">LINK2</a></li>
</ul>
EOB
      . POST_HTML,
      'BODY_OT'     => PRE_HTML . <<<'EOB'
<p><em>Salve</em>,<br>
questo è il testo HTML dell'email.</p>

<p>Un altro paragrafo:</p>

<ul>
<li><a href="http://link1">LINK1</a></li>
<li><a href="http://link2">LINK2</a></li>
</ul>
EOB
      . POST_HTML,
    ],
  ];

?>
