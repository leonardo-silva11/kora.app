<?php
use kora\bin\RouterKora;
use kora\lib\exceptions\DefaultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use kora\lib\storage\DirectoryManager;

require_once(__DIR__ . "/vendor/autoload.php");

class Main
{
    private const FILE_SETTINGS_JSON = 'appsettings.json'; 
    private static $instance = null;
    private static $exceptionSettings = [
        'line' => true,
        'file' => true,
        'details' => true,
        'defaultHiddenMessage' => 'hidden due to system settings'
    ];

    public function __construct()
    {
        ini_set('display_errors', 1);
    }

    private function lastInterruptionException(Throwable $th)
    {
        $code = $th->getCode();

        $httpCode = is_int($code) && $code >= 100 && $code <= 599 ? $code : 500;

        $details = method_exists($th,'getDetails') ? $th->getDetails() : [];

        $file = mb_substr(strrchr($th->getFile(), DIRECTORY_SEPARATOR), 1);

        $httpResponse = [
            'message' => $th->getMessage(),
            'file' => self::$exceptionSettings['file'] ? $file : self::$exceptionSettings['defaultHiddenMessage'],
            'line' => self::$exceptionSettings['line'] ? $th->getLine() : self::$exceptionSettings['defaultHiddenMessage'],
            'details' => self::$exceptionSettings['details'] ? $details : self::$exceptionSettings['defaultHiddenMessage'],
        ];

        $response = new JsonResponse($httpResponse,intval($httpCode));

        $response->send();
        exit();
    }

    private function getPathAppSettings($pathAppSettings = null) : string
    {
        $path = $pathAppSettings;
      
        try 
        {
            if(empty($path))
            {
                $dir = new DirectoryManager();
                $path = sprintf("{$dir->getCurrentStorage()}{$dir->getDirectorySeparator()}%s",self::FILE_SETTINGS_JSON);
            }


            if(!file_exists($path))
            {
                throw new DefaultException("{{$path}} not found!");
            }
        } 
        catch (\Throwable $th) 
        {
            self::$instance->lastInterruptionException($th);
        }

        return $path;
    }

    public static function start($pathAppSettings = null)
    {
        try 
        {
            if(self::$instance == null)
            {
                self::$instance = new Main();
            }
    
            $path = self::$instance->getPathAppSettings();
            
            RouterKora::start($path);

        } 
        catch (\Throwable $th) 
        {
            self::$instance->lastInterruptionException($th);
        }
    }
}
Main::start();

