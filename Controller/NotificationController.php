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
    
    public $components = array('Email', 'IdacFlour.Sms');
    
    public $helpers = array('IdacFlour.NumberWang', 'Time');
}
