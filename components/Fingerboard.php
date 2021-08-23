<?php

namespace app\components;

final class Fingerboard {

    // Leersaiten
    var $openStrings;

    var $useOpenStrings=false;

    // Saiten
    var $strings;

    // Lagenbreite
    var $posWidth = 5;

    var $numberOfStrings;
    var $root;
    var $chord;
    var $position;

    var $results;
    var $resNbr;
    var $toneNames;
    var $showMainTitle = true; // 2012-11-30 Neu

    public function __construct($numberOfStrings, $root, $chord, $position) {

        $this->openStrings = $this->getOpenStringDefinitions();
        $this->strings     = $this->getStringDefinitions();

        $this->numberOfStrings = $numberOfStrings;
        $this->root            = $root;
        $this->chord           = $chord;
        $this->position        = $position;

        $this->toneNames = $this->calculateIntervals($this->root, $this->chord);

        $test2 = $this->getAllPossibilities($this->root, $this->toneNames, $this->position, $this->numberOfStrings);
        $test2 = $this->deleteNotesBelowRoot($test2);
        $test2 = $this->filterOpenStrings($test2);

        $test3 = $this->filter($test2, $this->chord);
        $this->results = array_reverse($test3);
    }

    /**
     * Reduziert die Anzahl Resultate auf die angegebene Anzahl.
     *
     * @param integer $numberOfResults
     * @return integer
     * @since 2012-11-30
     */
    public function reduceTo($numberOfResults=1)
    {
        $reduced = 0;
        if($numberOfResults<1) {
            return $reduced;
        }
        foreach($this->results AS $i=>$unused) {
            if($i>=$numberOfResults) {
                unset($this->results[$i]);
                $reduced++;
            }
        }
        return $reduced;
    }

    /**
     * Leersaiten entfernen, aber nur
     * - alle Töne ausser Grundton
     * - nur in Position grösser 1
     *
     * @param array $data
     * @return array
     */
    public function filterOpenStrings($data)
    {
        if($this->position>1) {
            foreach($data AS $resultIndex=>$result){
                if($resultIndex>0) {
                    foreach($result AS $noteIndex=>$note) {
                        if($note['fret'] == 0) {
                            unset($data[$resultIndex][$noteIndex]);
                    }
                }
              }
            }
            foreach($data AS $resultIndex=>$result){
              $data[$resultIndex] = array_values($result);
            }
        }
      return $data;
    }
    public function deleteNotesBelowRoot($data) {

        $rootIntValue = 1000;
        foreach($data[0] AS $note) {
            if($note['numbered'] < $rootIntValue) {
                $rootIntValue = $note['numbered'];
            }
        }

        foreach($data AS $key1=>$notes) {
            foreach($notes AS $key2=>$note) {
                if($note['numbered'] < $rootIntValue) {
                    unset($data[$key1][$key2]);
                }
            }
        }

        // Abbrechen, falls eine Note fehlt
        foreach($data AS $d) {
            if(empty($d)) {
                return array();
            }
        }

        return $data;
    }

    public static function getRoots()
    {
        return array('C','C#','Db','D','D#','Eb','E','F','F#','Gb','G','G#','Ab','A','A#','Bb','B');
    }

    public static function getPositions()
    {
        return range(1, 8);
    }

    function getAbsoluteRoot($filtered=false)
    {
        $root = '';
        if(isset($this->results[0][0]['absolut'])) {
            $root = $this->results[0][0]['absolut'];
        }
        if($filtered) {
            $root = strtolower($root);
            $root = str_replace(array('#'), array('is'), $root);
        }
        return $root;
    }

    function getMidiFilename()
    {

    }

    function toRTTTL()
    {
        $enharmonics = array(
            'Db' => 'C#',
            'Eb' => 'D#',
            'Fb' => 'E',
            'Gb' => 'F#',
            'Ab' => 'G#',
            'Bb' => 'A#',
            'Cb' => 'B'
        );
        if(!empty($this->results[0])) {
            $notes = array();
            foreach($this->results[0] AS $tone) {
                $tmpNote = str_replace(array_keys($enharmonics), array_values($enharmonics), $tone['absolut']);
                $octave = (int)preg_replace('/[A-Za-z#]/', '', $tmpNote);
                $note = (string)preg_replace('/[0-9]/', '', $tmpNote);
                $notes[] = strtolower($note.($octave+1));
            }
            $reverse = array_reverse($notes);
            array_shift($reverse);
            $notes = array_merge($notes, $reverse);
            $notes[] = 'p';
            $notes[] = 'p';
            $rtttl = array();
            $rtttl[] = 'fingerboard';
            $rtttl[] = 'd=4,o=4,b=100';
            $rtttl[] = implode(',',$notes);
            return implode(':',$rtttl);
        }
        return '';
    }

    function getFingerboard($imagePath) {
        $out='';
        foreach($this->results AS $resNbr=>$val) {

            if($this->numberOfStrings == '4'){
              unset($this->strings['C']);
              unset($this->strings['B']);
            }

            if($this->numberOfStrings == '5'){
              unset($this->strings['C']);
            }

            $this->resNbr = $resNbr;
            if(empty($this->resNbr)) {
                $this->resNbr = 0;
            }

            $tpl = new Template(dirname(__FILE__));
            $tpl->set_file('content', 'tpl/chords.html' );
            $tpl->set_block('content', 'LAGEN2', 'LAGEN2_BLOCK' );
            $tpl->set_block('content', 'STRING_START2', 'STRING_START2_BLOCK' );
            $tpl->set_block('content', 'STRING_END2', 'STRING_END2_BLOCK' );
            $tpl->set_block('content', 'COL2', 'COL2_BLOCK' );
            $tpl->set_block('content', 'ROW2', 'ROW2_BLOCK' );

            // Link-Resultate
            $tpl->set_block('content', 'ACTIVE_INTERVAL_RESULTS', 'ACTIVE_INTERVAL_RESULTS_BLOCK' );
            $tpl->set_block('content', 'MORE_INTERVAL_RESULTS', 'MORE_INTERVAL_RESULTS_BLOCK' );
            $tpl->set_block('content', 'RESULT_TITLE', 'RESULT_TITLE_BLOCK' );
            $tpl->set_block('content', 'SHOW_RESULTS', 'SHOW_RESULTS_BLOCK' );
            $tpl->set_block('content', 'MAIN_TITLE', 'MAIN_TITLE_BLOCK' );

            $firstString = 1;
            $lastString  = $this->numberOfStrings;
            $index = 1;

            // T�ne ausgeben
            foreach($this->strings AS $stringName=>$string){

                  $showNote = false;
                  foreach($this->results[$this->resNbr] AS $resultKey=>$resultVal){
                    // Saitenname
                    if(($resultVal['string'] == $stringName) &&
                       ($resultVal['fret'] == 0)){
                      $showNote = true;
                    }

                    if($showNote){
                      $tpl->set_var(array('CLASS_TONES' => 'leersaite' ));
                    }
                    else {
                      $tpl->set_var(array('CLASS_TONES' => 'fingerboard' ));
                    }
                  }
                  $tpl->set_var(array('STRING_NAME' => $stringName ));
                  $tpl->parse('ROW2_BLOCK', 'STRING_START2', true );

                  // Saiten darstellen (abh�ngig von der Saitenanzahl)
                  $fretIndex = 1;
                  foreach($string AS $fret){
                    if($this->position != ''){
                      /* @phpstan-ignore-next-line */
                      if(($fretIndex >= $this->position) && ($fretIndex < ($this->position+$this->posWidth))){
                        $tpl->set_var(array('CLASS_POSITION' => 'bgPosition' ));
                      } else {
                        $tpl->set_var(array('CLASS_POSITION' => '' ));
                      }
                    }
                    Switch($index){
                      case $firstString:
                        $height = 16;
                        if($fretIndex == 1){
                          $tpl->set_var(array(
                              'NODES' => $imagePath.'sca_xd_null.gif',
                              'NODES_WIDTH' => 9,
                              'NODES_HEIGHT' => $height
                          ));
                        } else {
                          $tpl->set_var(array(
                              'NODES' => $imagePath.'sca_xd.gif',
                              'NODES_WIDTH' => 12,
                              'NODES_HEIGHT' => $height
                          ));
                        }

                        // T�ne darstellen
                        $showNote = false;
                        foreach($this->results[$this->resNbr] AS $resultKey=>$resultVal){
                          // Ton oder kein Ton?
                          if(($resultVal['string'] == $stringName) &&
                             ($resultVal['fret'] == $fretIndex) ){
                            $showNote = true;
                          }
                        }

                        // Ton oder kein Ton?
                        if($showNote){
                          $tpl->set_var(array(
                              'PICTURE' => $imagePath.'sca_tonexd.gif',
                              'PICTURE_WIDTH' => 33,
                              'PICTURE_HEIGHT' => $height
                          ));
                        } else {
                          $tpl->set_var(array(
                              'PICTURE' => $imagePath.'sca_lined.gif',
                              'PICTURE_WIDTH' => 33,
                              'PICTURE_HEIGHT' => $height
                          ));
                        }

                      break;

                      case $lastString:
                        $height = 16;
                        if($fretIndex == 1){
                          $tpl->set_var(array(
                              'NODES' => $imagePath.'sca_xu_null.gif',
                              'NODES_WIDTH' => 9,
                              'NODES_HEIGHT' => $height
                          ));
                        } else {
                          $tpl->set_var(array(
                              'NODES' => $imagePath.'sca_xu.gif',
                              'NODES_WIDTH' => 12,
                              'NODES_HEIGHT' => $height
                          ));
                        }

                        // T�ne darstellen
                        $showNote = false;
                        foreach($this->results[$this->resNbr] AS $resultKey=>$resultVal){
                          // Ton oder kein Ton?
                          if(($resultVal['string'] == $stringName) &&
                             ($resultVal['fret'] == $fretIndex) ){
                            $showNote = true;
                          }
                        }

                        // Ton oder kein Ton?
                        if($showNote){
                          $tpl->set_var(array(
                              'PICTURE' => $imagePath.'sca_tonexu.gif',
                              'PICTURE_WIDTH' => 33,
                              'PICTURE_HEIGHT' => $height
                          ));
                        } else {
                          $tpl->set_var(array(
                              'PICTURE' => $imagePath.'sca_lineu.gif',
                              'PICTURE_WIDTH' => 33,
                              'PICTURE_HEIGHT' => $height
                          ));
                        }

                      break;

                      default:
                        $height = 19;
                        if($fretIndex == 1){
                          $tpl->set_var(array(
                              'NODES' => $imagePath.'sca_x_null.gif',
                              'NODES_WIDTH' => 9,
                              'NODES_HEIGHT' => $height
                          ));
                        } else {
                          $tpl->set_var(array(
                              'NODES' => $imagePath.'sca_x.gif',
                              'NODES_WIDTH' => 12,
                              'NODES_HEIGHT' => $height
                          ));
                        }

                        // T�ne darstellen
                        $showNote = false;
                        foreach($this->results[$this->resNbr] AS $resultKey=>$resultVal){
                          // Ton oder kein Ton?
                          if(($resultVal['string'] == $stringName) &&
                             ($resultVal['fret'] == $fretIndex) ){
                            $showNote = true;
                          }
                        }

                        // Ton oder kein Ton?
                        if($showNote){
                          $tpl->set_var(array(
                              'PICTURE' => $imagePath.'sca_tonex.gif',
                              'PICTURE_WIDTH' => 33,
                              'PICTURE_HEIGHT' => $height
                          ));
                        } else {
                          $tpl->set_var(array(
                              'PICTURE' => $imagePath.'sca_line.gif',
                              'PICTURE_WIDTH' => 33,
                              'PICTURE_HEIGHT' => $height
                          ));
                        }

                      break;
                    }

                    $tpl->parse('ROW2_BLOCK', 'COL2', true );
                    $fretIndex++;
                  }
                  $tpl->parse('ROW2_BLOCK', 'STRING_END2', true );
                  $tpl->parse('ROW2_BLOCK', 'ROW2', true);
              $index++;
            }

            // Lagen ausgeben
            foreach($this->strings['G'] AS $fretNumber=>$fret){
              $fretNumber = $fretNumber - 1;
              if($fretNumber==3){
                $tpl->set_var(array('LAGE_NAME' => 'III' ));
              }
              elseif($fretNumber==5){
                $tpl->set_var(array('LAGE_NAME' => 'V' ));
              }
              elseif($fretNumber==7){
                $tpl->set_var(array('LAGE_NAME' => 'VII' ));
              }
              elseif($fretNumber==9){
                $tpl->set_var(array('LAGE_NAME' => 'IX' ));
              }
              elseif($fretNumber==12){
                $tpl->set_var(array('LAGE_NAME' => 'XII' ));
              }
              else {
                $tpl->set_var(array('LAGE_NAME' => '' ));
              }
              $tpl->parse('LAGEN2_BLOCK', 'LAGEN2', true );
            }

            if((count($this->results)>1) && ($this->resNbr == 0)) {
              $tpl->parse('SHOW_RESULTS_BLOCK', 'RESULT_TITLE', true );

              /*
              foreach($this->results AS $resultNumber=>$result){
                if($resNbr==$resultNumber){
                  $tpl->set_var(array('INTERVAL_RESULTS' => $resultNumber+1 ));
                $tpl->parse('SHOW_RESULTS_BLOCK', 'ACTIVE_INTERVAL_RESULTS', true );
                } else {
                $tpl->set_var(array('LNK_INTERVAL_RESULTS' => '?chord='.$this->chord.'&root='.$this->root.'&numberOfStrings='.$this->numberOfStrings.'&position='.$this->position.'&resNbr='.$resultNumber,
                                    'INTERVAL_RESULTS' => $resultNumber+1 ));
                $tpl->parse('SHOW_RESULTS_BLOCK', 'MORE_INTERVAL_RESULTS', true );
                }
              }
              */

            }

            if(count($this->results) == 0){
              $tpl->set_var('TXT_RESULT_MESSAGE', 'keine Ergebnisse');
            }

            //if((count($this->results)>1) && ($this->resNbr == 0)) {
                if($this->showMainTitle && ($this->toneNames != '') && ($this->resNbr == 0)){
                    $tpl->set_var(array('TXT_MESSAGE_INTERVAL' => implode(' - ', $this->toneNames) ));
                    $tpl->parse('MAIN_TITLE_BLOCK', 'MAIN_TITLE', true );
                }
            //}

            $tpl->parse ('CONTENT', 'content');
            $out .= $tpl->get('CONTENT');
        }
        return $out;
    }


    function calculateIntervals($root, $notes){
      $notes = explode('-', $notes);
      foreach($notes AS $note){
        $calcNotes[] = $this->calculateInterval($root, $note);
      }
      return $calcNotes;
    }

    function calculateInterval($root, $interval){

    $intervals = $this->getIntervalDefinitions();


      // f�r Berechnungen der Tonabst�nde
      $intervalTable = array('C' => 0,
                             'C#' => 1,
                             'Db' => 1,
                             'D' => 2,
                             'D#' => 3,
                             'Eb' => 3,
                             'E' => 4,
                             'Fb' => 4,
                             'E#' => 5,
                             'F' => 5,
                             'F#' => 6,
                             'Gb' => 6,
                             'G' => 7,
                             'G#' => 8,
                             'Ab' => 8,
                             'A' => 9,
                             'A#' => 10,
                             'Bb' => 10,
                             'B' => 11,
                             'Cb' => 11
                             );

      // Definition der b-Tonarten
      $flatKeys = array('C', 'F', 'Bb', 'Eb', 'Ab', 'Db', 'Gb');

      // Definition der #-Tonarten
      $sharpKeys = array('C', 'G', 'D', 'A', 'E', 'B', 'F#');

      $intervalSteps = $intervals[$interval];

      // Intervall berechnen
      $calculatedInterval = $intervalTable[$root] + $intervalSteps;
      if($calculatedInterval > 11) {
        $calculatedInterval = $calculatedInterval - 12;
      }

      // f�r Intervalle, die �ber den Oktavraum hinausgehen
      if($calculatedInterval > 11) {
        $calculatedInterval = $calculatedInterval - 12;
      }

      // alle m�glichen T�ne speichern, ohne enharmonische Ber�cksichtigung
      foreach($intervalTable AS $note=>$value){
        if($value == $calculatedInterval){
          if($this->is_sharp($note)){
            $possibleResults['S'] = $note;
          }
          elseif($this->is_flat($note)){
            $possibleResults['F'] = $note;
          }
          else{
            $possibleResults['N'] = $note;
          }
        }
      }


      // Je nach b- oder #-Tonart den richtigen Ton berechnen
      if($this->is_sharp($root)){
        if(isset($possibleResults['S'])){
          $calculatedInterval = $possibleResults['S'];
        } else {
          $calculatedInterval = $possibleResults['N'] ?? '';
        }
      }
      elseif($this->is_flat($root)){
        if(isset($possibleResults['F'])){
          $calculatedInterval = $possibleResults['F'];
        } else {
          $calculatedInterval = $possibleResults['N'] ?? '';
        }
      }
      else{
        if(isset($possibleResults['N'])){
          $calculatedInterval = $possibleResults['N'];
        }
        elseif(in_array($root, $flatKeys)){
          $calculatedInterval = $possibleResults['F'] ?? '';
        }
        elseif(in_array($root, $sharpKeys)){
          $calculatedInterval = $possibleResults['S'] ?? '';
        }
      }
      return $calculatedInterval;
    }

    function is_sharp($note){
      $sharp = false;
      if(preg_match('/#/', $note)){
        $sharp = true;
      }
      return $sharp;
    }


    function is_flat($note){
      $flat = false;
      if(preg_match('/b/', $note)){
        $flat = true;
      }
      return $flat;
    }

    function getAllPossibilities($root, $notes, $position, $numberOfStrings=4): array
    {

      $strings       = $this->getStringDefinitions();
      $openStrings   = $this->getOpenStringDefinitions();
      $numberedNotes = $this->getNumberedNoteDefinitons();
      $results = [];

      if($numberOfStrings == '4'){
        unset($strings['C']);
        unset($strings['B']);
      }

      if($numberOfStrings == '5'){
        unset($strings['C']);
      }

        // T�ne berechnen
        foreach($strings AS $stringName=>$string){

          foreach($notes AS $key=>$note){
            if($note == $stringName){
              $results[$key][] = array('string' => $stringName, 'fret' => 0, 'absolut' => $openStrings[$stringName]['N'], 'numbered' => $numberedNotes[$openStrings[$stringName]['N']] );
            }
          }

          $fretIndex = 1;
          foreach($string AS $fret){

            // Ton oder kein Ton?
            $flatTone   = preg_replace('/[0-9]/', '', $fret['F']);
            $sharpTone  = preg_replace('/[0-9]/', '', $fret['S']);
            $normalTone = preg_replace('/[0-9]/', '', $fret['N']);

            foreach($notes AS $key=>$note){
              /* @phpstan-ignore-next-line */
              if(($fretIndex >= $position) && ($fretIndex < ($position+$this->posWidth))){
                if($note == $flatTone){
                    $results[$key][] = array('string' => $stringName, 'fret' => $fretIndex, 'absolut' => $fret['F'], 'numbered' => $numberedNotes[$fret['F']] );
                }
                elseif($note == $normalTone){
                  $results[$key][] = array('string' => $stringName, 'fret' => $fretIndex, 'absolut' => $fret['N'], 'numbered' => $numberedNotes[$fret['N']] );
                }
                elseif($note == $sharpTone){
                  $results[$key][] = array('string' => $stringName, 'fret' => $fretIndex, 'absolut' => $fret['S'], 'numbered' => $numberedNotes[$fret['S']] );
                }
              }
            }

            $fretIndex++;
          }
        }

      ksort($results);
      return $results;

    }


    function filter($results, $chord){

      // -> Test
      foreach($results AS $resultIndex=>$result){
          foreach($result AS $noteIndex=>$note) {
            if($note['fret'] == 0) {
                //unset($results[$resultIndex][$noteIndex]);
            }
          }
      }
      // <--

      $test = array();
      foreach($results AS $rootIndex=>$result){
        if($rootIndex == 0){
          foreach($result AS $key=>$note){
            $test[][$rootIndex] = $note;
          }
        } else {
          $tempArr = $test;
          $test = NULL;
          for($i=0; $i<count($tempArr); $i++){
            foreach($result AS $key=>$note){
              $test[] = $tempArr[$i];
            }
          }

          $index = 0;
          foreach($test AS $testKey=>$testVal){
            if(isset($result[$index])){
              $test[$testKey][] = $result[$index];
              $index++;
              if(!isset($result[$index])){
                $index = 0;
              }
            } else {
              $index = 0;
            }
          }
        }
      }

      // Defaultwert muss genau so aussehen!!
      $retArr = array();
      foreach($test AS $key=>$val){
        $lowest = $val[0]['numbered'];
        $highest = 0;
        $delete = false;
        for($i=1; $i<count($val); $i++){
          if($val[$i]['numbered'] >= $lowest){
            $highest = $val[$i]['numbered'];
          }
          if($val[$i]['numbered'] < $val[$i-1]['numbered']){
            $delete = true;
          }
        }
      $chordParts = explode('-', $chord);
      $lastInterval = $chordParts[count($chordParts)-1];

      $intDef = $this->getIntervalDefinitions();

      if(($highest - $lowest) != $intDef[$lastInterval]){
        $delete = true;
      }


        if($delete == false){
          $retArr[$key] = $val;
        }

      }
      // -----------------------------------------------------------
      if(count($retArr) == 0) {
         $retArr = array('0' => array());
      }
      // -----------------------------------------------------------
      return $retArr;



    }


    function getOpenStringDefinitions(){

      // Definition der Leersaiten
      $openStrings['C'] = array('F' => '', 'N' => 'C3', 'S' => '');
      $openStrings['G'] = array('F' => '', 'N' => 'G2', 'S' => '');
      $openStrings['D'] = array('F' => '', 'N' => 'D2', 'S' => '');
      $openStrings['A'] = array('F' => '', 'N' => 'A1', 'S' => '');
      $openStrings['E'] = array('F' => '', 'N' => 'E1', 'S' => '');
      $openStrings['B'] = array('F' => '', 'N' => 'B0', 'S' => '');

      return $openStrings;
    }


    function getStringDefinitions(){

    // Definition der T�ne auf den Saiten
    $strings['C'][1] = array('F' => 'Db3', 'N' => '', 'S' => 'C#3');
    $strings['C'][2] = array('F' => '', 'N' => 'D3', 'S' => '');
    $strings['C'][3] = array('F' => 'Eb3', 'N' => '', 'S' => 'D#3');
    $strings['C'][4] = array('F' => 'Fb3', 'N' => 'E3', 'S' => '');
    $strings['C'][5] = array('F' => '', 'N' => 'F3', 'S' => 'E#3');
    $strings['C'][6] = array('F' => 'Gb3', 'N' => '', 'S' => 'F#3');
    $strings['C'][7] = array('F' => '', 'N' => 'G3', 'S' => '');
    $strings['C'][8] = array('F' => 'Ab3', 'N' => '', 'S' => 'G#3');
    $strings['C'][9] = array('F' => '', 'N' => 'A3', 'S' => '');
    $strings['C'][10] = array('F' => 'Bb3', 'N' => '', 'S' => 'A#3');
    $strings['C'][11] = array('F' => 'Cb3', 'N' => 'B3', 'S' => '');
    $strings['C'][12] = array('F' => '', 'N' => 'C4', 'S' => '');

    $strings['G'][1] = array('F' => 'Ab2', 'N' => '', 'S' => 'G#2');
    $strings['G'][2] = array('F' => '', 'N' => 'A2', 'S' => '');
    $strings['G'][3] = array('F' => 'Bb2', 'N' => '', 'S' => 'A#2');
    $strings['G'][4] = array('F' => 'Cb2', 'N' => 'B2', 'S' => '');
    $strings['G'][5] = array('F' => '', 'N' => 'C3', 'S' => '');
    $strings['G'][6] = array('F' => 'Db3', 'N' => '', 'S' => 'C#3');
    $strings['G'][7] = array('F' => '', 'N' => 'D3', 'S' => '');
    $strings['G'][8] = array('F' => 'Eb3', 'N' => '', 'S' => 'D#3');
    $strings['G'][9] = array('F' => 'Fb3', 'N' => 'E3', 'S' => '');
    $strings['G'][10] = array('F' => '', 'N' => 'F3', 'S' => 'E#3');
    $strings['G'][11] = array('F' => 'Gb3', 'N' => '', 'S' => 'F#3');
    $strings['G'][12] = array('F' => '', 'N' => 'G3', 'S' => '');

    $strings['D'][1] = array('F' => 'Eb2', 'N' => '', 'S' => 'D#2');
    $strings['D'][2] = array('F' => 'Fb2', 'N' => 'E2', 'S' => '');
    $strings['D'][3] = array('F' => '', 'N' => 'F2', 'S' => 'E#2');
    $strings['D'][4] = array('F' => 'Gb2', 'N' => '', 'S' => 'F#2');
    $strings['D'][5] = array('F' => '', 'N' => 'G2', 'S' => '');
    $strings['D'][6] = array('F' => 'Ab2', 'N' => '', 'S' => 'G#2');
    $strings['D'][7] = array('F' => '', 'N' => 'A2', 'S' => '');
    $strings['D'][8] = array('F' => 'Bb2', 'N' => '', 'S' => 'A#2');
    $strings['D'][9] = array('F' => 'Cb2', 'N' => 'B2', 'S' => '');
    $strings['D'][10] = array('F' => '', 'N' => 'C3', 'S' => '');
    $strings['D'][11] = array('F' => 'Db3', 'N' => '', 'S' => 'C#3');
    $strings['D'][12] = array('F' => '', 'N' => 'D3', 'S' => '');

    $strings['A'][1] = array('F' => 'Bb1', 'N' => '', 'S' => 'A#1');
    $strings['A'][2] = array('F' => 'Cb1', 'N' => 'B1', 'S' => '');
    $strings['A'][3] = array('F' => '', 'N' => 'C2', 'S' => '');
    $strings['A'][4] = array('F' => 'Db2', 'N' => '', 'S' => 'C#2');
    $strings['A'][5] = array('F' => '', 'N' => 'D2', 'S' => '');
    $strings['A'][6] = array('F' => 'Eb2', 'N' => '', 'S' => 'D#2');
    $strings['A'][7] = array('F' => 'Fb2', 'N' => 'E2', 'S' => '');
    $strings['A'][8] = array('F' => '', 'N' => 'F2', 'S' => 'E#2');
    $strings['A'][9] = array('F' => 'Gb2', 'N' => '', 'S' => 'F#2');
    $strings['A'][10] = array('F' => '', 'N' => 'G2', 'S' => '');
    $strings['A'][11] = array('F' => 'Ab2', 'N' => '', 'S' => 'G#2');
    $strings['A'][12] = array('F' => '', 'N' => 'A2', 'S' => '');

    $strings['E'][1] = array('F' => '', 'N' => 'F1', 'S' => 'E#1');
    $strings['E'][2] = array('F' => 'Gb1', 'N' => '', 'S' => 'F#1');
    $strings['E'][3] = array('F' => '', 'N' => 'G1', 'S' => '');
    $strings['E'][4] = array('F' => 'Ab1', 'N' => '', 'S' => 'G#1');
    $strings['E'][5] = array('F' => '', 'N' => 'A1', 'S' => '');
    $strings['E'][6] = array('F' => 'Bb1', 'N' => '', 'S' => 'A#1');
    $strings['E'][7] = array('F' => 'Cb1', 'N' => 'B1', 'S' => '');
    $strings['E'][8] = array('F' => '', 'N' => 'C2', 'S' => '');
    $strings['E'][9] = array('F' => 'Db2', 'N' => '', 'S' => 'C#2');
    $strings['E'][10] = array('F' => '', 'N' => 'D2', 'S' => '');
    $strings['E'][11] = array('F' => 'Eb2', 'N' => '', 'S' => 'D#2');
    $strings['E'][12] = array('F' => 'Fb2', 'N' => 'E2', 'S' => '');

    $strings['B'][1] = array('F' => '', 'N' => 'C1', 'S' => '');
    $strings['B'][2] = array('F' => 'Db1', 'N' => '', 'S' => 'C#1');
    $strings['B'][3] = array('F' => '', 'N' => 'D1', 'S' => '');
    $strings['B'][4] = array('F' => 'Eb1', 'N' => '', 'S' => 'D#1');
    $strings['B'][5] = array('F' => 'Fb1', 'N' => 'E1', 'S' => '');
    $strings['B'][6] = array('F' => '', 'N' => 'F1', 'S' => 'E#1');
    $strings['B'][7] = array('F' => 'Gb1', 'N' => '', 'S' => 'F#1');
    $strings['B'][8] = array('F' => '', 'N' => 'G1', 'S' => '');
    $strings['B'][9] = array('F' => 'Ab1', 'N' => '', 'S' => 'G#1');
    $strings['B'][10] = array('F' => '', 'N' => 'A1', 'S' => '');
    $strings['B'][11] = array('F' => 'Bb1', 'N' => '', 'S' => 'A#1');
    $strings['B'][12] = array('F' => 'Cb1', 'N' => 'B1', 'S' => '');

      return $strings;
    }


    function getNumberedNoteDefinitons(){

      // numerierte Noten (�hnlich wie Midinummern)
      $numberedNotes = array(
       'B0' => -1,
       'C1' => 0,
       'C#1' => 1,
       'Db1' => 1,
       'D1' => 2,
       'D#1' => 3,
       'Eb1' => 3,
       'E1' => 4,
       'Fb1' => 4,
       'E#1' => 5,
       'F1' => 5,
       'F#1' => 6,
       'Gb1' => 6,
       'G1' => 7,
       'G#1' => 8,
       'Ab1' => 8,
       'A1' => 9,
       'A#1' => 10,
       'Bb1' => 10,
       'B1' => 11,
       'Cb1' => 11,
       'C2' => 12,
       'C#2' => 13,
       'Db2' => 13,
       'D2' => 14,
       'D#2' => 15,
       'Eb2' => 15,
       'E2' => 16,
       'Fb2' => 16,
       'E#2' => 17,
       'F2' => 17,
       'F#2' => 18,
       'Gb2' => 18,
       'G2' => 19,
       'G#2' => 20,
       'Ab2' => 20,
       'A2' => 21,
       'A#2' => 22,
       'Bb2' => 22,
       'B2' => 23,
       'Cb2' => 23,
       'C3' => 24,
       'C#3' => 25,
       'Db3' => 25,
       'D3' => 26,
       'D#3' => 27,
       'Eb3' => 27,
       'E3' => 28,
       'Fb3' => 28,
       'E#3' => 29,
       'F3' => 29,
       'F#3' => 30,
       'Gb3' => 30,
       'G3' => 31,
       'G#3' => 32,
       'Ab3' => 32,
       'A3' => 33,
       'A#3' => 34,
       'Bb3' => 34,
       'B3' => 35,
       'Cb3' => 35,
       'C4' => 24
     );

      return $numberedNotes;
    }


    function getIntervalDefinitions(){
      // Definitionen der Intervalle
      $intervals['1'] = 0;
      $intervals['k2'] = 1;
      $intervals['g2'] = 2;
      $intervals['k3'] = 3;
      $intervals['g3'] = 4;
      $intervals['v4'] = 4;
      $intervals['r4'] = 5;
      $intervals['u4'] = 6;
      $intervals['v5'] = 6;
      $intervals['r5'] = 7;
      $intervals['u5'] = 8;
      $intervals['k6'] = 8;
      $intervals['g6'] = 9;
      $intervals['k7'] = 10;
      $intervals['g7'] = 11;
      $intervals['r8'] = 12;
      $intervals['k9'] = 13;
      $intervals['g9'] = 14;
      $intervals['k10'] = 15;
      $intervals['g10'] = 16;
      $intervals['v11'] = 16;
      $intervals['r11'] = 17;
      $intervals['u11'] = 18;
      $intervals['v12'] = 18;
      $intervals['r12'] = 19;
      $intervals['u12'] = 20;
      $intervals['k13'] = 20;
      $intervals['g13'] = 21;

      return $intervals;
    }

}



?>
