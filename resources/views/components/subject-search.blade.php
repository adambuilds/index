<div x-data="subjectSearch()" class="relative">
    <x-text-input type="text" x-model="query" @input.debounce.300ms="search" placeholder="Search subjects..." class="mt-1 block w-full" autocomplete="off" />
    <input type="hidden" name="{{ $fieldName ?? 'parent_subject_id' }}" :value="selectedId" />
    <ul x-show="results.length" class="absolute z-10 bg-white border rounded w-full mt-1">
        <template x-for="item in results" :key="item.id">
            <li @click="select(item)" class="px-2 py-1 hover:bg-gray-200 cursor-pointer" x-text="item.name"></li>
        </template>
    </ul>
</div>
