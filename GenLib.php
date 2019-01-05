<?php

namespace GenLib;

require_once 'BulkEmail.php';

use const BulkEmail\CONF;

function log_line($line) {
  file_put_contents(CONF['LOG'],"$line\n", FILE_APPEND);
}

function log_date_mark() {
  date_default_timezone_set(CONF['TZ']);
  log_line("__LOG of " . date("Y-m-d H:i:s"));
}

?>
