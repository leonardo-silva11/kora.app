<?php
namespace app\kora\filters;

trait Authentication
{
    public function expiredToken(string $name = null,string $fixedParamexpiredToken)
    {
        dump('Authentication::expiredToken with params: ',$name,$fixedParamexpiredToken);
    }

    public function requestToken($name = null,$height = null,$fixedParamrequestToken,$Collections)
    {
        dump('Authentication::requestToken with params: ',$name,$height,$fixedParamrequestToken);
    }

    public function finished($Collections,$age = 0,$height = 0,$fixedParam = null)
    {
        dump('Authentication::finished with params: ',$height);
    }
}