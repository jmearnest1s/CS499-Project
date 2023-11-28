<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function user()

    {

        return $this->belongsTo(User::class);

    }
}
