<?php

class Application_Form_AdMain extends Zend_Form
{

    public function init()
    {
        $this->addElement('hidden', 'id');
        $this->getElement("id")->setDecorators(array('ViewHelper'));

        $this->addElement('hidden', 'form');
        $this->getElement("form")->setValue("AdMain");
        $this->getElement("form")->setDecorators(array('ViewHelper'));

        $this->addElement('text', 'name', array(
            'class' => "input-block-level",
            'label' => "Name",
            'validators' => array(
                array('StringLength', false, array(0, 255)),
            ),
            // 'required' => true,
        ));

        $this->addElement('textarea', 'description', array(
            'class' => "input-block-level",
            'label' => "Description",
            'validators' => array(
                array('StringLength', false, array(0, 500))
            ),
            // 'required' => true,
        ));

        $this->addElement('submit', 'login', array(
            //'class' => 'btn btn-large btn-primary',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
        ));
    }
}

