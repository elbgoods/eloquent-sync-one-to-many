<?php

namespace Elbgoods\SyncOneToMany\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
