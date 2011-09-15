<?php

App::import('Component', 'EmogrifiedEmail');

/**
 * Controller to be used within models for sending out notifications
 *
 */
class NotificationController extends AppController
{
    var $uses = array();
    
    /**
     * @var EmailComponent
     */
    public $Email;
    
    /**
     * @var SmsComponent
     */
    public $Sms;
    
    var $components = array('Email' /*, 'Sms' */);
    
    var $helpers = array('NumberWang', 'Time');
}


