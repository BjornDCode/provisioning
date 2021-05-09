<?php 

namespace App\Enums;

class PipelineStatus extends Enum
{
    const PENDING = 'pending'; 
    const FAILED = 'failed'; 
    const SUCCESSFUL = 'successful'; 
    const CANCELLED = 'cancelled'; 
}
