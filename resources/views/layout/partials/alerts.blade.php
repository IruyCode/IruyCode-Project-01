@if (!session('success') && isset($success))
    @php session()->flash('success', $success); @endphp
@endif
@if (!session('danger') && isset($danger))
    @php session()->flash('danger', $danger); @endphp
@endif
@if (!session('warning') && isset($warning))
    @php session()->flash('warning', $warning); @endphp
@endif
@if (!session('info') && isset($info))
    @php session()->flash('info', $info); @endphp
@endif

<!-- Flash + validation alerts (Tailwind + Alpine) -->
<div class="max-w-7xl mx-auto px-4">
    <!-- Flash messages: success, danger, warning, info -->
    @php
        $flashLevels = [
            'success' => [
                'bg' => 'bg-green-100',
                'text' => 'text-green-800',
                'border' => 'border-green-300',
                'label' => 'Sucesso: ',
            ],
            'danger' => [
                'bg' => 'bg-red-100',
                'text' => 'text-red-800',
                'border' => 'border-red-300',
                'label' => 'Erro: ',
            ],
            'warning' => [
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'border' => 'border-yellow-300',
                'label' => 'Aviso: ',
            ],
            'info' => [
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-800',
                'border' => 'border-blue-300',
                'label' => 'Info: ',
            ],
        ];
    @endphp

    @foreach ($flashLevels as $level => $sty)
        @if (session($level))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.300ms
                role="status"
                class="mt-4 rounded-lg border {{ $sty['border'] }} {{ $sty['bg'] }} {{ $sty['text'] }} px-4 py-3">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 font-semibold">{{ $sty['label'] }}</div>
                    <div class="flex-1">{{ session($level) }}</div>
                    <button type="button" @click="show = false" class="opacity-70 hover:opacity-100"
                        aria-label="Fechar">
                        ✕
                    </button>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Validation errors -->
    @if ($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show" x-transition.opacity.duration.300ms
            role="alert" class="mt-4 rounded-lg border border-red-300 bg-red-50 text-red-800 px-4 py-3">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 font-semibold">Erros de validação</div>
                <ul class="list-disc list-inside text-sm flex-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" @click="show = false" class="opacity-70 hover:opacity-100" aria-label="Fechar">
                    ✕
                </button>
            </div>
        </div>
    @endif

</div>
