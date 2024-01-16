document.addEventListener('DOMContentLoaded', function () {
    var removeTagLinks = document.querySelectorAll('.js-tags-wrapper .js-remove-tag');
    var addTagLinks = document.querySelectorAll('.js-tags-wrapper .js-add-tag');
    var wrapper = document.querySelector('.js-tags-wrapper');

    removeTagLinks.forEach(function (link) {
        link.onclick = function (e) {
            e.preventDefault();
            var tagsItem = findClosestParent(link, '.js-tags-item');

            if (tagsItem) {
                tagsItem.style.display = 'none';
                tagsItem.parentNode.removeChild(tagsItem);
            }
        };
    });

    createTagLink.onclick = function (e) {
        e.preventDefault();
        var prototype = wrapper.getAttribute('data-prototype');
        var index = parseInt(wrapper.getAttribute('data-index'), 10);
        var newForm = prototype.replace(/__name__/g, index);
        wrapper.setAttribute('data-index', index + 1);

        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = newForm;
        var newFormElement = tempDiv.firstElementChild;

        if (newFormElement) {
            wrapper.appendChild(newFormElement);
        } else {
            console.log('New form is null');
        }

        var removeLink = newFormElement.querySelector('.js-remove-tag');
        if (removeLink) {
            removeLink.onclick = function (removeEvent) {
                removeEvent.preventDefault();
                newFormElement.parentNode.removeChild(newFormElement);
            };
        }
    };

    addTagLinks.forEach(function (link) {
        link.onclick = function (e) {
            e.preventDefault();
            // Obtenir le prototype de données expliqué précédemment
            var prototype = wrapper.getAttribute('data-prototype');
            // obtenir le nouvel indice
            var index = parseInt(wrapper.getAttribute('data-index'), 10);
            // Remplacer '__name__' dans le HTML du prototype
            // à la place d'un nombre basé sur le nombre d'éléments que nous avons
            var newForm = prototype.replace(/__name__/g, index);
            wrapper.setAttribute('data-index', index + 1);

            // Créez un élément temporaire pour injecter le nouveau formulaire dans le DOM
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = newForm;

            // Récupérez le premier enfant du div temporaire (votre nouveau formulaire)
            var newFormElement = tempDiv.firstElementChild;

            // Nettoyez le contenu du div temporaire en supprimant les nœuds texte vides
            tempDiv.childNodes.forEach(function (node) {
                if (node.nodeType === 3 && !node.nodeValue.trim()) {
                    tempDiv.removeChild(node);
                }
            });

            // Vérifiez si newFormElement est défini avant de l'ajouter
            if (newFormElement) {
                // Insérez le nouvel élément avant le lien "Ajout d'un autre tag"
                wrapper.appendChild(newFormElement);
            } else {
                console.log('New form is null');
            }

            // Ajouter un gestionnaire d'événements pour le lien de suppression dans le nouveau formulaire
            var removeLink = newFormElement.querySelector('.js-remove-tag');
            if (removeLink) {
                removeLink.onclick = function (removeEvent) {
                    removeEvent.preventDefault();
                    // Supprimer le formulaire parent du lien de suppression
                    newFormElement.parentNode.removeChild(newFormElement);
                };
            }
        };
    });

    // Solution sans twig macro
    // addTagLinks.forEach(function (link) {
    //     link.onclick = function (e) {
    //         e.preventDefault();
    //         // Obtenir le prototype de données expliqué précédemment
    //         var prototype = wrapper.getAttribute('data-prototype');
    //         // obtenir le nouvel indice
    //         var index = parseInt(wrapper.getAttribute('data-index'), 10);
    //         // Remplacer '__name__' dans le HTML du prototype
    //         // à la place d'un nombre basé sur le nombre d'éléments que nous avons
    //         var newForm = prototype.replace(/__name__/g, index);
    //         wrapper.setAttribute('data-index', index + 1);
    //         // Créez un élément temporaire pour injecter le nouveau formulaire dans le DOM
    //         var tempDiv = document.createElement('div');
    //         tempDiv.innerHTML = newForm;

    //         // Récupérez le premier enfant du div temporaire (votre nouveau formulaire) et insérez-le avant le lien "Ajout d'un autre tag"
    //         wrapper.insertBefore(tempDiv.firstChild, addTagLinks.nextSibling);
    //     };
    // });

    function findClosestParent(element, selector) {
        while (element && !element.matches(selector)) {
            element = element.parentElement;
        }
        return element;
    }
});