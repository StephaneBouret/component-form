document.addEventListener("DOMContentLoaded", function () {
    // Récupération du bouton "Enregistrer" de la modal
    var saveButton = document.getElementById('saveTagsBtn');

    // Ajout d'un gestionnaire d'événement au clic sur le bouton "Enregistrer" de la modal
    saveButton.addEventListener('click', function () {
        // Fermez la modal
        var modal = document.getElementById('exampleModal');
        modal.classList.remove('show');
        modal.classList.add('hide');
        // Supprimez la classe "modal-open" du body pour rétablir le défilement
        document.body.classList.remove('modal-open');

        // Supprimez l'arrière-plan de la modal pour rétablir le focus sur le contenu de la page
        var modalBackdrop = document.querySelector('.modal-backdrop');
        modalBackdrop.remove();
    });
});