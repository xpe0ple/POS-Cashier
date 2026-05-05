@extends('layouts.dashboard')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Event</h1>

        <a href="{{ route('events.create') }}"
           class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-xl text-white shadow">
           + Tambah Event
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow">

        <table class="w-full text-sm text-white">

            <thead class="bg-gray-700 text-gray-300 text-xs uppercase">
                <tr>
                    <th class="p-3 text-left">Nama Event</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($events as $e)
                <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">

                    <td class="p-3 font-medium">
                        {{ $e->name }}
                    </td>

                    <td class="p-3 text-center">
                        <div class="flex justify-center gap-2">

                            <a href="{{ route('events.edit', $e->event_id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded-lg text-xs">
                                Edit
                            </a>

                            <form action="{{ route('events.destroy', $e->event_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded-lg text-xs">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

@endsection