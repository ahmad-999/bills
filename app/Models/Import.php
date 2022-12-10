<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'payment', 'details', "opration_date"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function format()
    {
        return [
            "payment" => $this->payment,
            "opration_date" => $this->opration_date,
            "created_at" => Carbon::parse($this->opration_date)->getTimestampMs(),
            "customer" => $this->customer == null ? null : $this->customer->format(),
            "details" => $this->details == null ? "" : $this->details
        ];
    }
}
