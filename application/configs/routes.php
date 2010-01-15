<?php

/**
 * HumanHelp - Routes Configuration File
 * 
 */

return array(
    'book' => array(
        'route' => 'content/:book',
        'defaults' => array(
            'controller' => 'index',
            'action'     => 'index'
        )
    ),
    
    'book-page' => array(
        'route' => 'content/:book/:page',
        'defaults' => array(
            'controller' => 'index',
            'action'     => 'index'
        )
    )
);