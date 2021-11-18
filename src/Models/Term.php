<?php

namespace Devfactory\Taxonomy\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = [
        'name',
        'vocabulary_id',
        'parent',
        'weight',
    ];

    public static $rules = [
        'name' => 'required'
    ];

    public function termRelation()
    {
        return $this->morphMany(TermRelation::class, 'relationable');
    }

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }

    public function childrens()
    {
        return $this->hasMany(Term::class, 'parent', 'id')
            ->orderBy('weight', 'ASC');
    }

    public function parentTerm()
    {
        return $this->hasOne(Term::class, 'id', 'parent');
    }
}
