@extends('layouts.dashboard')

@section('content')

<div class="max-w-xl mx-auto">

    <h1 class="text-xl font-bold mb-4">Tambah Event</h1>

    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">

        <form method="POST" action="{{ route('events.store') }}" class="space-y-4">
            @csrf

            <input type="text" name="name"
                placeholder="Nama Event"
                class="w-full px-4 py-2 rounded-xl bg-gray-900 border border-gray-600 text-white">

            <div class="flex justify-end gap-2">
                <a href="{{ route('events.index') }}"
                   class="px-4 py-2 border border-gray-600 rounded-xl">
                   Batal
                </a>

                <button class="bg-blue-500 px-4 py-2 rounded-xl">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>

@endsection