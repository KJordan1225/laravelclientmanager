<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'client_code',
        'industry',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
        'hourly_rate',
        'credit_limit',
        'onboarded_at',
        'description',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'onboarded_at' => 'date',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class)->latest();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->latest();
    }
}
