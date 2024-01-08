document.addEventListener('DOMContentLoaded', function () {
    var typeSelect = document.querySelector('.js-product-form-type');
    var specificTypeTarget = document.querySelector('.js-specific-type-target');
    typeSelect.addEventListener('change', function (e) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    if (!response.content) {
                        specificTypeTarget.innerHTML = ''; // Clear the content
                        specificTypeTarget.classList.add('d-none');
                    } else {
                        // Replace le contenu et affiche
                        specificTypeTarget.innerHTML = response.content;
                        specificTypeTarget.classList.remove('d-none');
                    }
                }
            }
        };
        xhr.open('GET', typeSelect.getAttribute('data-specific-type-url') + '?type=' + typeSelect.value + '&ajax=true', true);
        xhr.send();
    });
});