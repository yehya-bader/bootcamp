const unsafeBtn = document.getElementById('unsafeBtn');
const safeBtn = document.getElementById('safeBtn');
const resultOutput = document.getElementById('resultOutput');
const userInput = document.getElementById('userInput');

// العرض غير الآمن (يسبب ثغرة)
unsafeBtn.addEventListener('click', () => {
    let inputValue = userInput.value;
    resultOutput.innerHTML = inputValue;
});

// العرض الآمن (يحمي من الثغرة)
safeBtn.addEventListener('click', () => {
    let inputValue = userInput.value;
    resultOutput.textContent = inputValue;
});