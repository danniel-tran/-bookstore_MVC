<?php

class UserValidate extends Validate
{
    function __construct($params)
    {
        parent::__construct($params['form']);
    }

    public function validate($model)
    {
        $queryUsername = "SELECT `id` FROM " . TBL_USER . " WHERE `username` = '{$this->source['username']}'";
        $queryEmail = "SELECT `id` FROM " . TBL_USER . " WHERE `email` = '{$this->source['email']}'";
        if (isset($this->source['id'])) {
            $queryUsername .= " AND `id` <> '{$this->source['id']}'";
            $queryEmail .= " AND `id` <> '{$this->source['id']}'";
        } else {
            $this->addRule('password', 'string', ['min' => 8, 'max' => 32]);
        }
        $this->addRule('username', 'string-notExistRecord', ['database' => $model, 'query' => $queryUsername, 'min' => 3, 'max' => 50])
            ->addRule('email', 'email-notExistRecord', ['database' => $model, 'query' => $queryEmail])
            ->addRule('status', 'status', ['deny' => ['default']])
            ->addRule('group_id', 'status', ['deny' => ['default']]);
        $this->run();
    }
}
