<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','phone'
    ];
    public function imports(){
        return $this->hasMany(Import::class,'customer_id');
    }
    public function exports(){
        return $this->hasMany(Export::class,'customer_id');
    }
    public function format(){
        return [
            "name"=>$this->name,
            "phone"=>$this->phone
        ];
    }
    public function format2(){
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "phone"=>$this->phone,
            'imports' => $this->imports->map->format(),
            'exports' => $this->exports->map->format(),
        ];
    }
}
