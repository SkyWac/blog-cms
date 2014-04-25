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
                $this->flashMessenger()->addMessage('<div class="alert alert-success">Your blog < '. $data->titre .' > has been created !<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                return $this->redirect()->toUrl('/manager');
            }
        }
         
        return array('form' => $form);
    }

    public function indexAction()
    {
        $blog = $this->objectManager->getRepository('Blog\Entity\Blog')->findBy(array("ownerId" => $this->zfcUserAuthentication()->getIdentity()->getId()));
        return new ViewModel(array("blog" => $blog));
    }

    public function editAction()
    {
        $blog = $this->objectManager->getRepository('Blog\Entity\Blog')->find($this->params()->fromRoute('id'));

        if($blog->ownerId != $this->zfcUserAuthentication()->getIdentity()->getId()) {
            $this->flashMessenger()->addMessage('<div class="alert alert-danger">This blog is not your !<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
            return $this->redirect()->toUrl('/manager');   
        }

        $builder = new AnnotationBuilder;
        $form = $builder->createForm($this->blogEntity);
        $form->bind($this->blogEntity);
        $form->setData($this->blogEntity->blogToArray($blog));

        $req = $this->getRequest();
        if($req->isPost()) {
            $info = array_merge($this->blogEntity->blogToArray($blog), $this->blogEntity->blogToArray($req->getPost()));
            $form->setData($info);
            if($form->isValid()) {
                $data = $form->getData();
                $this->blogEntity->attach($info);
                $this->objectManager->merge($this->blogEntity);
                $this->objectManager->flush();
                $this->flashMessenger()->addMessage('<div class="alert alert-success">Your blog < '. $data->titre .' > has been updated !<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                return $this->redirect()->toUrl('/manager');
            }
        }

        $view = new ViewModel(array('blog' => $blog, 'form' => $form));
        $view->setTerminal(true);
        return $view;
    }
}
