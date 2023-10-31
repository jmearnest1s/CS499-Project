<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $guarded = [];

    public function delete()

    {

        // Delete All post in this group by this user
        Post::where('group_id', $this->group_id)->where('user_id', $this->user_id)->delete();

        return parent::delete();
    }

    public function group()

    {

        return $this->belongsTo(Group::class);

    }

    public function user()

    {

        return $this->belongsTo(User::class);

    }
}
