<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomTerm extends Model
{
    protected $table = 'room_terms';

    const EVEN_ODD = [
        'odd' => 'Ganjil',
        'even' => 'Genap'
    ];

    const EVEN_ODD_NUMERIC = [
        'odd' => 1,
        'even' => 2
    ];

    protected $fillable = ['room_id', 'term_id', 'even_odd', 'teacher_id', 'grade'];

    // odd_even field from the room_terms table.
    public function getEvenOddAttribute($value)
    {
        return self::EVEN_ODD[$value];
    }

    public function even_odd_numeric()
    {
        return self::EVEN_ODD_NUMERIC[$this->getOriginal('even_odd')];
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function room() {
        return $this->belongsTo('App\Room');
    }

    public function term() {
        return $this->belongsTo('App\Term');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'room_term_id');
    }

    // TEMPORARY WORKAROUND
    public function getUpdatedAtColumn()
    {
        if ($this->pivotParent) {
            return $this->pivotParent->getUpdatedAtColumn();
        }

        return static::UPDATED_AT;
    }

    public function getCreatedAtColumn()
    {
        if ($this->pivotParent) {
            return $this->pivotParent->getCreatedAtColumn();
        }

        return static::CREATED_AT;
    }
}
