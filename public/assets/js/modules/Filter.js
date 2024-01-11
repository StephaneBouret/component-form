/**
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 */
export default class Filter {
    constructor(element) {
        console.log('Filter class constructed');
        if (element === null) {
            return;
        }
        console.log('Je me construis');
        this.content = element.querySelector('.js-filter-content');
        this.form = element.querySelector('.js-filter-form');
        this.bindEvents();
    }

    bindEvents() {
        this.form.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })
        this.form.querySelector('#q').addEventListener('keyup', this.loadForm.bind(this));
    }

    async loadForm() {
        console.log('loadForm called');
        const data = new FormData(this.form);
        const url = new URL(this.form.getAttribute('action') || window.location.href);
        const params = new URLSearchParams();
        data.forEach((value, key) => {
            params.append(key, value);
        });
        return this.loadUrl(url.pathname + '?' + params.toString());
    }

    async loadUrl(url) {
        console.log('loadUrl called with url:', url);
        const params = new URLSearchParams(url.split('?')[1] || '');
        params.set('ajax', 1);
        const response = await fetch(url.split('?')[0] + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (response.status >= 200 && response.status < 300) {
            const data = await response.json();
            this.content.innerHTML = data.content;
            params.delete('ajax');
            history.replaceState({}, '', url.split('?')[0] + '?' + params.toString());
        } else {
            console.error(response);
        }
    }
}