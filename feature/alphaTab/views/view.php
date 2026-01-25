<?php
/**
 * @var \yii\web\View $this
 * @var \app\feature\alphaTab\components\AlphaTabApi $alphaTab
 */
?>

<?php $this->title = $alphaTab->title() . ' | Play' ?>
<?php $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']) ?>
<?php $asset = \app\feature\alphaTab\WebAsset::register($this) ?>

<script src="https://kit.fontawesome.com/b43f0e512e.js"></script>
<style>
    .at-surface div:last-of-type { display: none !important; }
</style>
<style type="text/css">
    .at-wrap {
        width: 100%;
        height: 80vh;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: relative;
    }

    .at-header {
        flex: 0 0 auto;
        display: flex;
        justify-content: space-between;
        background-color: #436d9d;
        color: #ffffff;
        padding: 0.5rem 1rem;
    }

    .at-back-link a {
        color: white;
    }

    .at-content {
        position: relative;
        overflow: hidden;
        flex: 1 1 auto;
        border-left: 1px solid rgba(0, 0, 0, 0.12);
        border-right: 1px solid rgba(0, 0, 0, 0.12);
    }

    /** Sidebar **/
    .at-sidebar {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        max-width: 70px;
        width: auto;
        display: flex;
        align-content: stretch;
        z-index: 1001;
        overflow: hidden;
        border-right: 1px solid rgba(0, 0, 0, 0.12);
        background: #f7f7f7;
    }

    .at-sidebar:hover {
        max-width: 400px;
        transition: max-width 0.2s;
        overflow-y: auto;
    }

    .at-viewport {
        overflow-y: auto;
        position: absolute;
        top: 0;
        left: 70px;
        right: 0;
        bottom: 0;
        padding-right: 20px;
    }

    .at-footer {
        flex: 0 0 auto;
        background: #436d9d;
        color: #fff;
    }

    /** Overlay **/

    .at-overlay {
        /** Fill Parent */
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1002;

        /* Blurry dark shade */
        backdrop-filter: blur(3px);
        background: rgba(0, 0, 0, 0.5);

        /** center content */
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .at-overlay-content {
        /* white box with drop-shadow */
        margin-top: 20px;
        background: #fff;
        box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.3);
        padding: 10px;
    }

    /** Track selector **/
    .at-track {
        display: flex;
        position: relative;
        padding: 5px 0;
        transition: background 0.2s;
        cursor: pointer;
    }

    .at-track:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    .at-track > .at-track-icon,
    .at-track > .at-track-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .at-track > .at-track-icon {
        flex-shrink: 0;
        font-size: 32px;
        opacity: 0.5;
        transition: opacity 0.2s;
        width: 64px;
        height: 64px;
        margin-right: 5px;
        align-items: center;
    }

    .at-track-name {
        font-weight: 500;
        min-width: 140px;
    }
    .at-track-volume {
        margin-right: 1rem;
        accent-color: #436d9d;
    }
    .at-track:hover > .at-track-icon {
        opacity: 0.8;
    }

    .at-track.active {
        background: rgba(0, 0, 0, 0.03);
    }

    .at-track.active > .at-track-icon {
        color: #4972a1;
        opacity: 1;
    }

    /** Footer **/
    .at-controls {
        flex: 0 0 auto;
        display: flex;
        justify-content: space-between;
        background: #436d9d;
        color: #fff;
    }

    .at-controls > div {
        display: flex;
        justify-content: flex-start;
        align-content: center;
        align-items: center;
    }

    .at-controls > div > * {
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 4px;
        margin: 0 3px;
    }

    .at-controls .btn {
        color: #fff;
        border-radius: 0;
        height: 40px;
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    .at-controls .btn.disabled {
        cursor: progress;
        opacity: 0.5;
    }

    .at-controls a.active {
        background: #5588c7;
        text-decoration: none;
    }

    .at-controls .btn i {
        vertical-align: top;
    }

    .at-controls select {
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        border: none;
        width: 100%;
        height: 40px;
        background: #436d9d;
        padding: 4px 10px;
        color: #fff;
        font-size: 16px;
        text-align-last: center;
        text-align: center;
        -ms-text-align-last: center;
        -moz-text-align-last: center;
        cursor: pointer;
    }

    .at-song-title {
        font-weight: bold;
    }

    .at-cursor-bar {
        /* Defines the color of the bar background when a bar is played */
        background: rgba(255, 242, 0, 0.25);
    }

    .at-selection div {
        /* Defines the color of the selection background */
        background: rgba(64, 64, 255, 0.1);
    }

    .at-cursor-beat {
        /* Defines the beat cursor */
        background: rgba(64, 64, 255, 0.75);
        width: 3px;
    }

    .at-highlight * {
        /* Defines the color of the music symbols when they are being played (svg) */
        fill: #0078ff;
        stroke: #0078ff;
    }
</style>

<div class="at-wrap">
    <div class="at-overlay">
        <div class="at-overlay-content">
            Notenblatt wird geladen
        </div>
    </div>
    <div class="at-header">
        <div class="at-song-info">
            <span class="at-song-title"></span>
            <span class="at-song-subtitle"></span>
        </div>
        <div class="at-back-link">
            <?php if (str_contains((string)Yii::$app->request->referrer, (string)Yii::$app->request->hostName)): ?>
                <?= app\helpers\Html::a('Zurück', app\helpers\Url::to((string)Yii::$app->request->referrer)) ?>
            <?php else: ?>
                <?= app\helpers\Html::a('Zur Startseite', app\helpers\Url::to(['/site/index'])) ?>
            <?php endif ?>
        </div>
    </div>
    <div class="at-content">
        <div class="at-sidebar">
            <div class="at-sidebar-content">
                <div class="at-track-list"></div>
            </div>
        </div>
        <div class="at-viewport">
            <div class="at-main"><?= $alphaTab->notation() ?></div>
        </div>
    </div>
    <div class="at-controls">
        <div class="at-controls-left">
            <a class="btn at-player-stop disabled">
                <i class="fas fa-step-backward"></i>
            </a>
            <a class="btn at-player-play-pause disabled">
                <i class="fas fa-play"></i>
            </a>
            <span class="at-player-progress">0%</span>
            <div class="at-song-position">00:00 / 00:00</div>
        </div>
        <div class="at-controls-right">
            <a class="btn toggle at-count-in">
                <i class="fas fa-hourglass-half"></i>
            </a>
            <a class="btn at-metronome">
                <i class="fas fa-edit"></i>
            </a>
            <a class="btn at-loop">
                <i class="fas fa-retweet"></i>
            </a>
            <a class="btn at-print">
                <i class="fas fa-print"></i>
            </a>
            <div class="at-zoom">
                <i class="fas fa-search"></i>
                <select>
                    <option value="25">25%</option>
                    <option value="50">50%</option>
                    <option value="75">75%</option>
                    <option value="90">90%</option>
                    <option value="100" selected>100%</option>
                    <option value="110">110%</option>
                    <option value="125">125%</option>
                    <option value="150">150%</option>
                    <option value="200">200%</option>
                </select>
            </div>
            <div class="at-layout">
                <select>
                    <option value="horizontal">Horizontal</option>
                    <option value="page" selected>Page</option>
                </select>
            </div>
        </div>
    </div>
</div>

<template id="at-track-template">
    <div class="at-track">
        <div class="at-track-icon">
            <i class="fas fa-guitar"></i>
        </div>
        <div class="at-track-details">
            <div class="at-track-name"></div>
            <input class="at-track-volume" type="range" min="0" max="1" value="0.85" step="0.05" />
        </div>
    </div>
</template>

<?php if ($alphaTab->isDebug()): ?>
    <details>
        <summary>Notation</summary>
        <pre><?= $alphaTab->notation() ?></pre>
    </details>
    <details>
        <summary>Options</summary>
        <pre><?= $alphaTab->options() ?></pre>
    </details>
<?php endif ?>

<script type="text/javascript">
    window.addEventListener("DOMContentLoaded", () => {
        // load elements
        const wrapper = document.querySelector(".at-wrap");
        const main = wrapper.querySelector(".at-main");

        // initialize alphatab
        const settings = {
            tex: true,
            player: {
                enablePlayer: true,
                //soundFont: "<?= $asset->baseUrl ?>/sf2/sonivox.sf2",
                soundFont: "https://cdn.jsdelivr.net/npm/@coderline/alphatab@latest/dist/soundfont/sonivox.sf2",
                scrollElement: wrapper.querySelector('.at-viewport')
            },
            notation: {
                elements: {
                    scoreTitle: false,
                    scoreSubTitle: false,
                    scoreArtist: false
                }
            }
        };
        const api = new alphaTab.AlphaTabApi(main, settings);

        // overlay logic
        const overlay = wrapper.querySelector(".at-overlay");
        api.renderStarted.on(() => {
            overlay.style.display = "flex";
        });
        api.renderFinished.on(() => {
            overlay.style.display = "none";
        });

        // track selector
        function createTrackItem(track) {
            const trackItem = document
                .querySelector("#at-track-template")
                .content.cloneNode(true).firstElementChild;

            trackItem.querySelector(".at-track-name").innerText = track.name;
            trackItem.track = track;
            trackItem.onclick = (e) => {
                if (e.target.classList.contains('at-track-volume')) {
                    return;
                }
                e.stopPropagation();
                api.renderTracks([track]);
            };

            const trackVolume = trackItem.querySelector(".at-track-volume");
            trackVolume.value = localStorageGetTrackVolume(track.index);
            trackVolume.oninput = (e) => {
                e.stopPropagation();
                api.changeTrackVolume([track], e.target.value);
                localStorageSetTrackVolume(track.index, e.target.value);
            }

            return trackItem;
        }
        const trackList = wrapper.querySelector(".at-track-list");
        api.scoreLoaded.on((score) => {
            // clear items
            trackList.innerHTML = "";
            // generate a track item for all tracks of the score
            score.tracks.forEach((track) => {
                trackList.appendChild(createTrackItem(track));
            });
        });
        api.renderStarted.on(() => {
            // collect tracks being rendered
            const tracks = new Map();
            api.tracks.forEach((t) => {
                tracks.set(t.index, t);
            });
            // mark the item as active or not
            const trackItems = trackList.querySelectorAll(".at-track");
            trackItems.forEach((trackItem) => {
                if (tracks.has(trackItem.track.index)) {
                    trackItem.classList.add("active");
                } else {
                    trackItem.classList.remove("active");
                }
            });
        });

        /** Controls **/
        api.scoreLoaded.on((score) => {
            wrapper.querySelector(".at-song-title").innerText = score.title;
            wrapper.querySelector(".at-song-subtitle").innerText = score.subTitle ? ' - ' + score.subTitle : '';
        });

        function toggleElement(element, metronomeVolume) {
            if (metronomeVolume > 0) {
                if (!element.classList.contains("active")) {
                    element.classList.add("active")
                }
            } else {
                element.classList.remove("active")
            }
        }

        // Count in
        const countIn = wrapper.querySelector('.at-controls .at-count-in');

        api.countInVolume = parseInt(localStorage.getItem('at.countInVolume') || '0');
        toggleElement(countIn, api.countInVolume);

        countIn.onclick = () => {
            api.countInVolume = api.countInVolume === 0 ? 1 : 0;
            localStorage.setItem('at.countInVolume', api.countInVolume.toString());
            toggleElement(countIn, api.countInVolume);
        };

        // Metronome
        const metronome = wrapper.querySelector(".at-controls .at-metronome");

        api.metronomeVolume = parseInt(localStorage.getItem('at.metronomeVolume') || '0');
        toggleElement(metronome, api.metronomeVolume);

        metronome.onclick = () => {
            api.metronomeVolume = api.metronomeVolume === 0 ? 1 : 0;
            localStorage.setItem('at.metronomeVolume', api.metronomeVolume.toString());
            toggleElement(metronome, api.metronomeVolume);
        };

        // Loop
        const loop = wrapper.querySelector(".at-controls .at-loop");
        api.isLooping = parseInt(localStorage.getItem('at.isLooping') || '0');
        toggleElement(loop, api.isLooping);

        loop.onclick = () => {
            api.isLooping = api.isLooping === 0 ? 1 : 0;
            localStorage.setItem('at.isLooping', api.isLooping.toString());
            toggleElement(loop, api.isLooping);
        };

        wrapper.querySelector(".at-controls .at-print").onclick = () => {
            api.print();
        };

        const zoom = wrapper.querySelector(".at-controls .at-zoom select");
        zoom.onchange = () => {
            const zoomLevel = parseInt(zoom.value) / 100;
            api.settings.display.scale = zoomLevel;
            api.updateSettings();
            api.render();
        };

        /*
        // Zoom
        const zoom = wrapper.querySelector(".at-controls .at-zoom select");
        zoom.onchange = () => {
            api.settings.display.scale = parseInt(zoom.value) / 100;
            api.updateSettings();
            api.render();
            localStorage.setItem('at.zoom', zoom.value.toString());
        };
        zoom.value = parseInt(localStorage.getItem('at.zoom') || '100');
        zoom.value !== 100 && zoom.dispatchEvent(new Event('change'));
        */

        function updateSelectElement(element, selected) {
            element.value = selected;
            console.log(element, selected);
        }

        const layout = wrapper.querySelector(".at-controls .at-layout select");
        layout.onchange = () => {
            switch (layout.value) {
                case "horizontal":
                    api.settings.display.layoutMode = alphaTab.LayoutMode.Horizontal;
                    break;
                case "page":
                    api.settings.display.layoutMode = alphaTab.LayoutMode.Page;
                    break;
            }
            api.updateSettings();
            api.render();
        };

        // player loading indicator
        const playerIndicator = wrapper.querySelector(
            ".at-controls .at-player-progress"
        );
        api.soundFontLoad.on((e) => {
            const percentage = Math.floor((e.loaded / e.total) * 100);
            playerIndicator.innerText = percentage + "%";
        });
        api.playerReady.on(() => {
            playerIndicator.style.display = "none";
        });

        // main player controls
        const playPause = wrapper.querySelector(
            ".at-controls .at-player-play-pause"
        );
        const stop = wrapper.querySelector(".at-controls .at-player-stop");
        playPause.onclick = (e) => {
            if (e.target.classList.contains("disabled")) {
                return;
            }

            // seems to be the only place to adjust the track volume
            api.score.tracks.forEach((track) => {
                api.changeTrackVolume([track], localStorageGetTrackVolume(track.index));
            });

            api.playPause();
        };
        stop.onclick = (e) => {
            if (e.target.classList.contains("disabled")) {
                return;
            }
            api.stop();
        };
        api.playerReady.on(() => {
            playPause.classList.remove("disabled");
            stop.classList.remove("disabled");
        });
        api.playerStateChanged.on((e) => {
            const icon = playPause.querySelector("i.fas");
            if (e.state === alphaTab.synth.PlayerState.Playing) {
                icon.classList.remove("fa-play");
                icon.classList.add("fa-pause");
            } else {
                icon.classList.remove("fa-pause");
                icon.classList.add("fa-play");
            }
        });

        // song position
        function formatDuration(milliseconds) {
            let seconds = milliseconds / 1000;
            const minutes = (seconds / 60) | 0;
            seconds = (seconds - minutes * 60) | 0;
            return (
                String(minutes).padStart(2, "0") +
                ":" +
                String(seconds).padStart(2, "0")
            );
        }

        const songPosition = wrapper.querySelector(".at-song-position");
        let previousTime = -1;
        api.playerPositionChanged.on((e) => {
            // reduce number of UI updates to second changes.
            const currentSeconds = (e.currentTime / 1000) | 0;
            if (currentSeconds == previousTime) {
                return;
            }

            songPosition.innerText =
                formatDuration(e.currentTime) + " / " + formatDuration(e.endTime);
        });

        /*
        const layout = wrapper.querySelector(".at-controls .at-layout select");
        api.settings.display.layoutMode = localStorage.getItem('at.layoutMode') || 'page';
        updateSelectElement(layout, api.settings.display.layoutMode);

        layout.onchange = () => {
            switch (layout.value) {
                case "horizontal":
                    api.settings.display.layoutMode = alphaTab.LayoutMode.Horizontal;
                    break;
                case "page":
                    api.settings.display.layoutMode = alphaTab.LayoutMode.Page;
                    break;
            }
            api.updateSettings();
            api.render();
            localStorage.setItem("at.layoutMode", layout.value);
        };
        layout.dispatchEvent(new Event('change'));
        */

        function localStorageGetTrackVolume(trackIndex) {
            return localStorage.getItem("at.volumeTrack" + trackIndex) || 0.85;
        }

        function localStorageSetTrackVolume(trackIndex, volume) {
            return localStorage.setItem("at.volumeTrack" + trackIndex, volume);
        }
    });
</script>
