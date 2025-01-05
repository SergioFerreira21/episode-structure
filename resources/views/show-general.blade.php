<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $pageName }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 pt-4">
        <h5 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $relSectionName }}
        </h5>
    </div>
    <x-general-table
        :rows="$rows"
        :nextRelName="$nextRelName"
        :nextRelCounter="$nextRelCounter"
        :showRouteName="$showRouteName"
    />
</x-app-layout>
