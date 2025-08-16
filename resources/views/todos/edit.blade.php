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
                <form method="POST" action="{{ route('todos.update', $todo['id']) }}" class="space-y-4">
                    @csrf 
                    @method('PUT')

                    <!-- Title -->
                    <div>
                        <label class="block mb-1 font-medium">Title <span class="text-red-500">*</span></label>
                        <input name="title" value="{{ old('title', $todo['title']) }}"
                            class="w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $isDark ? 'bg-gray-900 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900' }}">
                        @error('title') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block mb-1 font-medium">Description (optional)</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $isDark ? 'bg-gray-900 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900' }}">{{ old('description', $todo['description']) }}</textarea>
                        @error('description') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block mb-1 font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $isDark ? 'bg-gray-900 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900' }}">
                            <option value="Pending" {{ old('status', $todo['status']) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Completed" {{ old('status', $todo['status']) == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" style="margin-top: 10px; background-color: blue;" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                            Update
                        </button>
                        <a style="margin-top: 10px; background-color: blue; margin-left: 5px text-color: #fff;" href="{{ route('todos.index') }}" 
                           class="px-4 py-2 rounded border hover:bg-gray-100 transition {{ $isDark ? 'hover:bg-gray-700' : '' }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
