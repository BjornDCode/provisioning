<?php 

namespace App\Enums;

class PipelineStatus extends Enum
{
    const PENDING = 'pending'; 
    const RUNNING = 'running'; 
    const FAILED = 'failed'; 
    const SUCCESSFUL = 'successful'; 
    const CANCELLED = 'cancelled'; 
}
