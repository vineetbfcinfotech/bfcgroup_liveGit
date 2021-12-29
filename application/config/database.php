<?php
   defined('BASEPATH') OR exit('No direct script access allowed');
   include_once(APPPATH . 'config/app-config.php');
   
   $active_group = 'default';
   $query_builder = TRUE;
   //'db_debug' => (ENVIRONMENT !== 'production'),
   
   $db['default'] = array(
      'dsn' => '', // Not Supported
      'hostname' => APP_DB_HOSTNAME,
      'username' => APP_DB_USERNAME,
      'password' => APP_DB_PASSWORD,
      'database' => APP_DB_NAME,
      'dbdriver' => defined('APP_DB_DRIVER') ? APP_DB_DRIVER : 'mysqli',
      'dbprefix' => '', // Not Supported
      'pconnect' => FALSE,
      'db_debug' => (ENVIRONMENT !== 'production'),
      'cache_on' => FALSE,
      'cachedir' => '',
      'char_set' => 'utf8',
      'dbcollat' => 'utf8_general_ci',
      'swap_pre' => '',
      'encrypt' => FALSE,
      'compress' => FALSE,
      'stricton' => FALSE,
      'failover' => array(),
      'save_queries' => TRUE
   );

   $db['rock'] = array(
      'dsn' => '', // Not Supported
      'hostname' => APP_DB_HOSTNAME,
      'username' => 'root',
      'password' => '',
      'database' => 'bfcpublicationswebsite',
      'dbdriver' => defined('APP_DB_DRIVER') ? APP_DB_DRIVER : 'mysqli',
      'dbprefix' => '', // Not Supported
      'pconnect' => FALSE,
      'db_debug' => (ENVIRONMENT !== 'production'),
      'cache_on' => FALSE,
      'cachedir' => '',
      'char_set' => 'utf8',
      'dbcollat' => 'utf8_general_ci',
      'swap_pre' => '',
      'encrypt' => FALSE,
      'compress' => FALSE,
      'stricton' => FALSE,
      'failover' => array(),
      'save_queries' => TRUE
   );
   
   $db['anotherdb'] = array(
      'dsn' => '', // Not Supported
      'hostname' => APP_DB_HOSTNAME,
      'username' => 'root',
      'password' => '',
      'database' => 'bfcpublicationswebsite',
      'dbdriver' => defined('APP_DB_DRIVER') ? APP_DB_DRIVER : 'mysqli',
      'dbprefix' => '', // Not Supported
      'pconnect' => TRUE,
      'db_debug' => (ENVIRONMENT !== 'production'),
      'cache_on' => FALSE,
      'cachedir' => '',
      'char_set' => 'utf8',
      'dbcollat' => 'utf8_general_ci',
      'swap_pre' => '',
      'autoinit' => FALSE,
      'stricton' => FALSE,
   );
    $db['secend_db'] = array(
      'dsn' => '', // Not Supported
      'hostname' => APP_DB_HOSTNAME,
      'username' => 'bfcpubli_pub_web_new',
      'password' => 'PubWeb@2021$$',
      'database' => 'bfcpubli_pub_web_new',
      'dbdriver' => defined('APP_DB_DRIVER') ? APP_DB_DRIVER : 'mysqli',
      'dbprefix' => '', // Not Supported
      'pconnect' => TRUE,
      'db_debug' => (ENVIRONMENT !== 'production'),
      'cache_on' => FALSE,
      'cachedir' => '',
      'char_set' => 'utf8',
      'dbcollat' => 'utf8_general_ci',
      'swap_pre' => '',
      'autoinit' => FALSE,
      'stricton' => FALSE,
   );
      $db['dev_db'] = array(
      'dsn' => '', // Not Supported
      'hostname' => APP_DB_HOSTNAME,
      'username' => 'bfcmysqladmin',
      'password' => 'BFC#capital#009$&&*',
      'database' => 'bfcpubli_dev_pub_web',
      'dbdriver' => defined('APP_DB_DRIVER') ? APP_DB_DRIVER : 'mysqli',
      'dbprefix' => '', // Not Supported
      'pconnect' => TRUE,
      'db_debug' => (ENVIRONMENT !== 'production'),
      'cache_on' => FALSE,
      'cachedir' => '',
      'char_set' => 'utf8',
      'dbcollat' => 'utf8_general_ci',
      'swap_pre' => '',
      'autoinit' => FALSE,
      'stricton' => FALSE,
   );