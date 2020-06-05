<?php

namespace app\home\model;

use think\Model;

class Category extends Model
{
    public function article(){
        return $this->hasOne('Article');
    }

}