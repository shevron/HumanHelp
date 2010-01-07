<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $book = $this->_getParam('book', Zend_Registry::get('config')->defaultBook);
        $page = $this->_getParam('page');

        $commentForm = new HumanHelp_Form_Comment(array(
            'action' => '#post-comment'
        ));
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($commentForm->isValid($formData)) {
                echo 'w00t';
            }
        }
        
        $this->view->book = new HumanHelp_Model_Book($book);
        
        if ($page) {
            $this->view->page = $this->view->book->getPage($page);
        } else {
            $this->view->page = $this->view->book->getDefaultPage();
        }
        
        $this->view->commentForm = $commentForm;
    }
}

