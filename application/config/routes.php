<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['adminPanel'] = 'adminPanel/home';
$route['adminPanel/logout'] = 'adminPanel/home/logout';
$route['adminPanel/dashboard'] = 'adminPanel/home';
$route['adminPanel/banner']['post'] = 'adminPanel/banner/get';
$route['adminPanel/users']['post'] = 'adminPanel/users/get';
$route['adminPanel/employee']['post'] = 'adminPanel/employee/get';
$route['adminPanel/sub-admin']['post'] = 'adminPanel/sub-admin/get';
$route['adminPanel/users/update/$1']['get'] = 'adminPanel/users/edit/$1';
$route['adminPanel/upcoming']['post'] = 'adminPanel/upcoming/get';
$route['adminPanel/upcoming/update/$1']['get'] = 'adminPanel/upcoming/edit/$1';
$route['adminPanel/category']['post'] = 'adminPanel/category/get';
$route['adminPanel/category/update/$1']['get'] = 'adminPanel/category/edit/$1';

$route['adminPanel/profile'] = 'adminPanel/home/profile';
$route['adminPanel/changePassword'] = 'adminPanel/home/changePassword';
$route['adminPanel/forgotPassword'] = 'adminPanel/login/forgotPassword';
$route['adminPanel/checkOtp'] = 'adminPanel/login/checkOtp';
$route['adminPanel/unauthorized'] = 'adminPanel/home/unauthorized';