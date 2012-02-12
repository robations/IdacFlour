<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Enables models to send emails or SMS notifications or messages
 */
class NotifiableBehavior extends ModelBehavior
{
    protected $notificationController;

    public function setup(&$model, $settings = array())
    {
        if (empty($this->notificationController))
        {
            App::import('Controller', 'IdacFlour.Notification');
            $this->notificationController = new NotificationController();
            $this->notificationController->constructClasses();
            
            $this->notificationController->Email = new EmogrifiedEmailComponent();
            
            $this->notificationController->Email->initialize($this->NotificationController);
            $this->notificationController->Email->delivery = Configure::read('Email.delivery');
            if ($this->notificationController->Email->delivery === 'smtp')
            {
                $this->notificationController->Email->smtpOptions 
                    = Configure::read('Email.smtpOptions');
            }
            //$this->NotificationController->Sms->initialize($this->NotificationController, Configure::read('Sms'));
        }
    }
    
    public function getNotificationController()
    {
        return $this->notificationController;
    }
}