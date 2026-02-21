<?php

namespace App\Models;

use App\Observers\ReferenceObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([ReferenceObserver::class])]
class ReferenceRecord extends Model
{
    //
}
