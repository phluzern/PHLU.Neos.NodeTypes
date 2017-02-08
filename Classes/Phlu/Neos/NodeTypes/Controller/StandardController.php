<?php
namespace Phlu\Neos\NodeTypes\Controller;

/*
 * This file is part of the Phlu.Neos.NodeTypes package.
 */

use Neos\Flow\Annotations as Flow;

class StandardController extends \Neos\Flow\Mvc\Controller\ActionController
{

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('foos', array(
            'bar', 'baz'
        ));
    }

}
