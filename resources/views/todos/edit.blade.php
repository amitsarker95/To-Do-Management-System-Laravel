<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Edit Task #{{ $todo['id'] }}</h2>
            <form method="POST" action="{{ route('theme.toggle') }}">
                @csrf
                <button class="px-3 py-1 rounded border">Toggle Theme ({{ $theme }})</button>
            </form>
        </div>
    </x-slot>

    @php $isDark = $theme === 'dark'; @endphp

    <div class="py-6 {{ $isDark ? 'bg-gray-900 text-gray-100' : 'bg-white text-gray-900' }}">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 rounded shadow {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                {!! Form::model($todo, ['route' => ['todos.update', $todo->id], 'method' => 'PUT', 'class' => 'space-y-4']) !!}

                    <!-- Title -->
                    <div>
                        {!! Form::label('title', 'Title', ['class' => 'block mb-1 font-medium']) !!} <span class="text-red-500">*</span>
                        {!! Form::text('title', null, [
                            'class' => 'w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-500 ' .
                                      ($isDark ? 'bg-gray-900 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900')
                        ]) !!}
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        {!! Form::label('description', 'Description (optional)', ['class' => 'block mb-1 font-medium']) !!}
                        {!! Form::textarea('description', null, [
                            'rows' => 3,
                            'class' => 'w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-500 ' .
                                      ($isDark ? 'bg-gray-900 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900')
                        ]) !!}
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        {!! Form::label('status', 'Status', ['class' => 'block mb-1 font-medium']) !!} <span class="text-red-500">*</span>
                        {!! Form::select('status', ['Pending' => 'Pending', 'Completed' => 'Completed'], null, [
                            'class' => 'w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-500 ' .
                                      ($isDark ? 'bg-gray-900 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900')
                        ]) !!}
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        {!! Form::submit('Update', [
                            'class' => 'px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition',
                            'style' => 'margin-top: 10px;'
                        ]) !!}
                        <a href="{{ route('todos.index') }}"
                           class="px-4 py-2 rounded border hover:bg-gray-100 transition {{ $isDark ? 'hover:bg-gray-700 text-gray-100' : '' }}"
                           style="margin-top: 10px; margin-left: 5px;">
                            Cancel
                        </a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</x-app-layout>
