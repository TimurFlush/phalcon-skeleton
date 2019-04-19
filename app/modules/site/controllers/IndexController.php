<?php declare(strict_types = 1);

namespace site\controllers;

use Phalcon\Mvc\View\Simple;

/**
 * @property Simple $view
 */
class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        echo $this->view->render('index.volt');
    }
}
