/**
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 */
class Filter {
    /**
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return;
        }
        console.log("Je me construis");
        this.content = element.querySelector('.js-filter-content')
        this.form = element.querySelector('.js-filter-form')
        console.log(this.form);
        this.bindEvents()
    }

    /**
     * Ajoute les comportements aux différents éléments
     */
    bindEvents() {
        this.form.querySelector('#q').addEventListener('keyup', this.loadForm.bind(this))
    }

    async loadForm() {
        const data = new FormData(this.form)

        for (const [key, value] of data.entries()) {
            console.log(`${key}: ${value}`);
        }
        // console.log(this.form.getAttribute('action'));
        const url = new URL(this.form.getAttribute('action') || window.location.pathname, window.location.origin)
        console.log(url);
        // const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams()
        data.forEach((value, key) => {
            params.append(key, value)
        })

        console.log(url.pathname + '?' + params.toString());
        // console.log(url.href + '?' + params.toString());
        return this.loadUrl(url.pathname + '?' + params.toString())
    }

    async loadUrl(url) {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        if (response.status >= 200 && response.status < 300) {
            const data = await response.json()
            console.log(data.content);
            // this.content.innerHTML = data.content
            this.content.insertAdjacentHTML('beforeend', data.content);
        } else {
            console.error(response)
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const filterElement = document.querySelector('.js-filter');
    const filter = new Filter(filterElement);
});