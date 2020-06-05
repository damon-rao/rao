<?php

namespace app\home\model;

use think\Model;

class Article extends Model
{
//    和栏目关联
    public function user(){
        return $this->belongsTo('User');
    }
}
