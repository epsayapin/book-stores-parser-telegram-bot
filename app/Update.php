<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    //
    protected $fillable = ['update_id', "handled"];

    public function setHandled()
    {
        $this->handled = true;
        $this->save();
    }
}
