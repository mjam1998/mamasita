<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Message as BaseMessage;

class Message extends BaseMessage
{
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'ticket_id',
            'user_id',
            'message',
            'attachment',
        ]);

        parent::__construct($attributes);
    }

}
