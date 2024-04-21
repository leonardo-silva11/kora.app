<?php
use kora\bin\RouterKora;
use Symfony\Component\HttpFoundation\JsonResponse;
use kora\lib\storage\DirectoryManager;

require_once(__DIR__ . "/vendor/autoload.php");

class Main
{
    private string $nameProject;
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
        $this->nameProject = basename(__DIR__);
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

    public function getProject()
    {
        return $this->nameProject;
    }

    public function getDefaultStorage() : DirectoryManager
    {
        try 
        {
            $dir = new DirectoryManager($this->nameProject,[],true);

            return $dir;
        } 
        catch (\Throwable $th) 
        {
            self::$instance->lastInterruptionException($th);
        }
    }

    public static function start()
    {
        try 
        {
            
            if(self::$instance == null)
            {
                self::$instance = new Main();
            }
            
            RouterKora::start(self::$instance);

        } 
        catch (\Throwable $th) 
        {
            self::$instance->lastInterruptionException($th);
        }
    }
}
Main::start();
