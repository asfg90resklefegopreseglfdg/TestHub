<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'tag'
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_tags');
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}
