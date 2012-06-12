<?php

class Enquiry extends AppModel
{

    public $useTable = false;

    protected $_schema = array(
        'name' => array('type' => 'string', 'length' => 100),
        'email' => array('type' => 'string', 'length' => 255),
        'phone' => array('type' => 'string', 'length' => 20),
        'message' => array('type' => 'text'),
    );

    public $validate = array(
        'name' => array(
            'rule' => array('minLength', 1),
            'message' => 'Name is required'
        ),
        'email' => array(
            'rule' => 'email',
            'message' => 'Must be a valid email address'
        ),
        'message' => array(
            'rule' => array('minLength', 1),
            'message' => 'Please write a message'
        ),
    );

}
