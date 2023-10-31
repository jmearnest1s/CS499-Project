<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    protected $guarded = [];

    public function delete()

    {

        $this->members()->delete();
        $this->notifies()->delete();
        @unlink('assets/front/img/' . $this->cover);

        return parent::delete();

    }

    public function members()

    {

        return $this->hasMany(GroupMember::class);

    }
	
	public function topics()

    {

        //return $this->hasMany(Interest::class);
		return $this->belongsToMany('App\Interest')->withPivot('interest_id');

    }

    public function memberCount()

    {

        return GroupMember::where('group_id', $this->id)->where('status', 1)->count();

    }

    public function requestCount()

    {

        return GroupMember::where('group_id', $this->id)->where('status', 4)->count();

    }

    public function isCreator()

    {

        $auth = Auth::user();

        $creator = GroupMember::where('group_id', $this->id)->where('role', 1)->where('user_id', $auth->id)->first();

        if ($creator) return $creator;

        return false;

    }

    public function creator()

    {

        $creator = GroupMember::where('group_id', $this->id)->where('role', 1)->first();

        if ($creator) return $creator->user;

        return false;

    }

    public function posts()

    {

        return $this->hasMany(Post::class);

    }

    public function isAdmin()

    {

        $auth = Auth::user();

        $admin = GroupMember::where('group_id', $this->id)->where('role', 2)->where('user_id', $auth->id)->where('status', 1)->first();

        if ($admin) return $admin;

        return false;

    }

    public function isModerator()

    {

        $auth = Auth::user();

        $moderator = GroupMember::where('group_id', $this->id)->where('role', 3)->where('user_id', $auth->id)->where('status', 1)->first();

        if ($moderator) return $moderator;

        return false;

    }

    public function isMember()

    {

        $auth = Auth::user();

        $member = GroupMember::where('group_id', $this->id)->where('role', 4)->where('user_id', $auth->id)->where('status', 1)->first();

        if ($member) return $member;

        return false;

    }

    public function isInvited()

    {

        $auth = Auth::user();

        $member = GroupMember::where('group_id', $this->id)->where('status', 3)->where('user_id', $auth->id)->first();

        if ($member) return $member;

        return false;

    }

    public function isPending()

    {

        $auth = Auth::user();

        $member = GroupMember::where('group_id', $this->id)->where('status', 4)->where('user_id', $auth->id)->first();

        if ($member) return $member;

        return false;

    }

    public function pinned()

    {

        return $this->belongsTo(Post::class, 'pin');

    }

    public function notifies()

    {

        return $this->hasMany(Notify::class);

    }
}
