<?php

// use App\Repositories\ModuleRepository;

if (!function_exists('pr')) {
    function pr($data = array(),$exit = false)
    {
        echo "<pre>";
        print_r($data);
        echo "<pre>";
        if($exit)
            exit;
    }
}