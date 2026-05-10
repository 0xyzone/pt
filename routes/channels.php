<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('stats.{match_id}', function ($match, $match_id) {
    return (int) $match->id === (int) $match_id;
});
