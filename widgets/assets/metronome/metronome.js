class Metronome {
  constructor(volume = 0.8, tempo = 120, accent = 4, barLength = 8, swing = 50) {
    this.volume = volume;
    this.audioContext = null;
    this.currentQuarterNote = 0;
    this.tempo = tempo;
    this.lookahead = 25;          // How frequently to call scheduling function (in milliseconds)
    this.scheduleAheadTime = 0.1; // How far ahead to schedule audio (sec)
    this.nextNoteTime = 0.0;      // when the next note is due
    this.isRunning = false;
    this.intervalID = null;
    this.swing = swing;           // a value for "swing" between zero and one hundred
    this.accent = accent;
    this.barLength = barLength;
    this.currentEighthNote = 0
  }

  nextNote() {
    // Advance current note and time by a quarter note (crotchet if you're posh)
    var sw = this.swing / 100
    var secondsPerBeat = 60.0 / this.tempo; // Notice this picks up the CURRENT tempo value to calculate beat length.

    if (this.currentEighthNote == 0) {
      this.nextNoteTime += secondsPerBeat * sw;
      //console.log("beat")
    } else {
      //console.log("and")
      this.nextNoteTime += secondsPerBeat * (1 - sw);

    }
    // this.nextNoteTime += secondsPerBeat; // Add beat length to last beat time
    this.currentQuarterNote++;    // Advance the beat number, wrap to zero
    if (this.currentQuarterNote >= this.barLength) {
      this.currentQuarterNote = 0;
      //console.log("here")
    }

    if (this.currentEighthNote == 0) {
      this.currentEighthNote = 1
    } else {
      this.currentEighthNote = 0
    }

    //console.log(this.currentQuarterNote, this.currentEighthNote);
  }

  scheduleNote(beatNumber, time) {
    
    let volume = 1 * this.volume;
    if ((this.currentQuarterNote + 1) % 2 == 0) {
      volume = 0.25 * this.volume
    }

    // no volume, return
    if (volume <= 0) {
      return
    }

    // create oscillator node
    const osc = this.audioContext.createOscillator();

    if (this.currentQuarterNote == 0) {
      osc.frequency.value = 1000
    } else {
      osc.frequency.value = (beatNumber % this.accent == 0) ? 1000 : 800;
    }

    // create gain node
    const gain = this.audioContext.createGain();
    gain.gain.value = 1;
    gain.gain.exponentialRampToValueAtTime(volume, time + 0.001);
    gain.gain.exponentialRampToValueAtTime(0.001, time + 0.02);

    // chain nodes
    osc.connect(gain);
    gain.connect(this.audioContext.destination);

    // play and stop
    osc.start(time);
    osc.stop(time + 0.03);
  }

  scheduler() {
    // while there are notes that will need to play before the next interval, schedule them and advance the pointer.
    while (this.nextNoteTime < this.audioContext.currentTime + this.scheduleAheadTime) {
      this.scheduleNote(this.currentQuarterNote, this.nextNoteTime);
      this.nextNote();
    }
  }

  start() {
    if (this.isRunning) return;

    if (this.audioContext == null) {
      this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }

    this.isRunning = true;

    this.currentQuarterNote = 0;

    this.nextNoteTime = this.audioContext.currentTime + 0.05;

    this.intervalID = setInterval(() => this.scheduler(), this.lookahead);
  }

  stop() {
    this.isRunning = false;

    clearInterval(this.intervalID);
  }

  startStop() {
    if (this.isRunning) {
      this.stop();
    } else {
      this.start();
    }
  }
}
