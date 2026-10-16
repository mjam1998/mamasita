<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Ticket as BaseTicket;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ticket extends BaseTicket
{
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'ticketable_id',
            'ticketable_type',
        ]);

        parent::__construct($attributes);
    }

    public function ticketable(): MorphTo
    {
        return $this->morphTo();
    }
}
