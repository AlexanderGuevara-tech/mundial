<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'group_name',
        'team_home',
        'team_away',
        'flag_home',
        'flag_away',
        'match_date',
        'result_home',
        'result_away',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'date:Y-m-d',
            'result_home' => 'integer',
            'result_away' => 'integer',
        ];
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function hasResult(): bool
    {
        return $this->result_home !== null && $this->result_away !== null;
    }
}
