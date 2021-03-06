<?php

class Application_Form_AdContacts extends Zend_Form
{
    public $isReady;

    public function __construct($options = null)
    {
        $this->isReady = $options["isReady"]?true:false;
        $this->setAttrib("id", "ad_contacts_form");
        parent::__construct($options);
    }

    public function init()
    {
        global $translate;

        $this->addElement('hidden', 'id');
        $this->getElement("id")->setDecorators(array('ViewHelper'));

        $this->addElement('hidden', 'form');
        $this->getElement("form")->setValue("AdContacts");
        $this->getElement("form")->setDecorators(array('ViewHelper'));

        $this->addElement('text', 'address', array(
            'class' => "form-control",
            'label' => $translate->getAdapter()->translate("address") . ' *',
            'validators' => array(
                array('StringLength', false, array(0, 255)),
            ),
            'required' => true,
        ));

        $this->addElement('text', 'phone', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'class' => "form-control",
            'label' => $translate->getAdapter()->translate("phone") . ' *',
            'validators' => array(
                array('StringLength', false, array(0, 45)),
            ),
            'required' => true,
        ));

        $this->addElement('text', 'phone1', array(
            'class' => "form-control",
            'label' => $translate->getAdapter()->translate("additional_phone"),
            'validators' => array(
                array('StringLength', false, array(0, 14))
            ),
//            'required' => true,
        ));

        $this->addElement('text', 'phone2', array(
            'class' => "form-control",
            'label' => $translate->getAdapter()->translate("additional_phone"),
            'validators' => array(
                array('StringLength', false, array(0, 14))
            ),
//            'required' => true,
        ));

        $this->addElement('text', 'email', array(
            'class' => "form-control",
            'label' => $translate->getAdapter()->translate("email") . ' *',
            'validators' => array(
                array('EmailAddress')
            ),
            'required' => true,
        ));


        $this->addElement('text', 'url', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'class' => "form-control",
            'label' => $translate->getAdapter()->translate("url"),
            'validators' => array(
                array('StringLength', false, array(0, 255)),
                new Custom_Form_Validator_Url()
            ),
            //'required' => true,
        ));

        $this->addElement('submit', 'login', array(
            'class' => 'btn btn-primary',
            'required' => false,
            'ignore' => true,
            'label' => $translate->getAdapter()->translate($this->isReady?"finish":"save_and_next")
        ));
    }
}

