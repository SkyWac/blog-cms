<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

class ManagerController extends AbstractActionController
{

    /**
     * Attache les évènements
     * @see \Zend\Mvc\Controller\AbstractController::attachDefaultListeners()
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
         
        $events = $this->getEventManager();
        $events->attach('dispatch', array($this, 'preDispatch'), 100);
        $events->attach('dispatch', array($this, 'postDispatch'), -100);
    }
     
    /**
     * Avant l'action
     * @param MvcEvent $e
     */
    public function preDispatch (MvcEvent $e)
    {
    	 /** Check isAuth() **/
         if(!$this->zfcUserAuthentication()->hasIdentity()) {
         	return $this->redirect()->toUrl('/user/login');
         }

         /** Custom Layout **/
         $this->layout('blog/manager/layout.phtml');

    }
     
    /**
     * Après l'action
     * @param MvcEvent $e
     */
    public function postDispatch (MvcEvent $e)
    {
         
    }

    public function indexAction()
    {
        return new ViewModel();
    }


}
