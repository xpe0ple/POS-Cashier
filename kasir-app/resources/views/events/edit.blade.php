@extends('layouts.dashboard')

@section('content')

<div class="max-w-xl mx-auto">

    <h1 class="text-xl font-bold mb-4">Edit Event</h1>

    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">

        <form method="POST" action="{{ route('events.update', $event->event_id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <input type="text" name="name"
                value="{{ $event->name }}"
                class="w-full px-4 py-2 rounded-xl bg-gray-900 border border-gray-600 text-white">

            <div class="flex justify-end gap-2">
                <a href="{{ route('events.index') }}"
                   class="px-4 py-2 border border-gray-600 rounded-xl">
                   Batal
                </a>

                <button class="bg-green-500 px-4 py-2 rounded-xl">
                    Update
                </button>
            </div>

        </form>

    </div>

</div>

@endsection