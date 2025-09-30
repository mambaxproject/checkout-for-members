<?php

namespace App\Helpers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Illuminate\Support\Facades\DB;
use Monolog\Level;

class DatabaseHandler extends AbstractProcessingHandler
{
    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        DB::table('logs')->insert([
            'level' => $record->level->getName(), 
            'message' => $record->message,
            'context' => json_encode($record->context),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
