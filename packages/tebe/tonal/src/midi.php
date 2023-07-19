<?php

namespace tebe\tonal\midi;

const SHARPS = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];
const FLATS = ["C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "Bb", "B"];

function midiToNoteName(?int $midi = null, array $options = []): string
{
  if (is_null($midi) || $midi === PHP_INT_MIN || $midi === PHP_INT_MAX) {
    return "";
  }
  $pcs = isset($options["sharps"]) && $options["sharps"] === true ? SHARPS : FLATS;
  $pc = $pcs[$midi % 12];
  if (isset($options["pitchClass"]) && $options["pitchClass"] === true) {
    return $pc;
  }
  $o = floor($midi / 12) - 1;
  return $pc . $o;
}
