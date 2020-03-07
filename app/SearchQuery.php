<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchQuery extends Model
{
    //
    protected $fillable = ['text', 'fullname', 'username', 'from_id'];
}
