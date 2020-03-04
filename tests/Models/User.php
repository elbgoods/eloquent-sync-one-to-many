<?php

namespace Elbgoods\SyncOneToMany\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
