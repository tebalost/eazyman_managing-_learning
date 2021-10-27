<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmClassSection extends Model
{
    public function sectionName()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
    public function className(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
}
