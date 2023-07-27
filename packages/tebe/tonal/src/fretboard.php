<?php

namespace tebe\tonal\fretboard;

use Exception;
use function tebe\tonal\note\get;
use function tebe\tonal\midi\midiToNoteName;
use function tebe\tonal\note\enharmonic;

const FRET_FROM = 0;
const FRET_TO = 24;
const POSITION_WIDTH = 4;
const EXPAND_NO = 0;
const EXPAND_HIGHER = 1;
const EXPAND_LOWER = 2;

function getNote(Tuning $tuning, string $fingering): array
{
    [$fret, $string] = explode('/', $fingering);

    if ($fret < FRET_FROM || $fret > FRET_TO) {
        return [];
    }

    $stringIndex = $string - 1;
    
    if (!isset($tuning->strings[$stringIndex])) {
        return [];
    }

    $emptyFretNote = $tuning->strings[$stringIndex][0];
    $midi = get($emptyFretNote)->midi + $fret;
    
    $note = midiToNoteName($midi);    
    $enharmonic = enharmonic($note);

    return $note === $enharmonic ? [$note] : [$note, $enharmonic];
}

/**
 * Find notes from lowest to highest
 */
function findNotes(Tuning $tuning, array $notes, ?int $fretFrom = null, ?int $fretTo = null, ?int $stringFrom = null, ?int $stringTo = null): array
{
    $stringCount = count($tuning->strings);
    $stringFrom = is_null($stringFrom) ? 1 : bound(abs($stringFrom), 1, $stringCount);
    $stringTo = is_null($stringTo) ? count($tuning->strings) : bound(abs($stringTo), 1, $stringCount);
    $fretFrom = is_null($fretFrom) ? 0 : bound(abs($fretFrom), 0, FRET_TO);
    $fretTo = is_null($fretTo) ? FRET_TO : bound(abs($fretTo), 0, FRET_TO);

    if ($stringFrom > $stringTo || $fretFrom > $fretTo) {
        return [];
    }

    $labels = array_map(fn($note) => is_array($note) ? $note[1] : $note, $notes);
    $notes = array_map(fn($note) => is_array($note) ? $note[0] : $note, $notes);

    $foundNotes = [];

    foreach (notes($tuning) as $fretString => $notesPerFretString) {
        [$fret, $string] = explode('/', $fretString);
        foreach ($notesPerFretString as $notePerFretString) {
            if ($fret < $fretFrom || $fret > $fretTo) {
                continue;
            }
            if ($string < $stringFrom || $string > $stringTo) {
                continue;
            }
            if (!in_array($notePerFretString, $notes)) {
                continue;
            }
            $foundKey = array_search($notePerFretString, $notes);
            if ($foundKey === false) {
                continue;
            }
            $foundNotes[] = [
                "coord" => $fretString,
                "note" => $notes[$foundKey],
                "pc" => preg_replace('/[0-9]/', '', $notes[$foundKey]),
                "label" => $labels[$foundKey],
                "abs" => coordToAbs($stringCount, $fretString)
            ];
        }
    }

    return $foundNotes;
}

function findLowestNote(Tuning $tuning, string $note): string
{
    foreach (notes($tuning) as $fretString => $notesPerFretString) {
        if (in_array($note, $notesPerFretString)) {
            return $fretString;
        }
    }
    return '';
}

function findHighestNote(Tuning $tuning, string $note): string
{
    foreach (array_reverse(notes($tuning)) as $fretString => $notesPerFretString) {
        if (in_array($note, $notesPerFretString)) {
            return $fretString;
        }
    }
}

function midi(Tuning $tuning, int $fretTo = FRET_TO): array
{
    $notes = [];
    foreach ($tuning->strings as $stringIndex => [$note]) {
        $stringNumber = $stringIndex + 1;
        $midi = get($note)->midi;
        foreach (range(0, $fretTo) as $fret) {
            $notes[$fret . '/' . $stringNumber] = $midi + $fret;
        }
    }
    return $notes;
}

function test_cartesian($input) {
    $result = array(array());

    foreach ($input as $key => $values) {
        $append = array();

        foreach($result as $product) {
            foreach($values as $item) {
                $product[$key] = $item;
                $append[] = $product;
            }
        }

        $result = $append;
    }

    return $result;
}

function reverse_cartesian($cartesion): array
{
    $reversed = [];
    foreach ($cartesion as $y => $xValues) {
        foreach ($xValues as $x => $v) {
            $reversed[$x][$y] = $v;
        }
    }
    return $reversed;
}

function print_array(array $cartesion): void
{
    echo '<table>';
    foreach ($cartesion as $y => $xValues) {
        echo '<tr>';
        foreach ($xValues as $x => $v) {
            echo '<td style="border:1px solid black">', $v, '</td>';
        }
        echo '</tr>';
    }
    echo '</table border="0">';    
}

function toAbsFingerings(array $fingerings): array
{
    $absFingerings = [];
    foreach ($fingerings as $fingering) {
        $abs = $fingering['abs'];
        if (!isset($absFingerings[$abs])) {
            $absFingerings[$abs] = [];
        }
        $absFingerings[$abs][] = $fingering;
    }
    return array_values($absFingerings);
}

function filterFingeringsForPosition(array $fingerings, int $fretFrom, int $fretTo): array
{
    $filtered = [];
    foreach ($fingerings as $fingering) {
        [$fret] = explode('/', $fingering['coord']);
        if ($fret >= $fretFrom && $fret <= $fretTo) {
            $filtered[] = $fingering;
        }
    }
    return $filtered;
}

function findLowestAndHighest(array $fingerings, string $lowestNote, string $highestNote): array
{
    $firstRoot = null;
    $lastRoot = null;
    foreach ($fingerings as $index => $fingering) {
        if ($firstRoot === null && ($fingering['note'] === $lowestNote || $fingering['pc'] === $lowestNote)) {
            $firstRoot = $index;
        }
        if ($fingering['note'] === $highestNote || $fingering['pc'] === $highestNote) {
            $lastRoot = $index;
        }
    }
    return [$firstRoot, $lastRoot];
}

function findRootNotes(array $fingerings, string $root): array
{
    $indexes = [];
    foreach ($fingerings as $index => $fingeringsPerIndex) {
        $notes = array_column($fingeringsPerIndex, 'note');
        $pcs = array_column($fingeringsPerIndex, 'pc');
        if (in_array($root, $notes) || in_array($root, $pcs)) {
            $indexes[] = $index;
        }
    }
    return $indexes;
}

function recognizePattern(array $fingerings, array $notes): array
{
    $indexes = findRootNotes($fingerings, reset($notes)[0]);
    $length = count($fingerings);

    $slices = [];
    foreach ($indexes as $startIndex) {
        $slices[] = array_slice($fingerings, $startIndex, $length);
    }

    // add semitones to notes
    foreach ($notes as $index => $note) {
        $notes[$index][] = \tebe\tonal\core\interval\interval($note[1])->semitones;
    }

    $noteCount = count($notes);
    
    $results = [];
    foreach ($slices as $slice) {

        $candidates = [];
        $absDistance = 0;

        foreach ($notes as $noteIndex => [$note, , $relDistance]) {
            if ($noteIndex === 0) {
                $absDistance = $slice[0][0]['abs'];
            }
            foreach ($slice as $s) {
                $sliceNotes = array_column($s, 'note');
                $slicePcs = array_column($s, 'pc');
                if (in_array($note, $sliceNotes) || in_array($note, $slicePcs)) {
                    if (($absDistance + $relDistance) == $s[0]['abs']) {
                        $candidates[] = $s;
                    }
                }
            }
        }

        if ($noteCount === count($candidates)) {
            $results[] = $candidates;
        }
    }

    return $results;
}

function get_all_possibilities(array $notes, array $fingerings, int $position, int $expand = EXPAND_NO)
{
    $lowestNote = reset($notes)[0];
    $highestNote = end($notes)[0];
    
    // determine frets from/to for position
    [$fretFrom, $fretTo] = positionBound($position, $expand);

    // filter fingerings for position
    $filteredFingerings = filterFingeringsForPosition($fingerings, $fretFrom, $fretTo);

    // get first and last note of...
    [$firstRoot, $lastRoot] = findLowestAndHighest($filteredFingerings, $lowestNote, $highestNote);

    // remove all notes below lowest and highest valid note
    if (!is_null($firstRoot) && !is_null($lastRoot)) {
        $filteredFingerings = array_slice($filteredFingerings, $firstRoot, $lastRoot - $firstRoot + 1);
    }

    // calculate absolute coordinates
    $absFingerings = toAbsFingerings($filteredFingerings);
    #echo"<pre>";print_r($absFingerings);echo"</pre>";

    // split fingerings if we have multiple forms within a position
    $patternResults = recognizePattern($absFingerings, $notes);
    
    $posibilities = [];

    // go through all splitted fingerings
    foreach ($patternResults as $splittedFingeringsInOneOctave) {

        // create cartesian product
        $results = (test_cartesian($splittedFingeringsInOneOctave));
        foreach ($results as $result) {
            $posibilities[] = $result;
        }
    }

    return $posibilities;
}

/**
 * All notes from lowest to highest
 */
function notes(Tuning $tuning, int $fretTo = FRET_TO): array
{
    $notes = [];    
    $stringCount = count($tuning->strings);
    foreach (array_reverse($tuning->strings) as $stringIndex => $string) {
        $stringNumber = $stringCount - $stringIndex;
        [$note] = $string;
        $midi = get($note)->midi;
        foreach (range(0, $fretTo) as $fret) {
            $key = $fret . '/' . $stringNumber;
            $notes[$key] = [];
            $noteName = midiToNoteName($midi + $fret);
            $noteClass = get($noteName);
            $notes[$key][] = $noteClass->pc;
            $notes[$key][] = $noteName;
            $enharmonic = enharmonic($noteName);
            if ($enharmonic <> $noteName) {
                $notes[$key][] = get($enharmonic)->pc;
                $notes[$key][] = $enharmonic;
            }
            $specials = [['B', 'Cb', 1], ['C', 'B#', -1], ['E', 'Fb', 0], ['F', 'E#', 0]];
            foreach ($specials as [$sLetter, $sFix, $sOct]) {
                if ($noteClass->letter === $sLetter) {
                    $notes[$key][] = $sFix;
                    $notes[$key][] = $sFix . ($noteClass->oct + $sOct);
                }
            }
        }
    }
    return $notes;
}

function bound(int $x, int $min, int $max): int
{
     return min(max($x, $min), $max);
}

function positionBound(int $position, int $expand = EXPAND_NO): array
{
    $expand = bound($expand, 0, 3);
    $position = bound($position, 1, 21);
    $fretFrom = $position === 1 ? 0 : $position;
    $fretTo = $position + POSITION_WIDTH - 1;
    switch($expand) {
        case EXPAND_NO:
            return [$fretFrom, $fretTo];
        case EXPAND_LOWER:
            return [max(0, $fretFrom - 1), $fretTo];
        case EXPAND_HIGHER:
            return [$fretFrom, $fretTo + 1];
        case EXPAND_LOWER|EXPAND_HIGHER: 
            return [max(0, $fretFrom - 1), $fretTo + 1];
    };
}

function coordToAbs(int $stringCount, string $coord): int
{
    [$fret, $string] = explode('/', $coord);
    return ($stringCount - $string) * 5 + $fret;
}

function tuning(string $name, array $strings): Tuning
{
    return new Tuning($name, $strings);
}

class Tuning 
{
    public function __construct(
        public string $tuning,
        public array $strings
    ) {}
}
