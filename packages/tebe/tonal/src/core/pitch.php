<?php

namespace tebe\tonal\core\pitch;

// The number of fifths of [C, D, E, F, G, A, B]
const FIFTHS = [0, 2, 4, -1, 1, 3, 5];

// The number of octaves it span each step
define(__NAMESPACE__ . '\STEPS_TO_OCTS', array_map(fn(int $fifths) => floor(($fifths * 7) / 12), FIFTHS));

// We need to get the steps from fifths
// Fifths for CDEFGAB are [ 0, 2, 4, -1, 1, 3, 5 ]
// We add 1 to fifths to avoid negative numbers, so:
// for ["F", "C", "G", "D", "A", "E", "B"] we have:
const FIFTHS_TO_STEPS = [3, 0, 4, 1, 5, 2, 6];

function isPitch($pitch): bool
{
    return $pitch !== null && $pitch instanceof Pitch;
}

function encode(Pitch $pitch): array // pitch coordinates
{
    $dir = $pitch->dir ?? 1;

    $f = FIFTHS[$pitch->step] + 7 * $pitch->alt;
    if ($pitch->oct === null) {
      return [$dir * $f];
    }
    $o = $pitch->oct - STEPS_TO_OCTS[$pitch->step] - 4 * $pitch->alt;
    return [$dir * $f, $dir * $o];
}

function decode(array $coord): Pitch
{
    [$f] = $coord;
    $o = $coord[1] ?? null;
    $dir = $coord[2] ?? null;
    $step = FIFTHS_TO_STEPS[unaltered($f)];
    $alt = floor(($f + 1) / 7);
    if ($o === null) {
        return new Pitch($step, $alt, $dir);
    }
    $oct = $o + 4 * $alt + STEPS_TO_OCTS[$step];
    return new Pitch($step, $alt, $oct, $dir);
}

// Return the number of fifths as if it were unaltered
function unaltered(int $f): int 
{
    $i = ($f + 1) % 7;
    return $i < 0 ? 7 + $i : $i;
}

class Pitch {
    public function __construct(
        public int $step,
        public int $alt,
        public ?int $oct = null, // undefined for pitch classes
        public ?int $dir = null, // undefined for notes
    ) {}    
}
