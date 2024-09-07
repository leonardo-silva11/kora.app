<?php

use kora\bin\DefaultResponseKora;
use kora\bin\RouterKora;
use kora\lib\storage\DirectoryManager;

require_once(__DIR__ . "/vendor/autoload.php");

$SoundSingularity = function
(
    $useRoutesInProject = false,
    $useSettingsInProject = false,
    $displayErrors = true,
    $allowedOrigins = [
        "http://localhost",
    ],
)
{
    try 
    {
        $pathOfProject = __DIR__;
        $nameOfProject = basename(__DIR__);
        
        ini_set('display_errors',$displayErrors);
    
        $defaultStorage = new DirectoryManager($nameOfProject);

        RouterKora::start
        ([
            'pathOfProject' => $pathOfProject,
            'nameOfProject' => $nameOfProject,
            'useRoutesInProject' => $useRoutesInProject,
            'useSettingsInProject' => $useSettingsInProject,
            'ignoreOrigin' => true,
            'allowedOrigins' => $allowedOrigins
        ],$defaultStorage);

    } 
    catch (\Throwable $th) 
    {
        (new DefaultResponseKora())
            ->parseThrowable($th,[
                'Content-Type' => 'application/json'
            ]);
    }
};

$SoundSingularity();