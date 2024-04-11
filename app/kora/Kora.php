<?php
namespace app\kora;

use kora\bin\AppKora as AppKora;
use kora\lib\collections\Collections;
use Symfony\Component\HttpFoundation\Request;

class Kora extends AppKora
{   
    public function __construct(Request $request)
    {
        parent::__construct($this, $request); 
    }

    public function extraConfig(): void 
    {
        dump("Kora::extraConfig call");
    }

    public function injectables(): array
    {
        return [];

        /**
         * use to inject instances configured in the route.json file
         * return [
         *       Collections::class => new Collections(),     
         *   ];
        */
    }
}
