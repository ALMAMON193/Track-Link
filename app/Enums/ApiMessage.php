<?php

namespace App\Enums;

enum ApiMessage: string
{
    case TruckerNotFound = 'Trucker not found.';
    case BrowseTrackerFetched = 'Browse Tracker details successfully fetched.';
    case HireRequestSent = 'Hire request successfully sent.';
    case RecordCreated = 'Record created successfully.';
    case RecordUpdated = 'Record updated successfully.';
    case RecordDeleted = 'Record deleted successfully.';
}
