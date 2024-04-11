<?php
namespace app\kora\controllers;

use app\kora\filters\Authentication;
use app\kora\filters\FilterTest;
use kora\bin\ControllerKora;
use kora\lib\collections\Collections;

class HomeController extends ControllerKora
{
    use Authentication;
    use FilterTest;
    
    public function __construct(Collections $Collections = null,$A1 = null,$A2 = null,$age = null)
    {
        parent::__construct();
       // dd($Collections,$A1,$A2,$age);
    }

    public function index($age = null,$name = null,$height = null,$A2 = null, $image = null,$id = null)
    {
        dump('Home/index',$age,$name,$height,$id);
    }

    public function help()
    {
        dd('ok help');
    }
}

