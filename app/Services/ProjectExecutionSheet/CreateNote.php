<?php

namespace App\Services\ProjectExecutionSheet;

use App\Models\ProjectSheetNote;

/**
 * Class CreateNote.
 */
class CreateNote
{
    public function handle($data)
    {
        return ProjectSheetNote::create($data);
    }
}
