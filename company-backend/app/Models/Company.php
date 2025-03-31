<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
use HasFactory ;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected  $fillable =['name','email','phone','country','industry'];


    public function inquires()
    {
        return $this->hasMany(Inquiry::class);
    }


}
