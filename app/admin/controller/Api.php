<?php
namespace app\admin\controller;
class Index extends Base
{
    public function Index()
    {
        $title = '云采集';
        $this->assign([
            'title' => $title,
        ]);
        return $this->fetch();
    }
}
