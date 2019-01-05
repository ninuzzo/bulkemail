<?php

require_once 'BulkEmail.php';
require_once 'SMTP.php';
require_once 'GenLib.php';
require_once 'DB.php';

use DB\MySQL;
use const BulkEmail\CONF;

GenLib\log_date_mark();

SMTP\Bulk::init();

MySQL::connect();

$result = MySQL::query('SELECT * FROM target ORDER BY id LIMIT ' .
  CONF['LIM']);

for ($sent_msg = 0; $row = mysqli_fetch_assoc($result); $sent_msg++) {
  $body = $row['lang'] == 'en' ? CONF['SMTP']['BODY_EN']
    : CONF['SMTP']['BODY_OT'];

  $last_of_burst = (($sent_msg + 1) % CONF['SMTP']['BURST']) == 0;

  if ($error = SMTP\Bulk::send($row['id'],
    $row['lang'] == 'en' ? CONF['SMTP']['SUBJECT_EN'] : CONF['SMTP']['SUBJECT_OT'],
    $body, $row['email'], "$row[name] $row[surname]", !$last_of_burst)) {

    echo "-------------------------------------------------------\n";
    echo $error;
    echo "-------------------------------------------------------\n";

    if (stripos($error, 'SMTP code: 421') !== FALSE) {
      die("Connection closed. Sent $sent_msg messages. Wait and retry.");
    } else if (stripos($error, 'quota exceeded') !== FALSE) {
      die("Quota exceeded. Sent $sent_msg messages. Try next day.");
    }

    die();
  } else {
    MySQL::move_row('target', 'target_sent', 'id', $row['id']);
  }

  if ($last_of_burst) {
    sleep(CONF['SMTP']['PAUSE']);
  }
}

?>
