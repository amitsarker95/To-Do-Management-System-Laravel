<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">To-Dos</h2>
            
            {{-- Theme Toggle --}}
            {!! Form::open(['route' => 'theme.toggle', 'method' => 'POST']) !!}
                <button class="px-3 py-1 rounded border">
                    Toggle Theme ({{ $theme }})
                </button>
            {!! Form::close() !!}
        </div>
    </x-slot>

    @php $isDark = $theme === 'dark'; @endphp

    <div class="py-6 {{ $isDark ? 'bg-gray-900 text-gray-100' : 'bg-white text-gray-900' }}">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 p-3 border rounded {{ $isDark ? 'bg-gray-800 border-gray-700' : 'bg-green-50 border-green-300' }}">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Add Task Form --}}
            <div class="p-6 mb-6 rounded shadow {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                <h3 class="text-lg font-semibold mb-3">Add Task</h3>

                {!! Form::open(['route' => 'todos.store', 'class' => 'space-y-3']) !!}
                    <div>
                        {!! Form::label('title', 'Title') !!} <span class="text-red-500">*</span>
                        {!! Form::text('title', old('title'), [
                            'class' => 'w-full px-3 py-2 rounded border ' . ($isDark ? 'bg-gray-900 border-gray-700' : '')
                        ]) !!}
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        {!! Form::label('description', 'Description (optional)') !!}
                        {!! Form::textarea('description', old('description'), [
                            'rows' => 3,
                            'class' => 'w-full px-3 py-2 rounded border ' . ($isDark ? 'bg-gray-900 border-gray-700' : '')
                        ]) !!}
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {!! Form::submit('Add Task', ['class' => 'px-4 py-2 bg-blue-500 text-white rounded pointer']) !!}
                {!! Form::close() !!}
            </div>

            {{-- Tasks Table --}}
            <div class="p-6 rounded shadow {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold">Tasks</h3>

                    {{-- Clear All --}}
                    {!! Form::open(['route' => 'todos.clear', 'method' => 'DELETE', 'onsubmit' => "return confirm('Clear all tasks?')"]) !!}
                        <button class="px-3 py-1 rounded border">Clear All</button>
                    {!! Form::close() !!}
                </div>

                @if ($todos->isEmpty())
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
                                        <td class="p-2 border">{{ $t->id }}</td>
                                        <td class="p-2 border">{{ $t->title }}</td>
                                        <td class="p-2 border">{{ $t->description }}</td>
                                        <td class="p-2 border">
                                            <span class="px-2 py-1 rounded text-sm {{ $t->status === 'Completed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                                {{ ucfirst($t->status) }}
                                            </span>
                                        </td>
                                        <td class="p-2 border">
                                            <div class="flex gap-2">
                                                {{-- Edit --}}
                                                <a href="{{ route('todos.edit', $t->id) }}" class="px-2 py-1 rounded border">Edit</a>
                                                {{-- Toggle --}}
                                                {!! Form::open(['route' => ['todos.toggle', $t->id], 'method' => 'PATCH']) !!}
                                                    <button class="px-2 py-1 rounded border">Toggle</button>
                                                {!! Form::close() !!}
                                                {{-- Delete --}}
                                                {!! Form::open(['route' => ['todos.destroy', $t->id], 'method' => 'DELETE', 'onsubmit' => "return confirm('Delete this task?')"]) !!}
                                                    <button class="px-2 py-1 rounded border">Delete</button>
                                                {!! Form::close() !!}
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
