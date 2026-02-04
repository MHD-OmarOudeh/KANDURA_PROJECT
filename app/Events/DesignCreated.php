<?php

namespace App\Events;

use App\Models\Design;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DesignCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Design $design)
    {
    }
}
