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
        
    </header>
</section>

            </div>
        </div>

    </div>
</div>

@endsection