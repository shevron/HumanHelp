<?php

class HumanHelp_Form_Comment extends Zend_Form
{
    public function __construct($options = array())
    {
        $options['disableLoadDefaultDecorators'] = true;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->setMethod('post');
        
        // Set Decorators
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'form')),
            'Form'
        ));
        
        // Add Elements
        $this->addElement('text', 'name', array(
            'label'      => 'Your name',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(3)),
                array('Regex', false, array('/^\pL[\pL\pN]*(?:[ .\-_\']\pL[\pL\pN]*)*$/uD')),
            ),
            'description' => 'Please enter your name - this will be displayed next to your comment'
        ));
        
        $this->addElement('text', 'email', array(
            'label'      => 'Your Email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('emailaddress')
            ),
            'description' => 'Your Email will not be shared with anyone or displayed to other users'
        ));
        
        $this->addElement('textarea', 'comment', array(
            'label'      => 'Comment',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength',false, array(10))
            ),
            'rows' => 12
        ));
        
        $captchaConfig = Zend_Registry::get('config')->captcha;
        if ($captchaConfig) {
            
            $captchaOptions = $captchaConfig->options->toArray();
            
            if ($captchaConfig->type == 'recaptcha') {
                $reCaptcha = new Zend_Service_ReCaptcha(
                    $captchaConfig->service->publickey,
                    $captchaConfig->service->privatekey
                );
                $captchaOptions['service'] = $reCaptcha;
            }
                
            $this->addElement('captcha', 'challenge', array(
                'captcha' => $captchaConfig->type,
                'captchaOptions' => $captchaOptions,
                'label' => 'Are you human?',
                'description' => 'Please verify that you are a real person by typing in the two words above'
            ));
        }

//        $book = new Zend_Form_Element('hidden', 'book');
//        $book->clearDecorators();
//        $this->addElement($book);
//        
//        $page = new Zend_Form_Element('hidden', 'page');
//        $page->clearDecorators();
//        $this->addElement($page);

        $this->addElement('submit', 'submit', array(
            'label' => 'Send Comment'
        ));
    } 
}