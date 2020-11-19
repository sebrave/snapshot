<head>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<div style="display: flex; align-content: center; justify-content: space-between;" class="p-5">
    <h1 class="text-3xl">Snapshot of {{ $name }} table</h1>
    <p class="text-sm">{{ $timestamp }}</p>
</div>

<div class="px-3 py-4">
    <table style="width:auto" class="w-full text-md bg-white shadow-md rounded mb-4 overflow-x-scroll">
        <thead>
        <tr class="border-b">
            @foreach ($data->first()->toArray() as $key => $value)
                <td class="text-left p-3 px-5 text-sm"><b>{{ $key }}</b></td>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $model)
            <tr class="border-b bg-gray-100">
                @foreach ($model->toArray() as $value)
                    @if (gettype($value) === 'integer')
                        <td class="p-3 px-5 text-sm text-blue-700">{{ $value }}</td>
                    @endif
                    @if (gettype($value) === 'string')
                        <td class="p-3 px-5 text-sm">{{ $value }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>