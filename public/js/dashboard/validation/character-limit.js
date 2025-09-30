function setCharacterLimit(input) {
    input.addEventListener("input", function () {
        let charCount = input.value.length;
        let minLength = input.getAttribute("minlength");
        let errorMsgDisplay = document.getElementById(`error-msg-${input.id}`);

        errorMsgDisplay.textContent = charCount <= minLength ? `Caracteres digitados: ${charCount}` : "";
    });
}
