<div class="container mx-auto p-4" x-data="">
    <!-- Table -->
    <table class="min-w-full w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="px-4 py-2 border-b">Name</th>
                <th class="px-4 py-2 border-b">{{ $nextRelName }}</th>
                <th class="px-4 py-2 border-b"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border-b">{{ $row->name }}</td>
                    <td class="px-4 py-2 border-b text-center">{{ $row->$nextRelCounter }}</td>
                    <td class="px-4 py-2 border-b text-center">
                        @if($nextRelName)
                            <a href="{{ route($showRouteName, $row->id) }}" class="text-blue-500">View</a>
                        @endif
                        <button
                            @click="duplicate('{{ $row->id }}', '{{ addslashes(get_class($row)) }}')"
                            class="ml-2 text-green-500"
                        >
                            Duplicate
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $rows->links() }}
    </div>
</div>

<script>
    duplicate = (modelId, modelClass) => {
        if (confirm('Are you sure you want to duplicate this record and all related records?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('duplicate') }}",
                data: {
                    _token: $('[name="csrf-token"]').attr('content'),
                    modelId, modelClass
                },
                success: () => { location.reload(); }
            });
        }
    }
</script>
