<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Ticket as BaseTicket;

class Ticket extends BaseTicket
{
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'mama_id',
            'track_number',
            'status'
        ]);

        parent::__construct($attributes);
    }

    public function mama()
    {
        return $this->belongsTo(User::class, 'mama_id');
    }

}
