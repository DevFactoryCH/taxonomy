<?php

namespace Devfactory\Taxonomy\Models;

use Illuminate\Database\Eloquent\Model;

class TermRelation extends Model
{
    protected $fillable = [
        'term_id',
        'vocabulary_id',
    ];

    protected $table = 'term_relations';

    public function relationable()
    {
        return $this->morphTo();
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
