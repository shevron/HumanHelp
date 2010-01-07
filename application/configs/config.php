<?php

/**
 * HumanHelp - Main Configuration File
 */

return array(
    'defaultBook' => 'zend-server',

    /* Uncomment the next lines to enable reCaptcha protection for comments */
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
);