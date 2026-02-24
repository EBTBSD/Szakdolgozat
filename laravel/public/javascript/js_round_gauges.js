function avg_grade() {
    let inputValue = assignmentAverage;
    let grade = Math.max(0, Math.min(5, inputValue));

    let gauge = document.querySelector("#avg_gauge");
    let text = document.querySelector("#avg_text");

    let angle = grade * 72;

    gauge.style.background = `conic-gradient(rgb(255,0,0) 0deg,rgb(0, 255, 0) ${angle}deg, rgba(17, 21, 60, 1) ${angle}deg 360deg)`;
    text.textContent = grade.toFixed(1);
}

function assignment_completed() {
    let inputValue = assignmentCompleted;
    let percent = Math.max(0, Math.min(100, inputValue));

    let gauge = document.querySelector("#ass_gauge");
    let text = document.querySelector("#ass_text");

    let angle = (percent/100) * 360;

    gauge.style.background = `conic-gradient(rgb(255,0,0) 0deg,rgb(0, 255, 0) ${angle}deg, rgba(17, 21, 60, 1) ${angle}deg 360deg)`;
    text.textContent = percent.toFixed(0) + "%";
}

assignmentCompleted
// Animációs effekt betöltéskor
setTimeout(() => {
    avg_grade();
    assignment_completed();
}, 500);
