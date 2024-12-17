<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Specify the fillable fields
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address', 
        'city', 'state', 'postal_code', 'country', 'status'
    ];

    /**
     * Get the full name of the customer.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
