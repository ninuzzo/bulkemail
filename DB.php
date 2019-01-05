<?php

Namespace DB;

require_once 'BulkEmail.php';
require_once 'GenLib.php';

use const BulkEmail\CONF;
use function GenLib\log_line;

class MySQL {
  protected static $link;

  protected static function error($sql = '') {
    log_line('__ERROR: MySQL (' . mysqli_errno(MySQL::$link) . ') '
     . mysqli_error(MySQL::$link) . " $sql");
    die("ERROR: DB query error\n");
  }

  public static function connect() {
    if (! (MySQL::$link = @mysqli_connect('p:' . CONF['DB']['HOST'],
             CONF['DB']['USR'], CONF['DB']['PWD'], CONF['DB']['NAME']))) {
      log_line('__ERROR: MySQL connection (' . mysqli_connect_errno() . ') '
       . mysqli_connect_error());
      die("ERROR: Can't connect to database\n");
    }
  }

  public static function query($sql) {
    if (! ($result = mysqli_query(MySQL::$link, $sql))) {
      MySQL::error($sql);
    }
    return $result;
  }

  public static function escape($str) {
    return mysqli_real_escape_string(MySQL::$link, $str);
  }

  // Tables must have the same structure
  public static function move_row($table_from, $table_to, $key_field, $value) {
    $table_from = MySQL::escape($table_from);
    $table_to = MySQL::escape($table_to);
    $cond = ' WHERE ' . MySQL::escape($key_field)
      . '=' .  MySQL::escape($value);

    /* TODO: Use START TRANSACTION; ... COMMIT; instead of LOCKING if the
       storage engine supports that (check for InnoDB vs MyISAM) */
    MySQL::query("LOCK TABLES $table_from WRITE, $table_to WRITE");

    MySQL::query("INSERT INTO $table_to SELECT * FROM $table_from $cond");
    MySQL::query("DELETE FROM $table_from $cond");

    MySQL::query("UNLOCK TABLES");
  }
}

?>
