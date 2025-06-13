import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.subjectSearch = function () {
    return {
        query: '',
        results: [],
        selectedId: null,
        search() {
            if (this.query.length < 2) { this.results = []; return; }
            axios.get('/subject/search', { params: { q: this.query } })
                .then(r => { this.results = r.data.subjects; });
        },
        select(item) {
            this.query = item.name;
            this.selectedId = item.id;
            this.results = [];
        }
    }
}
