<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('verify_access'))
{
    function verify_access($name, $action)
    {
        $access = [
            'banner' => ['list'],
            'category' => ['list'],
            'upcoming' => ['list'],
            'users' => ['list', 'add', 'update', 'delete']
        ];

        switch (get_instance()->session->role) {
            case 'Employee':
                $check = isset($access[$name]) ? in_array($action, $access[$name]) : false;
                break;
            case 'Sub Admin':
                $check = isset($access[$name]) ? in_array($action, $access[$name]) : false;
                break;
            
            default:
                $check = true;
                break;
        }
        
        return $check === true ? true : die;
    }
}

if ( ! function_exists('check_access'))
{
    function check_access($name, $action)
    {
        $access = [
            'banner' => ['list'],
            'category' => ['list'],
            'upcoming' => ['list'],
            'users' => ['list', 'add', 'update', 'delete']
        ];

        switch (get_instance()->session->role) {
            case 'Employee':
                return isset($access[$name]) ? in_array($action, $access[$name]) : false;     
                break;
            case 'Sub Admin':
                return isset($access[$name]) ? in_array($action, $access[$name]) : false;
                break;
            
            default:
                return true;
                break;
        }
    }
}