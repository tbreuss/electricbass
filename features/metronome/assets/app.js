(function () {

  const metronome = new Metronome();

  const volume = document.getElementById('metronomeVolume');
  volume.textContent = metronome.volume;

  volume.addEventListener('input', (event) => {
    let volume = parseFloat(event.target.value);
    metronome.volume = volume

    // hide all icons
    const volumeIcons = document.getElementsByClassName('metronome__volumeIcon');
    const id = Math.ceil(volume * 100 / 25);
    for (const icon of volumeIcons) {
      const elementId = 'metronomeVolumeIcon' + id;
      if (icon.getAttribute('id') === elementId) {
        icon.classList.remove('metronome__volumeIcon--hidden');
      } else {
        icon.classList.add('metronome__volumeIcon--hidden')
      }
    }
  });

  const tempo = document.getElementById('metronomeTempo');
  tempo.textContent = metronome.tempo;

  const accent = document.getElementById('metronomeAccent');
  accent.textContent = metronome.accent;

  const swing = document.getElementById('metronomeSwing');
  swing.textContent = metronome.swing;

  const bar = document.getElementById('metronomeBar');
  bar.textContent = metronome.barLength;

  const playPauseIcon = document.getElementById('metronomePlayPauseIcon');

  const playButton = document.getElementById('metronomePlayPauseButton');
  playButton.addEventListener('click', () => {
    metronome.startStop();

    if (metronome.isRunning) {
      playPauseIcon.classList.add('metronome__playPauseIcon--playing');
      playPauseIcon.classList.remove('metronome__playPauseIcon--pausing');
    } else {
      playPauseIcon.classList.add('metronome__playPauseIcon--pausing');
      playPauseIcon.classList.remove('metronome__playPauseIcon--playing');
    }

  });

  const tempoChangeButtons = document.getElementsByClassName('metronome__tempoChange');
  for (let i = 0; i < tempoChangeButtons.length; i++) {
    tempoChangeButtons[i].addEventListener('click', (event) => {
      let newValue = metronome.tempo + parseInt(event.target.dataset.change);
      let minValue = 40;
      let maxValue = 208;
      if (newValue < minValue) {
        newValue = minValue;
      }
      if (newValue > maxValue) {
        newValue = maxValue;
      }
      metronome.tempo = newValue;
      tempo.textContent = newValue.toString();
    });
  }

  const accentChangeButtons = document.getElementsByClassName('metronome__accentChange');
  for (let i = 0; i < accentChangeButtons.length; i++) {
    accentChangeButtons[i].addEventListener('click', (event) => {
      let newValue = metronome.accent + parseInt(event.target.dataset.change);
      let minValue = 1;
      if (newValue < minValue) {
        newValue = minValue;
      }
      metronome.accent = newValue;
      accent.textContent = newValue.toString();
    });
  }

  const swingChangeButtons = document.getElementsByClassName('metronome__swingChange');
  for (let i = 0; i < swingChangeButtons.length; i++) {
    swingChangeButtons[i].addEventListener('click', (event) => {
      let newValue = metronome.swing + parseInt(event.target.dataset.change);
      let minValue = 10;
      let maxValue = 90;
      if (newValue < minValue) {
        newValue = minValue;
      }
      if (newValue > maxValue) {
        newValue = maxValue;
      }
      metronome.swing = newValue;
      swing.textContent = newValue.toString();
    });
  }

  const barChangeButtons = document.getElementsByClassName('metronome__barChange');
  for (let i = 0; i < barChangeButtons.length; i++) {
    barChangeButtons[i].addEventListener('click', (event) => {
      let newValue = metronome.barLength + parseInt(event.target.dataset.change);
      let minValue = 1;
      if (newValue < minValue) {
        newValue = minValue;
      }
      metronome.barLength = newValue;
      bar.textContent = newValue.toString();
    });
  }

})();
