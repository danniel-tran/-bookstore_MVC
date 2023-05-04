<?php

class SliderValidate extends Validate
{
    function __construct($params)
    {
        parent::__construct($params['form']);
    }

    public function validate($model)
    {
        $queryName = "SELECT `id` FROM " . TBL_CATEGORY . " WHERE `name` = '{$this->source['name']}'";
        if (isset($this->source['id'])) {
            $queryName .= " AND `id` <> '{$this->source['id']}'";
        }
        $this->addRule('name', 'string-notExistRecord', ['database' => $model, 'query' => $queryName, 'min' => 3, 'max' => 100])
            ->addRule('description', 'string', ['min' => 4, 'max' => 300])
            ->addRule('status', 'status', ['deny' => ['default']], false)
            ->addRule('group_acp', 'status', ['deny' => ['default']])
            ->addRule('ordering', 'int', ['min' => 1, 'max' => 99999999999999])
            ->addRule('picture', 'file', ['min' => 1, 'max' => 10000000000, 'extension' => ['jpg', 'png']], false);
        $this->run();
    }
    public function validatePicture($model = null)
    {
        $this->addRule('picture', 'file', ['min' => 1, 'max' => 10000000000, 'extension' => ['jpg', 'png', "gif"]], false);
        $this->run();
    }
}
