<?php
  define('DB_SERVER_NAME', 'SERVER_IP_OR_NAME');
  define('DB_USER', 'USERNAME');
  define('DB_PASSWORD', 'PASSWORD');
  define('DB_NAME', 'NAME_OF_THE_DB');

  $conn = mysql_connect(DB_SERVER_NAME, DB_USER, DB_PASSWORD);
  mysql_select_db(DB_NAME,$conn);

?>
