<?php
require_once('Net/SMS.php');

/**
* Component to send SMS message through AQL (aka sms2email)
*
* Usage:
* Declare the component in your controller with:
*
* var $components = array('Sms' => array('username' => '****', 'password' => '****'));
*
* Then you can view the current credit balance with:
*
* $this->Sms->getBalance();
*
* You can send messages with:
*
* $this->Sms->send(
*   array(
*      'id'       => 1,                          // "unique" id (it doesn't seem to worry it).
*      'text'     => 'text of message to send',  // message
*      'to'       => array('448701234567'),      // list of recipients
*      'from'     => 'INAA',                     // optional sender (seems to be ignored)
*      'msg_type' => 'SMS_FLASH'                 // optional flash message flag
*
* Return code parsing seems to be bad for multiple recipients.
*
*
* An array will be returned with an entry for each recipient. If
* there was an error with any of the recipients the corresponding
* element will contain a non-empty error 'field'. For example:
*
* Array ( 
*          [0] => Array ( 
*                          [message_id] => 1 
*                          [remote_id]  => 
*                          [recipient]  => 4477990373519999999
*                          [error]      => AQSMS-AUTHERROR 
*                       ) 
*        ) 
*
* @see http://www.sms2email.com/site/developerinfo2.php
* @see http://pear.php.net/package/Net_SMS/docs/latest/Net_SMS/Net_SMS_sms2email_http.html
*/ 
class SmsComponent extends Component
{
    private $sms;
    
    private $param;
    
    private static $defaults = array(
        'username' => null,
        'password' => null,
        'enabled' => true
    );
    
    
    public function initialize($controller, $param)
    {
        if ($param)
        {
            return $this->init($param); // optionally initialise from static components array.
        }
    }
    
    public function init($param)
    {
        if (!isset($param['username'])) throw new Exception('No SMS username supplied');
        if (!isset($param['password'])) throw new Exception('No SMS password supplied');
        
        $param = Set::merge(self::$defaults, $param);
        
        $this->param = $param;
        
        return $this->sms = Net_SMS::factory('sms2email_http', array(
            'user'     => $param['username'],
            'password' => $param['password']
        ));
    }
    
    public function getBalance()
    {
        if (!isset($this->sms)) throw new Exception('No SMS gateway configured');
        return $this->sms->getBalance();
    }
    
    /**
     * Send an SMS message
     *
     * @param array $message
     * @return boolean|PEAR Error True on success
     */
    public function send($message)
    {
        if (!isset($this->sms))
        {
            throw new Exception('No SMS gateway configured');
        }
        if ($this->param['enabled'] == true)
        {
            if (Configure::read('debug') > 1)
            {
                return true;
            }
            return $this->sms->send($message);
        }
        return true;
    }
    
    public function hasCapability($capability)
    {
        if (!isset($this->sms))
        {
            throw new Exception('No SMS gateway configured');
        }
        return $this->sms->hasCapability($capability);
    }
}