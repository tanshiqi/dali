import "preline";

document.addEventListener("alpine:init", () => {
    Alpine.magic("clipboard", () => {
        return (subject) => navigator.clipboard.writeText(subject);
    });
});
