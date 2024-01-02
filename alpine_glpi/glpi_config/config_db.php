<?php
class DB extends DBmysql {
   public $dbhost = 'glpi_db';
   public $dbuser = 'glpi_db_user';
   public $dbpassword = 'glpi_db_pass';
   public $dbdefault = 'glpi_db';
   public $use_utf8mb4 = true;
   public $allow_myisam = false;
   public $allow_datetime = false;
   public $allow_signed_keys = false;
}
