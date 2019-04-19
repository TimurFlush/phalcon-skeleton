<?php declare(strict_types = 1);

namespace app\routes;

use Phalcon\Mvc\Router\Group;

class SiteRoutes extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'site',
            'namespace' => 'site\\controllers',
        ]);

        $this->add('/', [
            'controller' => 'index',
            'action' => 'index'
        ]);
    }
}
