<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('job.{id}', function ($user, $id) {
    return true; // or validate user ID
});
