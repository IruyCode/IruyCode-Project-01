{{-- @extends('layout.app')

@section('content')
    <h1>Notiicacoes</h1>
@endsection --}}


@extends('layout.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10">

        <h2 class="text-2xl font-bold mb-6">Notificações</h2>

        @if ($notifications->count() === 0)
            <p class="text-gray-500">Nenhuma notificação por enquanto.</p>
        @endif

        @foreach ($notifications as $notif)
            <div class="p-4 mb-3 rounded-lg border bg-gray-100 dark:bg-gray-800">
                <div class="flex justify-between">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $notif->title }}</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $notif->message }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Criada em: {{ $notif->triggered_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('core.notifications.check', $notif->id) }}">
                            @csrf
                            <button class="px-3 py-1 text-sm bg-green-600 text-white rounded-md">
                                Marcar lida
                            </button>
                        </form>

                        <form method="POST" action="{{ route('core.notifications.ignore', $notif->id) }}">
                            @csrf
                            <button class="px-3 py-1 text-sm bg-red-600 text-white rounded-md">
                                Ignorar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection



{{-- @extends('layouts.app')

@section('content')
    <div class="px-6 py-6">
        <h1 class="text-2xl font-bold mb-4 text-white">Notificações</h1>

        <form action="{{ route('core.notifications.check-all') }}" method="POST" class="mb-4">
            @csrf
            <button class="px-3 py-2 bg-blue-600 text-white rounded">Marcar todas como lidas</button>
        </form>

        @forelse($notifications as $n)
            <div class="mb-3 p-3 rounded bg-gray-800 text-white flex justify-between items-center">
                <div>
                    <div class="text-sm text-gray-400">
                        [{{ strtoupper($n->module) }}] {{ $n->context }} – {{ $n->type }}
                    </div>
                    <div class="font-semibold">{{ $n->title }}</div>
                    <div class="text-sm text-gray-300">{{ $n->message }}</div>
                    @if ($n->url)
                        <a href="{{ $n->url }}" class="text-blue-400 text-sm underline">Ver mais</a>
                    @endif
                </div>

                <div class="flex gap-2">
                    @if ($n->status === 'active')
                        <form method="POST" action="{{ route('core.notifications.check', $n->id) }}">
                            @csrf
                            <button class="px-2 py-1 text-xs bg-green-600 rounded">Lida</button>
                        </form>
                        <form method="POST" action="{{ route('core.notifications.ignore', $n->id) }}">
                            @csrf
                            <button class="px-2 py-1 text-xs bg-yellow-600 rounded">Ignorar</button>
                        </form>
                    @else
                        <span class="text-xs text-gray-400">{{ $n->status }}</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-300">Nenhuma notificação encontrada.</p>
        @endforelse
    </div>
@endsection --}}
