<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmSubject extends Model
{
    public function subjectBook(){
        return $this->belongsTo('App\Book', 'book', 'id');
    }
}