<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $guarded = ['id'];

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('id', 'desc');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($data){
            $data->messages()->delete();
        });
    }
}
