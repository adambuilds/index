@extends('layouts.app')

@section('content')

<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    <section class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg max-w-xl">

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Create new subject') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('This will create a unique ID and QR code') }}
            </p>
        </header>

        <form method="post" action="{{ route('subject.store') }}" class="mt-6 space-y-6">
            @csrf
            @method('post')

            <div>
                <x-input-label for="name" :value="__('Subject Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"/>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Create Subject') }}</x-primary-button>
            </div>
        </form>

    </section>

    <section class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg max-w-xl">
                
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('All Subjects') }}
        </h2>

        @php
            $subjects = \App\Models\Subject::all();
        @endphp

        <ul>
        @foreach ($subjects as $subject)
            <li>
                <a href="{{ route('subject.show', $subject->id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    {{ $subject->name }}
                </a>
            </li>
        @endforeach
        </ul>
        
    </section>

</div>

@endsection