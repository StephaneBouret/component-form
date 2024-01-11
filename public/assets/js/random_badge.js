document.addEventListener("DOMContentLoaded", function () {

    // Fonction pour obtenir une classe de badge aléatoire
    function getRandomBadgeClass() {
        var badgeClasses = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        var randomIndex = Math.floor(Math.random() * badgeClasses.length);
        return 'text-bg-' + badgeClasses[randomIndex];
    }

    // Parcourir tous les éléments de tag et application d'une classe aléatoire
    var tagElements = document.querySelectorAll('.badge.bg-fixed');
    tagElements.forEach(function (tagElement) {
        var randomBadgeClass = getRandomBadgeClass();
        tagElement.classList.add(randomBadgeClass);
    });
});