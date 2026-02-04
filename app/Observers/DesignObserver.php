<?php

namespace App\Observers;

use App\Models\Design;
use App\Events\DesignCreated;
use Illuminate\Support\Facades\Log;

class DesignObserver
{
    public function created(Design $design): void
    {
        Log::info('Design created', ['design_id' => $design->id]);

        event(new DesignCreated($design));
    }
}
