<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $config = Zend_Registry::get('config');
        $bookName = $this->_getParam('book', $config->defaultBook);
        $pageName = $this->_getParam('page');
        
        $book = new HumanHelp_Model_Book($bookName);
        
        if ($pageName) {
            $page = $book->getPage($pageName);
        } else {
            $page = $book->getDefaultPage();
            $pageName = $page->getName();
        }
        
        $commentForm = new HumanHelp_Form_Comment(array(
            'action' => '#post-comment'
        ));
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($commentForm->isValid($formData)) {
                
                $comment = new HumanHelp_Model_Comment(array(
                    'author_name'  => $commentForm->getValue('name'),
                    'author_email' => $commentForm->getValue('email'),
                    'created_at'   => $_SERVER['REQUEST_TIME'],
                    'book'         => $bookName,
                    'page'         => $pageName,
                    'comment'      => $commentForm->getValue('comment'),
                    'token'        => HumanHelp_Model_Comment::generateToken(),
                ));
                
                if (! $config->moderateComments) {
                    $comment->setFlags(HumanHelp_Model_Comment::FLAG_APPROVED);
                }
                
                try {
                    $comment->save();
                
                    // Redirect back to page
                    $this->_redirect("/$bookName/$pageName?done=ok#");
                } catch (Exception $ex) {
                    throw $ex;
                }
            }
        }
        
        $this->view->book = $book; 
        $this->view->page = $page;
        $this->view->commentForm = $commentForm;
        $this->view->commentsAreModerated = $config->moderateComments;  
    }
}

