<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function hasOpenedJobPositions(): bool
    {
        return $this->jobs()->where('status', JobStatus::OPEN)->exists();
    }
}
