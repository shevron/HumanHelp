<?php

/**
 * HumanHelp - Routes Configuration File
 * 
 */

return array(
    'book' => array(
        'route' => ':book',
        'defaults' => array(
            'controller' => 'index',
            'action'     => 'index'
        )
    ),
    
    'book-page' => array(
        'route' => ':book/:page',
        'defaults' => array(
            'controller' => 'index',
            'action'     => 'index'
        )
    )
);