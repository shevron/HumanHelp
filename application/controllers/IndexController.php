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
                    $this->_sendNewCommentEmail($comment, $page);
                    
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
    
    public function _sendNewCommentEmail(HumanHelp_Model_Comment $comment, HumanHelp_Model_Page $page)
    {
        $config = Zend_Registry::get('config');
        
        $emailTemplate = new Zend_View();
        $emailTemplate->setScriptPath(APPLICATION_PATH . '/views/emails');
        $emailTemplate->setEncoding('UTF-8');
        
        $emailTemplate->comment = $comment;
        $emailTemplate->page = $page;
        $emailTemplate->baseUrl = $this->view->baseUrl;
        
        $bodyHtml = $emailTemplate->render('newComment.phtml');
        $bodyText = $emailTemplate->render('newComment.txt');
         
        $mail = new Zend_Mail();
        $mail->setType(Zend_Mime::MULTIPART_ALTERNATIVE)
             ->setBodyHtml($bodyHtml, 'UTF-8')
             ->setBodyText($bodyText, 'UTF-8')
             ->setSubject("New comment on '{$page->getTitle()}' in '{$page->getBook()->getTitle()}'")
             ->setFrom($config->fromAddress, $config->fromName);
             
        if (is_object($config->notifyComments)) {
            foreach($config->notifyComments->toArray() as $rcpt) {
                $mail->addTo($rcpt);
            }
        } else {
            $mail->addTo($config->notifyComments);
        }

        if ($config->smtpServer) {
            $transport = new Zend_Mail_Transport_Smtp($config->smtpServer, $config->smtpOptions->toArray());
        } else {
            $transport = new Zend_Mail_Transport_Sendmail();
        }
        
        $mail->send($transport);
    }
}

