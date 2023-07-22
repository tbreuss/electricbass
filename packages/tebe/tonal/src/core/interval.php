<?php

namespace tebe\tonal\core\interval;
use function tebe\tonal\core\pitch\encode;
use tebe\tonal\core\pitch\Pitch;

// shorthand tonal notation (with quality after number)
const INTERVAL_TONAL_REGEX = "([-+]?\\d+)(d{1,4}|m|M|P|A{1,4})";
// standard shorthand notation (with quality before number)
const INTERVAL_SHORTHAND_REGEX = "(AA|A|P|M|m|d|dd)([-+]?\\d+)";
const REGEX = "/^" . INTERVAL_TONAL_REGEX . "|" . INTERVAL_SHORTHAND_REGEX . "$/";
const SIZES = [0, 2, 4, 5, 7, 9, 11];
const TYPES = "PMMPPMM";

function interval(string $interval): Interval|NoInterval
{
    return parse($interval);
}

function parse(string $interval): Interval|NoInterval
{
    $tokens = tokenizeInterval($interval);

    if ($tokens[0] === "") {
      return new NoInterval();
    }

    $num = +$tokens[0];
    $q = $tokens[1];
    $step = (abs($num) - 1) % 7;
    $t = TYPES[$step];
    if ($t === "M" && $q === "P") {
      return new NoInterval();
    }

    $type = $t === "M" ? "majorable" : "perfectable";
  
    $name = "" . $num . $q;
    $dir = $num < 0 ? -1 : 1;
    $simple = $num === 8 || $num === -8 ? $num : $dir * ($step + 1);
    $alt = qToAlt($type, $q);
    $oct = floor((abs($num) - 1) / 7);
    $semitones = $dir * (SIZES[$step] + $alt + 12 * $oct);
    $chroma = ((($dir * (SIZES[$step] + $alt)) % 12) + 12) % 12;
    $coord = encode(new Pitch($step, $alt, $oct, $dir));
    
    return new Interval(
        $alt,
        $chroma,
        $coord,
        $dir,
        false, // empty
        $name,
        $num,
        $oct,
        $q,
        $semitones,
        $simple,
        $step,
        $type,
    );
}

function tokenizeInterval(string $interval): array
{
    $status = preg_match(REGEX, $interval, $matches);
    
    if (empty($status)) {
        return ["", ""];
    }

    return !empty($matches[1]) 
        ? [$matches[1], $matches[2]] 
        : [$matches[4], $matches[3]];
}

function qToAlt(string $type, string $q): int 
{
    return ($q === "M" && $type === "majorable") || ($q === "P" && $type === "perfectable")
      ? 0
      : ($q === "m" && $type === "majorable"
      ? -1
      : (preg_match('/^A+$/', $q)
      ? strlen($q)
      : (preg_match('/^d+$/', $q)
      ? (-1 * ($type === "perfectable" ? strlen($q) : strlen($q) + 1))
      : 0)));
  }

class Interval
{
    public function __construct(
        public int $alt, 
        public int $chroma,
        public array $coord,
        public int $dir,
        public bool $empty,
        public string $name,
        public int $num,
        public int $oct,
        public string $q,
        public int $semitones,
        public int $simple,
        public int $step,
        public string $type
    ) {}
}

class NoInterval
{
    public function __construct(
        public string $acc = '',
        public bool $empty = true,
        public string $name = '',
        public string $pc = '',
    ) {}
}
