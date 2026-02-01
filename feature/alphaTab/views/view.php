<?php
/**
 * @var \yii\web\View $this
 * @var \app\feature\alphaTab\components\AlphaTabApi $alphaTab
 */
?>

<?php $this->title = $alphaTab->title() . ' | Play' ?>
<?php $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']) ?>
<?php $asset = \app\feature\alphaTab\WebAsset::register($this) ?>

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

    .at-attribution {
        position: absolute;
        display: inline-block;
        right: 1rem;
        bottom: 0;
        padding: 0.25rem 0.25rem;
        z-index: 2000;
        background: rgba(255,255,255, 0.8);
    }
    .at-attribution a {
        font-family: Arial, sans-serif;
        color: #999999;
        font-size: 16px;
    }
    .at-attribution a:hover {
        color: #000000;
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
        font-size: 16px;
    }
    .at-controls .btn.disabled {
        cursor: progress;
        opacity: 0.5;
    }
    .at-controls .btn.hidden {
        display: none;
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
        <div class="at-attribution"><a href="<?= app\helpers\Url::to(['/lesson/index', 'path' => '/credits']) ?>#alphatab">erstellt mit alphaTab</a></div>
    </div>
    <div class="at-controls">
        <div class="at-controls-left">
            <a class="btn at-player-stop">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-skip-start-fill" viewBox="0 0 16 16">
                    <path d="M4 4a.5.5 0 0 1 1 0v3.248l6.267-3.636c.54-.313 1.232.066 1.232.696v7.384c0 .63-.692 1.01-1.232.697L5 8.753V12a.5.5 0 0 1-1 0z"/>
                </svg>
            </a>
            <a class="btn at-player-play">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                    <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393"/>
                </svg>
            </a>
            <a class="btn at-player-pause hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-pause-fill" viewBox="0 0 16 16">
                    <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5m5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5"/>
                </svg>
            </a>
            <span class="at-player-progress">0%</span>
            <div class="at-song-position">00:00 / 00:00</div>
        </div>
        <div class="at-controls-right">
            <a class="btn toggle at-count-in">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-hourglass-top" viewBox="0 0 16 16">
                    <path d="M2 14.5a.5.5 0 0 0 .5.5h11a.5.5 0 1 0 0-1h-1v-1a4.5 4.5 0 0 0-2.557-4.06c-.29-.139-.443-.377-.443-.59v-.7c0-.213.154-.451.443-.59A4.5 4.5 0 0 0 12.5 3V2h1a.5.5 0 0 0 0-1h-11a.5.5 0 0 0 0 1h1v1a4.5 4.5 0 0 0 2.557 4.06c.29.139.443.377.443.59v.7c0 .213-.154.451-.443.59A4.5 4.5 0 0 0 3.5 13v1h-1a.5.5 0 0 0-.5.5m2.5-.5v-1a3.5 3.5 0 0 1 1.989-3.158c.533-.256 1.011-.79 1.011-1.491v-.702s.18.101.5.101.5-.1.5-.1v.7c0 .701.478 1.236 1.011 1.492A3.5 3.5 0 0 1 11.5 13v1z"/>
                </svg>
            </a>
            <a class="btn at-metronome">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                </svg>
            </a>
            <a class="btn at-loop">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                    <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                    <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                </svg>
            </a>
            <a class="btn at-print">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                </svg>
            </a>
            <div class="at-zoom">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11M13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0"/>
                    <path d="M10.344 11.742q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1 6.5 6.5 0 0 1-1.398 1.4z"/>
                    <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5"/>
                </svg>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-music"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
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
        const play = wrapper.querySelector(".at-controls .at-player-play");
        const pause = wrapper.querySelector(".at-controls .at-player-pause");
        const stop = wrapper.querySelector(".at-controls .at-player-stop");
        play.onclick = () => {
            // seems to be the only place to adjust the track volume
            api.score.tracks.forEach((track) => {
                api.changeTrackVolume([track], localStorageGetTrackVolume(track.index));
            });
            api.playPause();
            play.classList.add("hidden");
            pause.classList.remove("hidden");
        };

        pause.onclick = () => {
            api.playPause();
            play.classList.remove("hidden");
            pause.classList.add("hidden");
        };

        stop.onclick = () => {
            api.stop();
            play.classList.remove("hidden");
            pause.classList.add("hidden");
        };

        api.playerReady.on(() => {
            play.classList.remove("disabled");
            stop.classList.remove("disabled");
        });

        api.playerStateChanged.on((e) => {
            if (e.state !== alphaTab.synth.PlayerState.Playing) {
                play.classList.remove("hidden");
                pause.classList.add("hidden");
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
