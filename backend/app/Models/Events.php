<?php


namespace App\Models;


use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed        date_from
 * @property mixed        date_to
 * @property mixed        name
 * @property mixed        event
 * @property false|string days
 */
class Events extends Model
{
    
    use UsesUuid;
    
    
    protected $table = 'events';
    
    protected $fillable
        = [
            'date_from',
            'date_to',
            'days',
            'event',
        ];
    
}
