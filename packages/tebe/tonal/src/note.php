<?php

namespace tebe\tonal\note;

use tebe\tonal\core\note\Note;
use tebe\tonal\core\note\NoNote;
use function tebe\tonal\core\note\note AS coreNote;
use function tebe\tonal\midi\midiToNoteName;

function get(string $note): Note|NoNote
{
    return coreNote($note);
}

function name(string $note): string
{
    return get($note)->name;
}

function pitchClass(string $note): string
{
    return get($note)->pc;
}

function accidentals(string $note): string
{
    return get($note)->acc;
}

function octave(string $note): int
{
    return get($note)->oct;
}

function midi(string $note): int
{
    return get($note)->midi;
}

function frequency(string $note): float
{
    return get($note)->freq;
}

function chroma(string $note): int
{
    return get($note)->chroma;
}

function letter(string $note): string
{
    return get($note)->letter;
}

function step(string $note): int
{
    return get($note)->step;
}

function height(string $note): int
{
    return get($note)->height;
}

function enharmonic(string $noteName, ?string $destName = null): string
{
    $src = get($noteName);
    if ($src->empty) {
      return "";
    }

    // destination: use given or generate one
    $dest = get(
      $destName ??
        midiToNoteName($src->midi ?? $src->chroma, [
          "sharps" => $src->alt < 0,
          "pitchClass" => true,
        ])
    );

    // ensure destination is valid
    if ($dest->empty || $dest->chroma !== $src->chroma) {
      return "";
    }

    // if src has no octave, no need to calculate anything else
    if ($src->oct === null) {
      return $dest->pc;
    }
  
    // detect any octave overflow
    $srcChroma = $src->chroma - $src->alt;
    $destChroma = $dest->chroma - $dest->alt;
    $destOctOffset =
      $srcChroma > 11 || $destChroma < 0
        ? -1
        : ($srcChroma < 0 || $destChroma > 11
        ? +1
        : 0);
    // calculate the new octave
    $destOct = $src->oct + $destOctOffset;
    return $dest->pc . $destOct;
  }

function simplify(string $noteName): string
{
  $note = get($noteName);
  if ($note->empty) {
    return "";
  }
  return midiToNoteName($note->midi ?? $note->chroma, [
    "sharps" => $note->alt > 0,
    "pitchClass" => $note->midi === null,
  ]);
};
