<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Ticket as BaseTicket;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ticket extends BaseTicket
{
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'mama_id',
            'track_number',
        ]);

        parent::__construct($attributes);
    }

    public function ticketable(): MorphTo
    {
        return $this->morphTo();
    }
}
