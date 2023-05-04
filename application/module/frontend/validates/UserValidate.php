<?php

class UserValidate extends Validate
{
    function __construct($params)
    {
        parent::__construct($params['form']);
    }

    //===== VALI DATE FRONT END ======
    public function validate($model)
    {
        $email    = $this->source['email'];
        $password = md5($this->source['password']);
        $query    = "SELECT `id` FROM `" . TBL_USER . "` WHERE `email` = '$email' AND `password` = '$password'";
        
        $this->addRule('username', 'string', ['min' => 3, 'max' => 255]);
        $this->addRule('email', 'email-notExistRecord', ['database' => $model, 'query' => $query]);
        $this->addRule('password', 'password', ['action' => 'add']);
        $this->run();
    }
    //===== VALI DATE FRONT END ======
    public function validateFrontend($model)
    {
        $email = $this->source['email'];
        $password = md5($this->source['password']);
        $query = "SELECT `id` FROM `" . TBL_USER . "` WHERE `email` = '$email' AND `password` = '$password'";

        $this->addRule('email', 'existRecord', ['database' => $model, 'query' => $query]);
        $this->addRule('password', 'existRecord', ['database' => $model, 'query' => $query]);
        $this->run();
    }
}
