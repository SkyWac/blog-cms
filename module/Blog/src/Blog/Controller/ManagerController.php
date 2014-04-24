<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Zend\Form\Annotation\AnnotationBuilder;

use Blog\Entity\Blog as BlogEntity;

class ManagerController extends AbstractActionController
{
    private $blogEntity,
            $objectManager;

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

         /** AutoLoad Entity */
         $this->blogEntity = new BlogEntity;

         /** Entity Manager */
        $this->objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
     
    /**
     * Après l'action
     * @param MvcEvent $e
     */
    public function postDispatch (MvcEvent $e)
    {

    }

    public function createAction()
    {
        $builder = new AnnotationBuilder();
        $form = $builder->createForm($this->blogEntity);

        $request = $this->getRequest();
        if ($request->isPost()){
            $form->bind($this->blogEntity);
            $form->setData($request->getPost());
            if ($form->isValid()){
                $data = $form->getData();
                $data->ownerId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $this->blogEntity->attach($data);
                $this->objectManager->persist($this->blogEntity);
                $this->objectManager->flush();
            }
        }
         
        return array('form'=>$form);
    }

    public function indexAction()
    {
        return new ViewModel();
    }


}
