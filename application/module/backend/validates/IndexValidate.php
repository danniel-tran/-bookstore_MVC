<?php

class IndexValidate extends Validate
{

    function __construct($params)
    {
        parent::__construct($params['form']);
    }

    //===== VALIDATE ======
    public function validate($model)
    {
        $username = $this->source['username'];
        $password = md5($this->source['password']);
        $query    = "SELECT `id` FROM `" . TBL_USER . "` WHERE `username` = '$username' AND `password` = '$password'";
        $this->addRule('username', 'existRecord', ['database' => $model, 'query' => $query], false)
            ->addRule('password', 'existRecord', ['database' => $model, 'query' => $query]);
        $this->run();
    }

    public function validateProfile($model)
    {



        $queryFullname = "SELECT `id` FROM " . TBL_USER . " WHERE `fullname` = '{$this->source['fullname']}'";
        $queryFullname .= " AND `id` <> '{$this->source['id']}'";

        $queryEmail    = "SELECT `id` FROM " . TBL_USER . " WHERE `email` = '{$this->source['email']}'";
        $queryEmail .= " AND `id` <> '{$this->source['id']}'";


        $this->addRule('fullname', 'string-notExistRecord', ['database' => $model, 'query' => $queryFullname, 'min' => 3, 'max' => 50])
            ->addRule('email', 'email-notExistRecord', ['database' => $model, 'query' => $queryEmail]);
        $this->run();
    }
}
