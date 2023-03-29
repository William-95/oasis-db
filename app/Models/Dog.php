<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table='user';
    protected $fillable=['id_dog','name','sex','race','size','date_birth','microchip','date_entry','img','structure','contacts'];
}
