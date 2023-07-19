<?php

namespace tebe\tonal\core\note;

use function tebe\tonal\core\pitch\encode;
use function tebe\tonal\core\pitch\decode;
use tebe\tonal\core\pitch\Pitch;

const LETTERS = 'CDEFGAB';
const REGEX = '/^([a-gA-G]?)(#{1,}|b{1,}|x{1,}|)(-?\d*)\s*(.*)$/';
const SEMI = [0, 2, 4, 5, 7, 9, 11];

function note(Pitch|string $note): Note|NoNote
{
    if (is_string($note)) {
        return parse($note);
    }
    if ($note instanceof Pitch) {
        return note(pitchName($note));
    }    
    return new NoNote();
}

function parse(string $note): Note|NoNote
{
    $tokens = tokenizeNote($note);

    if ($tokens[0] === "" || $tokens[3] !== "") {
        return new NoNote();
    }

    $letter = $tokens[0];
    $acc = $tokens[1];
    $octStr = $tokens[2];
    
    $step = (ord(substr($letter, 0, 1)) + 3) % 7;
    $alt = accToAlt($acc);
    $oct = strlen($octStr) ? abs($octStr) : null;
    $coord = encode(new Pitch($step, $alt, $oct));
    $name = $letter . $acc . $octStr;
    $pc = $letter . $acc;
    $chroma = (SEMI[$step] + $alt + 120) % 12;
    $height = $oct === null
        ? mod(SEMI[$step] + $alt, 12) - 12 * 99
        : SEMI[$step] + $alt + 12 * ($oct + 1);
    $midi = $height >= 0 && $height <= 127 ? $height : null;
    $freq = $oct === null ? null : pow(2, ($height - 69) / 12) * 440;

    return new Note(
        $acc,
        $alt,
        $chroma,
        $coord,
        false, // empty
        $freq,
        $height,
        $letter,
        $midi,
        $name,
        $oct,
        $pc,
        $step,
    );
}

function tokenizeNote(string $note): array
{
    preg_match(REGEX, $note, $matches);
    return [
        strtoupper($matches[1] ?? ''),
        str_replace('x', '##', $matches[2] ?? ''), // double sharp accidentials
        $matches[3] ?? '', 
        $matches[4] ?? ''
    ];
}

function coordToNote(array $noteCoord): Note|NoNote
{
    $note = decode($noteCoord);
    return note($note);
}

function accToAlt(string $acc): int
{
    $strlen = strlen($acc);
    return substr($acc, 0, 1) === 'b' ? -$strlen : $strlen;
}

function mod(int $n, int $m): int
{
    return (($n % $m) + $m) % $m;
}

function stepToLetter(int $step): string
{
    return substr(LETTERS, $step, 1);
}

function altToAcc(int $alt): string
{
  return $alt < 0 ? str_repeat("b", -$alt) : str_repeat("#", $alt);
}

function pitchName(Pitch $pitch): string
{
    $letter = stepToLetter($pitch->step);
    if (!$letter) {
      return "";
    }

    $pc = $letter . altToAcc($pitch->alt);
    return $pitch->oct || $pitch->oct === 0 ? $pc . $pitch->oct : $pc;
}

class Note
{
    public function __construct(
        public string $acc, // string "#"
        public int $alt, // int 1
        public int $chroma, // int 10
        public array $coord, // array [10, -1]
        public bool $empty, // false
        public ?float $freq, // float 466.1634123424
        public int $height, // int 70
        public string $letter, // string "A"
        public ?int $midi, // int 70
        public string $name, // string "A#4"
        public ?int $oct, //int 4
        public string $pc, // string "A#"
        public int $step, // int 5
    ) {}
}

class NoNote
{
    public function __construct(
        public string $acc = '',
        public bool $empty = true,
        public string $name = '',
        public string $pc = '',
    ) {}
}
