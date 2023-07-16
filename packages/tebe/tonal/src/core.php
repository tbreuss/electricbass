<?php

namespace tebe\tonal\core;

use tebe\tonal\core\interval\Interval;
use tebe\tonal\core\interval\NoInterval;
use tebe\tonal\core\note\Note;
use tebe\tonal\core\note\NoNote;
use function tebe\tonal\core\note\note AS coreNote;
use function tebe\tonal\core\interval\interval AS coreInterval;

function note(string $note): Note|NoNote
{
    return coreNote($note);
}

function interval(string $interval): Interval|NoInterval
{
    return coreInterval($interval);
}
