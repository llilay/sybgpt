<?php

namespace Home\Controller;

use Common\Controller\CommonController;
use Ku;

class IndexController extends CommonController
{
    public function index()
    {
        $menu = Ku\Utils::hTree(session('menu'));
        $this->assign('data', $menu);
        $this->display();
    }

    public function main()
    {
        $this->display();
    }
}
