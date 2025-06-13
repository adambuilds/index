<div x-data="subjectParentSearch('{{ $subject->id }}')" class="mt-6">
    <input type="text" x-model="query" @input.debounce.300="search" placeholder="Search subjects..." class="w-full rounded-md" />
    <ul class="border rounded-md mt-2 bg-white" x-show="results.length" x-cloak>
        <template x-for="item in results" :key="item.id">
            <li @click="addParent(item.id)" class="px-2 py-1 cursor-pointer hover:bg-gray-100" x-text="item.name"></li>
        </template>
    </ul>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('subjectParentSearch', subjectId => ({
        query: '',
        results: [],
        async search() {
            if (this.query.length < 2) { this.results = []; return; }
            try {
                const response = await fetch(`/api/subjects/search?q=${encodeURIComponent(this.query)}`);
                if (response.ok) {
                    const data = await response.json();
                    this.results = data.subjects;
                }
            } catch (e) {
                console.error(e);
            }
        },
        async addParent(parentId) {
            const csrf = document.querySelector('meta[name=csrf-token]').getAttribute('content');
            await fetch(`/subject/${subjectId}/parents`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ parent_id: parentId })
            });
            this.query = '';
            this.results = [];
            window.location.reload();
        }
    }));
});
</script>
