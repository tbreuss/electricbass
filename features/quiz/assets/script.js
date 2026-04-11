const svg = document.querySelector(".quiz-score");
const draggableNote = document.querySelector(".quiz-score-draggable-note");
const selectedNote = document.querySelector(".quiz-score-selected-note");
const upperLedgerLine1 = document.querySelector(".quiz-score-upper-ledger-lines-1");
const upperLedgerLine2 = document.querySelector(".quiz-score-upper-ledger-lines-2");
const upperLedgerLine3 = document.querySelector(".quiz-score-upper-ledger-lines-3");
const lowerLedgerLine1 = document.querySelector(".quiz-score-lower-ledger-lines-1");
const lowerLedgerLine2 = document.querySelector(".quiz-score-lower-ledger-lines-2");
const xCoordinate = document.querySelector(".quiz-score-x-coordinate");
const yCoordinate = document.querySelector(".quiz-score-y-coordinate");

const noteToCoord = [
    ['g1', 14],
    ['f1', 24],
    ['e1', 34],
    ['d1', 44],
    ['c1', 54],
    ['b', 64],
    ['a', 74],
    ['g', 84],
    ['f', 94],
    ['e', 104],
    ['d', 114],
    ['c', 124],
    ['B', 134],
    ['A', 144],
    ['G', 154],
    ['F', 164],
    ['E', 174],
    ['D', 184],
    ['C', 194],
    ['B1', 204],
];

const noteToFrequency = {
    "g1": 195.9975,
    "f1": 174.615,
    "e1": 164.8125,
    "d1": 146.8325,
    "c1": 130.8125,
    "b": 123.47,
    "a": 110.00,
    "g": 98.00,
    "f": 87.3075,
    "e": 82.4075,
    "d": 73.415,
    "c": 65.4075,
    "B": 61.735,
    "A": 55.00,
    "G": 49.00,
    "F": 43.6525,
    "E": 41.2025,
    "D": 36.7075,
    "C": 32.7025,
    "B1": 30.8675,
};

function calcNoteY(offsetY) {
    let y = Math.floor(offsetY / 10) * 10 + 4;
    y = Math.min(204, y);
    y = Math.max(14, y);
    return y;
}

function selectNote(offsetY) {
    const y = calcNoteY(offsetY);
    selectedNote.setAttribute("y", y);
    selectedNote.setAttribute("visibility", "visible");
}

function showNote(offsetY) {
    const y = calcNoteY(offsetY);
    const ySelected = selectedNote.getAttribute("y");
    draggableNote.setAttribute("visibility", "visible");
    draggableNote.setAttribute("y", y);
    upperLedgerLine1.setAttribute("visibility", y <= 54 || ySelected <= 54 ? "visible" : "hidden");
    upperLedgerLine2.setAttribute("visibility", y <= 34 || ySelected <= 34 ? "visible" : "hidden");
    upperLedgerLine3.setAttribute("visibility", y <= 14 || ySelected <= 14 ? "visible" : "hidden");
    lowerLedgerLine1.setAttribute("visibility", y >= 174 || ySelected >= 174 ? "visible" : "hidden");
    lowerLedgerLine2.setAttribute("visibility", y >= 194 || ySelected >= 194 ? "visible" : "hidden");
}

function hideNote() {
    draggableNote.setAttribute("visibility", "hidden");
    const ySelected = selectedNote.getAttribute("y");
    upperLedgerLine1.setAttribute("visibility", ySelected <= 54 ? "visible" : "hidden");
    upperLedgerLine2.setAttribute("visibility", ySelected <= 34 ? "visible" : "hidden");
    upperLedgerLine3.setAttribute("visibility", ySelected <= 14 ? "visible" : "hidden");
    lowerLedgerLine1.setAttribute("visibility", ySelected >= 174 ? "visible" : "hidden");
    lowerLedgerLine2.setAttribute("visibility", ySelected >= 194 ? "visible" : "hidden");
}

function toSvgX(x) {
    return 800 / svg.clientWidth * x;
}

function toSvgY(y) {
    return 220 / svg.clientHeight * y;
}

function showCoordinate(x, y) {
    xCoordinate.setAttribute("y1", y);
    xCoordinate.setAttribute("y2", y);
    yCoordinate.setAttribute("x1", x);
    yCoordinate.setAttribute("x2", x);
}

function setNote(note) {
    for (let i = 0; i < noteToCoord.length; i++) {
        if (noteToCoord[i][0] === note) {
            selectNote(noteToCoord[i][1]);
            showNote(noteToCoord[i][1]);
        }
    }
}

function playTone(note) {
    const frequency = noteToFrequency[note];
    const context = new AudioContext();
    const oscillator = context.createOscillator();
    const gainNode = context.createGain();
    const filter = context.createBiquadFilter();

    // Sägezahn-Welle für volleren Klang (besser als Sinus für Bass)
    oscillator.type = 'sawtooth';
    oscillator.frequency.setValueAtTime(frequency, context.currentTime); // E2 (Bass-E)

    // Filter: Tiefpass für "dunklen" Bass-Klang
    filter.type = 'lowpass';
    filter.frequency.setValueAtTime(200, context.currentTime);

    // Lautstärke
    gainNode.gain.setValueAtTime(0.3, context.currentTime);

    // Verbinden
    oscillator.connect(filter);
    filter.connect(gainNode);
    gainNode.connect(context.destination);

    oscillator.start();
    oscillator.stop(context.currentTime + 1); // 1 Sekunde
}

export {
    hideNote,
    playTone,
    showNote,
    selectNote,
    setNote,
    showCoordinate,
    toSvgX,
    toSvgY,
};
