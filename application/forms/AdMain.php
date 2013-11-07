<?php

class Application_Form_AdMain extends Zend_Form
{

    public function init()
    {
        global $translate;

        $this->addElement('hidden', 'id');
        $this->getElement("id")->setDecorators(array('ViewHelper'));

        $this->addElement('hidden', 'form');
        $this->getElement("form")->setValue("AdMain");
        $this->getElement("form")->setDecorators(array('ViewHelper'));

        $this->addElement('text', 'name', array(
            'class' => "input-block-level",
            'label' => $translate->getAdapter()->translate("name"),
            'validators' => array(
                array('StringLength', false, array(0, 500)),
            ),
            // 'required' => true,
        ));

        $this->addElement('textarea', 'description', array(
            'class' => "input-block-level",
            'label' => $translate->getAdapter()->translate("description"),
            'max_length' => 500,
//            'validators' => array(
//                array('StringLength', false, array(0, 500))
//            ),
            // 'required' => true,
        ));

        $this->addElement('textarea', 'full_description', array(
            'class' => "input-block-level",
            'label' => $translate->getAdapter()->translate("full_description"),
            'max_length' => 2500,
            'validators' => array(
                array('StringLength', false, array(0, 2500)),
            ),
        ));

        $this->addElement('submit', 'login', array(
            //'class' => 'btn btn-large btn-primary',
            'required' => false,
            'ignore' => true,
            'label' => $translate->getAdapter()->translate("save_and_next"),
        ));
    }
}

