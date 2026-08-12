<?php

namespace App\Models;

use App\Data\ContactData;
use App\Enums\CompanySize;
use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'size' => '2-10',
    ];

    protected function casts(): array
    {
        return [
            'contact' => ContactData::class,
            'size' => CompanySize::class,
        ];
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function hasOpenedJobPositions(): bool
    {
        return $this->jobs()->where('status', JobStatus::OPEN)->exists();
    }
}
