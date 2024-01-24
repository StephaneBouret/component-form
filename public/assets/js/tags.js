document.addEventListener('DOMContentLoaded', function () {
    const removeTagLinks = document.querySelectorAll('.js-tags-wrapper .js-remove-tag');
    const addTagLinks = document.querySelectorAll('.js-tags-wrapper .js-add-tag');
    const wrapper = document.querySelector('.js-tags-wrapper');
    const createTagLink = document.querySelector('.js-create-new-tag');
    const newTagInput = document.querySelector('.js-new-tag-input');
    const cancelNewTagLink = document.querySelector('.js-cancel-new-tag');
    const genericExamples = document.querySelectorAll('[data-trigger]');

    for (let i = 0; i < genericExamples.length; i++) {
        const element = genericExamples[i];
        new Choices(element, {
            allowHTML: true,
        });
    }

    createTagLink.onclick = function (e) {
        e.preventDefault();
        newTagInput.style.display = 'block';
    };

    cancelNewTagLink.onclick = function (e) {
        e.preventDefault();
        newTagInput.style.display = 'none';
    };

    removeTagLinks.forEach(function (link) {
        link.onclick = function (e) {
            e.preventDefault();
            const tagsItem = findClosestParent(link, '.js-tags-item');

            if (tagsItem) {
                tagsItem.style.display = 'none';
                tagsItem.parentNode.removeChild(tagsItem);
            }
        };
    });

    addTagLinks.forEach(function (link) {
        link.onclick = function (e) {
            e.preventDefault();
            // Obtenir le prototype de données expliqué précédemment
            const prototype = wrapper.getAttribute('data-prototype');
            // obtenir le nouvel indice
            const index = parseInt(wrapper.getAttribute('data-index'), 10);
            // Remplacer '__name__' dans le HTML du prototype
            // à la place d'un nombre basé sur le nombre d'éléments que nous avons
            const newForm = prototype.replace(/__name__/g, index);
            wrapper.setAttribute('data-index', index + 1);

            // Créez un élément temporaire pour injecter le nouveau formulaire dans le DOM
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newForm;

            // Récupérez le premier enfant du div temporaire (votre nouveau formulaire)
            const newFormElement = tempDiv.firstElementChild;

            // Nettoyez le contenu du div temporaire en supprimant les nœuds texte vides
            tempDiv.childNodes.forEach(function (node) {
                if (node.nodeType === 3 && !node.nodeValue.trim()) {
                    tempDiv.removeChild(node);
                }
            });

            // Vérifiez si newFormElement est défini avant de l'ajouter
            if (newFormElement) {
                // Ajoutez la classe à l'élément <select>
                const selectElement = newFormElement.querySelector('select');
                if (selectElement) {
                    selectElement.classList.add('js-product-form-type');
                }
                const newSelect = newFormElement.querySelector('.js-product-form-type');
                new Choices(newSelect, {
                    allowHTML: true
                });
                // Insérez le nouvel élément avant le lien "Ajout d'un autre tag"
                wrapper.appendChild(newFormElement);
            } else {
                console.log('New form is null');
            }

            // Ajouter un gestionnaire d'événements pour le lien de suppression dans le nouveau formulaire
            const removeLink = newFormElement.querySelector('.js-remove-tag');
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