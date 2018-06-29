<?php
namespace Home\Controller;

use Common\Controller\CommonController;

class UserController extends CommonController
{
    public function index()
    {
        $this->display('userInfo');
    }

    public function login()
    {
        $this->display();
    }
}