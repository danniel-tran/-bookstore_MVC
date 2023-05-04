<?php

class CartValidate extends Validate
{
    function __construct($params)
    {
        parent::__construct($params['form']);
    }

    public function validate($model)
    {

        $queryUsername = "SELECT `id` FROM `" . TBL_USER . "`WHERE `username` = '{$this->source['username']}'";
        if (isset($this->source['id'])) {
            $queryUsername .= " AND `id` <> '{$this->source['id']}'";
        } else {
            $this->addRule('password', 'string', ['min' => 8, 'max' => 32]);
        }
        $this->addRule('username', 'existRecord', ['database' => $model, 'query' => $queryUsername], false)
            ->addRule('status', 'status', ['deny' => ['default']], false)
            ->addRule('book-quantity', 'bookCartQuantity',  ['min' => 0, "max" => 13], false)
            ->addRule('book-id', 'bookCartId', null, false)
            ->addRule('dateOrder', 'date', ["start" => "01/01/2020", "end" => "01/01/2022"]);
        $this->run();
    }
}
