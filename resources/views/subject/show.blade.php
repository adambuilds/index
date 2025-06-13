@extends('layouts.app')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __($subject->name) }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Click and drag to your desktop to save the link to this subject.') }}
        </p>

        <a href="{{ route('subject.show', $subject->id) }}" class="mt-4 inline-flex items-center bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <img src="{{ 'qr/' . $subject->id . '.png' }}" alt="QR code for {{ $subject->name }}." class="mt-4 w-full h-auto rounded-lg shadow-md bg-white">
        </a>

        @if($subject->links->count())
        <div class="mt-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Links</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subject->links as $link)
                    <li class="flex justify-between">
                        <a href="{{ $link->url }}" class="text-blue-600 hover:underline" target="_blank">{{ $link->title }}</a>
                        <form method="POST" action="{{ route('subject.links.destroy', [$subject->id, $link->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">x</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($subject->tags->count())
        <div class="mt-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Tags</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subject->tags as $tag)
                    <li class="flex justify-between">
                        <span class="text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                        <form method="POST" action="{{ route('subject.tags.destroy', [$subject->id, $tag->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">x</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($subject->parents->count())
        <div class="mt-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Belongs To</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subject->parents as $parent)
                    <li class="text-gray-700 dark:text-gray-300">{{ $parent->name }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($subject->children->count())
        <div class="mt-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Members</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subject->children as $child)
                    <li class="text-gray-700 dark:text-gray-300">{{ $child->name }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($subject->meta->count())
        <div class="mt-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Metadata</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subject->meta as $meta)
                    <li class="flex justify-between">
                        <span class="text-gray-700 dark:text-gray-300">{{ $meta->key }}:</span>
                        <span class="text-gray-700 dark:text-gray-300">{{ $meta->value }}</span>
                        <form method="POST" action="{{ route('subject.meta.destroy', [$subject->id, $meta->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">x</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('subject.meta.store', $subject->id) }}" class="mt-6 space-y-2">
            @csrf
            <div>
                <input type="text" name="key" placeholder="Key" class="w-full rounded-md" required />
            </div>
            <div>
                <input type="text" name="value" placeholder="Value" class="w-full rounded-md" />
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md">Add</button>
            </div>
        </form>

        <form method="POST" action="{{ route('subject.links.store', $subject->id) }}" class="mt-6 space-y-2">
            @csrf
            <div>
                <input type="text" name="title" placeholder="Link title" class="w-full rounded-md" required />
            </div>
            <div>
                <input type="url" name="url" placeholder="https://example.com" class="w-full rounded-md" required />
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md">Add Link</button>
            </div>
        </form>
        <form method="POST" action="{{ route('subject.tags.store', $subject->id) }}" class="mt-6 space-y-2">
            @csrf
            <div>
                <input type="text" name="name" placeholder="Tag name" class="w-full rounded-md" required />
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md">Add Tag</button>
            </div>
        </form>
    </header>
</section>

            </div>
        </div>

    </div>
</div>

@endsection