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
    'notifyComments' => 'shahar.e@zend.com',

    /* Date Format */
    'dateFormat' => 'l F jS H:i',

    /* Uncomment the next lines to enable reCaptcha protection for comments */
    /*
    'captcha' => array(
        'type'    => 'recaptcha',
        'service' => array(
            'publickey'  => '6LfEWgoAAAAAAI0WlkYqb0MbfFKz9X_V8B1CVRpY',
            'privatekey' => '6LfEWgoAAAAAAFfsHs_0VK85Lxi0UZlsjRoOM2dJ',
        ),
        'options' => array(
            'theme'      => 'clean'
        )
    )
    */
);