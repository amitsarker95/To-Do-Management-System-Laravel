<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">To-Dos (Session)</h2>
            <form method="POST" action="{{ route('theme.toggle') }}">
                @csrf
                <button class="px-3 py-1 rounded border">Toggle Theme ({{ $theme }})</button>
            </form>
        </div>
    </x-slot>

    @php $isDark = $theme === 'dark'; @endphp

    <div class="py-6 {{ $isDark ? 'bg-gray-900 text-gray-100' : 'bg-white text-gray-900' }}">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 border rounded {{ $isDark ? 'bg-gray-800 border-gray-700' : 'bg-green-50 border-green-300' }}">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 mb-6 rounded shadow {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                <h3 class="text-lg font-semibold mb-3">Add Task</h3>
                <form method="POST" action="{{ route('todos.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block mb-1">Title <span class="text-red-500">*</span></label>
                        <input name="title" value="{{ old('title') }}" class="w-full px-3 py-2 rounded border {{ $isDark ? 'bg-gray-900 border-gray-700' : '' }}">
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Description (optional)</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 rounded border {{ $isDark ? 'bg-gray-900 border-gray-700' : '' }}">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Add this button -->
                    <button type="submit" style="background-color: blue" class="px-4 py-2 bg-blue-500 text-white rounded">
                        Add Task
                    </button>
                </form>
            </div>

            <div class="p-6 rounded shadow {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold">Tasks</h3>
                    <form method="POST" action="{{ route('todos.clear') }}" onsubmit="return confirm('Clear all tasks?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 rounded border">Clear All</button>
                    </form>
                </div>

                @if (count($todos) === 0)
                    <p>No tasks yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="{{ $isDark ? 'bg-gray-700' : 'bg-gray-100' }}">
                                    <th class="p-2 border">ID</th>
                                    <th class="p-2 border">Title</th>
                                    <th class="p-2 border">Description</th>
                                    <th class="p-2 border">Status</th>
                                    <th class="p-2 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todos as $t)
                                    <tr>
                                        <td class="p-2 border">{{ $t['id'] }}</td>
                                        <td class="p-2 border">{{ $t['title'] }}</td>
                                        <td class="p-2 border">{{ $t['description'] }}</td>
                                        <td class="p-2 border">
                                            <span class="px-2 py-1 rounded text-sm {{ $t['status']==='done' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                                {{ ucfirst($t['status']) }}
                                            </span>
                                        </td>
                                        <td class="p-2 border">
                                            <div class="flex gap-2">
                                                <a href="{{ route('todos.edit', $t['id']) }}" class="px-2 py-1 rounded border">Edit</a>
                                                <form method="POST" action="{{ route('todos.toggle', $t['id']) }}">
                                                    @csrf @method('PATCH')
                                                    <button class="px-2 py-1 rounded border">Toggle</button>
                                                </form>
                                                <form method="POST" action="{{ route('todos.destroy', $t['id']) }}" onsubmit="return confirm('Delete this task?')">
                                                    @csrf @method('DELETE')
                                                    <button class="px-2 py-1 rounded border">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
