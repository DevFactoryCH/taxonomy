<?php

namespace Devfactory\Taxonomy\Models;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $table = 'vocabularies';

    public $rules = [
        'name' => 'required'
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function relations()
    {
        return $this->hasMany(TermRelation::class);
    }
}
