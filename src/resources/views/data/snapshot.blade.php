<head>
    <title>Database snapshot</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <style>
        .active {
            color: white;
            background-color: deepskyblue;
        }
    </style>
</head>

<div x-data="{ tab: '{{ $tables[0]['shortName'] }}' }" id="tab_wrapper">
    @if (count($tables) > 1)
        <nav class="p-4">
            @foreach($tables as $table)
                <a class="text-md py-2 px-4 rounded-xl"
                   :class="{ 'active': tab === '{{ $table['shortName'] }}' }"
                   @click.prevent="tab = '{{ $table['shortName'] }}'" href="#">
                    {{ $table['shortName'] }}
                </a>
            @endforeach
        </nav>
        <hr>
    @endif

    @foreach($tables as $table)
        @if (count($tables) > 1)
            <div x-show="tab === '{{ $table['shortName'] }}'">
        @else
            <div>
        @endif
        <div style="display: flex; align-content: center; justify-content: space-between;" class="p-5">
            <h1 class="text-3xl">Snapshot of {{ $table['name'] }} table</h1>
            <p class="text-sm">{{ $timestamp }}</p>
        </div>
        <div class="px-3 py-4">
            @if ($table['data']->isNotEmpty())
                <table style="width:auto"
                       class="w-full text-md bg-white shadow-md rounded mb-4 overflow-x-scroll">
                    <thead>
                    <tr class="border-b">
                        @foreach ($table['data']->first() as $key => $value)
                            <td class="text-left p-3 px-5 text-sm"><b>{{ $key }}</b></td>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($table['data'] as $model)
                        <tr class="border-b bg-gray-100">
                            @foreach ($model as $value)
                                @if (is_string($value) || is_numeric($value))
                                    <td class="p-3 px-5 text-sm">{{ $value }}</td>
                                @else
                                    <td class="p-3 px-5 text-sm">{{ json_encode($value) }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>This table is empty</p>
            @endif
        </div>
    </div>
    @endforeach
</div>