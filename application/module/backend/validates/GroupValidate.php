<?php

class GroupValidate extends Validate
{
    function __construct($params)
    {
        parent::__construct($params['form']);
    }

    public function validate()
    {

        $this->addRule('name', 'string', ['min' => 3, 'max' => 255])
            ->addRule('status', 'status', ['deny' => ['default']])
            ->addRule('group_acp', 'status', ['deny' => ['default']]);

        $this->run();
    }
}
