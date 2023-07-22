<?php

namespace tebe\tonal\core\distance;

use function tebe\tonal\core\note\coordToNote;
use function tebe\tonal\core\note\note AS asNote;
use function tebe\tonal\core\interval\interval AS asInterval;

function transpose(string $noteName, string $intervalName): string
{
    $note = asNote($noteName);

    $intervalCoord = asInterval($intervalName)->coord;

    if ($note->empty || !is_array($intervalCoord) || count($intervalCoord) < 2) {
      return "";
    }

    $noteCoord = $note->coord;

    $tr =
      count($noteCoord) === 1
        ? [$noteCoord[0] + $intervalCoord[0]]
        : [$noteCoord[0] + $intervalCoord[0], $noteCoord[1] + $intervalCoord[1]];

    
    $note = coordToNote($tr);

    return coordToNote($tr)->name;
}
