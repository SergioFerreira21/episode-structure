<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\CanDuplicate;

class Episodes extends Model
{
    use HasUuids, HasFactory, CanDuplicate;

    public $incrementing = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
        ];
    }

    // Relationships
    public function parts()
    {
        return $this->hasMany('App\Models\Parts', 'episode_id');
    }

    // This is the method that is called by the CanDuplicate trait
    // Returns the downstream relationships of the model that should be duplicated
    public function getDownstreamRelations()
    {
        return ['parts'];
    }
}
