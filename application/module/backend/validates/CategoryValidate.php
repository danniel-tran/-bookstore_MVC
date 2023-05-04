<?php
class CategoryValidate extends Validate
{
    //===== __CONSTRUCT ======
    public function __construct($params)
    {
        $dataForm = $params['form'] ?? [];
        parent::__construct($dataForm);
    }

    //===== VALIDATE ======
    public function validate($model)
    {
        $queryName = "SELECT `id` FROM " . TBL_CATEGORY . " WHERE `name` = '{$this->source['name']}'";
        if (isset($this->source['id'])) {
            $queryName .= " AND `id` <> '{$this->source['id']}'";
        }
        $this->addRule('name', 'string-notExistRecord', ['database' => $model, 'query' => $queryName, 'min' => 3, 'max' => 50])
            ->addRule('ordering', 'int', ['min' => 1, 'max' => 100])
            ->addRule('status', 'status', ['deny' => ['default']])
            ->addRule('picture', 'file', ['min' => 1, 'max' => 9000000, 'extension' => ['jpg', 'png']], false);
        $this->run();
    }
    // VALIDATE PICTURE
    public function validatePicture($model = null)
    {
        $this->addRule('picture', 'file', ['min' => 1, 'max' => 10000000000, 'extension' => ['jpg', 'png', "webp"]], false);
        $this->run();
    }
}
