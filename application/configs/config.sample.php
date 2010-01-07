<?php

/**
 * HumanHelp - Main Configuration File
 */

return array(
    /* Default book to use when no book is passed in the URL */
    'defaultBook' => 'zend-server',

    /* Require comment moderation? */
    'moderateComments' => true,

    /* Person to notify to when a new comment is posted.
     * Can be an array of email addresses */
    'notifyComments' => 'your-email@example.com',

    /* From name / address to use when sending emails */
    'fromAddress' => 'do-not-reply@example.com',
    'fromName'    => 'HumanHelp Automatic Email',

    /* Should mail be sent from SMTP server? If not sendmail will be used */
    /*
    'smtpServer'  => 'my.smtp.server.com',
    'smtpOptions' => array(
        'auth'     => 'password',
        'user'     => 'someuser',
        'password' => 'somepassword'
    ),
    */

    /* Date Format */
    'dateFormat' => 'l F jS H:i',

    /* Uncomment the next lines to enable captcha protection for comments */
    /*
    'captcha' => array(
        'type'    => 'recaptcha',
        'service' => array(
            'publickey'  => 'recaptcha-publickey',
            'privatekey' => 'recaptcha-privateckey',
        ),
        'options' => array(
            'theme'      => 'clean'
        )
    )
    */
);