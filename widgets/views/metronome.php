<div class="metronome">

    <div class="metronome__volumeControls">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="metronome__volumeIcon metronome__volumeIcon--hidden bi bi-volume-mute-fill" id="metronomeVolumeIcon0" viewBox="0 0 16 16">
            <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06zm7.137 2.096a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0z"/>
        </svg>
        <svg style="margin-right:6.25px" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="metronome__volumeIcon metronome__volumeIcon--hidden bi bi-volume-off-fill" id="metronomeVolumeIcon1" viewBox="0 0 16 16">
            <path d="M10.717 3.55A.5.5 0 0 1 11 4v8a.5.5 0 0 1-.812.39L7.825 10.5H5.5A.5.5 0 0 1 5 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="metronome__volumeIcon metronome__volumeIcon--hidden bi bi-volume-up-fill" id="metronomeVolumeIcon2" viewBox="0 0 16 16">
            <path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="metronome__volumeIcon bi bi-volume-up-fill" id="metronomeVolumeIcon3" viewBox="0 0 16 16">
            <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z"/>
            <path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/>
        </svg>        
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="metronome__volumeIcon metronome__volumeIcon--hidden bi bi-volume-up-fill" id="metronomeVolumeIcon4" viewBox="0 0 16 16">
            <path d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z"/>
            <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z"/>
            <path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/>
        </svg>
        <input class="metronome__volume" type="range" id="metronomeVolume" min="0" max="1" value="0.75" step="0.01">
    </div>
    
    <div class="metronome__title">Online Metronom</div>
    
    <button class="metronome__button metronome__button--play" id="metronomePlayPauseButton">
        <div id="metronomePlayPauseIcon" class="metronome__playPauseIcon"></div>
    </button>
    
    <!-- TEMPO CONTROLS -->
    <div class="metronome__tempoControls">
        <button class="metronome__button metronome__tempoChange" data-change="-5">-5</button>
        <button class="metronome__button metronome__tempoChange" data-change="-1">-</button>
        <div class="metronome__tempoContainer">
            <div class="metronome__tempo" id="metronomeTempo">120</div>
            <div class="metronome__bpm">BPM</div>
        </div>
        <button class="metronome__button metronome__tempoChange" data-change="+1">+</button>
        <button class="metronome__button metronome__tempoChange" data-change="+5">+5</button>
    </div>

    <!--TIME SIG-->
    <div class="metronome__barControls">
        <div class="metronome__tempoContainer">
            <select>
                <option>1/8</option>
                <option>2/8</option>
                <option>3/8</option>
                <option>4/8</option>
                <option>5/8</option>
                <option>6/8</option>
                <option>7/8</option>
                <option>8/8</option>
                <option>9/8</option>
                <option>10/8</option>
                <option>11/8</option>
                <option>12/8</option>
                <option>13/8</option>
                <option>14/8</option>
                <option>15/8</option>
                <option>16/8</option>
                <option>1/4</option>
                <option>2/4</option>
                <option>3/4</option>
                <option>4/4</option>
                <option>5/4</option>
                <option>6/4</option>
                <option>7/4</option>
                <option>8/4</option>
            </select>
            <div class="metronome__bpm">Taktart</div>
        </div>
    </div>
    
    <!--TIME SIG-->
    <div class="metronome__barControls">
        <button class="metronome__button metronome__barChange" data-change="-1">-</button>
        <div class="metronome__tempoContainer">
            <div class="metronome__bar" id="metronomeBar">4</div>
            <div class="metronome__bpm">Taktart</div>
        </div>
        <button class="metronome__button metronome__barChange" data-change="+1">+</button>
    </div>
    
    <!--TIME SIG-->
    <div class="metronome__accentControls">
        <button class="metronome__button metronome__accentChange" data-change="-1">-</button>
        <div class="metronome__barContainer">
            <div class="metronome__accent" id="metronomeAccent">4</div>
            <div class="metronome__bpm">Akzent</div>
        </div>
        <button class="metronome__button metronome__accentChange" data-change="+1">+</button>
    </div>

    <!--SWING-->
    <div class="metronome__swingControls">
        <button class="metronome__button metronome__swingChange" data-change="-5">-5</button>
        <button class="metronome__button metronome__swingChange" data-change="-1">-</button>
        <div class="metronome__tempoContainer">
            <div class="metronome__swing" id="metronomeSwing">50</div>
            <div class="metronome__bpm">Swing</div>
        </div>
        <button class="metronome__button metronome__swingChange" data-change="+1">+</button>
        <button class="metronome__button metronome__swingChange" data-change="+5">+5</button>
    </div>

</div>
