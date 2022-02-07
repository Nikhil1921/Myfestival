<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;

if($_SERVER['HTTP_HOST'] == 'localhost'){
    $db['default'] = array(
        'dsn'   => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'marusuvichar',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => (ENVIRONMENT !== 'production'),
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
}else{
    $db['default'] = array(
        'dsn'   => '',
        'hostname' => 'localhost',
        'username' => 'marusuvichar_db',
        'password' => 'marusuvichar_marusuvichar',
        'database' => 'marusuvichar_marusuvichar',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => (ENVIRONMENT !== 'production'),
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
}