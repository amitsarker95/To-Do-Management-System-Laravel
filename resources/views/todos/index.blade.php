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

            {{-- Add Task Form (AJAX) --}}
            <div class="p-6 mb-6 rounded shadow {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                <h3 class="text-lg font-semibold mb-3">Add Task</h3>
                {!! Form::open(['id' => 'addTaskForm', 'class' => 'space-y-3']) !!}
                    <div>
                        {!! Form::label('title', 'Title') !!} <span class="text-red-500">*</span>
                        {!! Form::text('title', null, [
                            'class' => 'w-full px-3 py-2 rounded border ' . ($isDark ? 'bg-gray-900 border-gray-700' : '')
                        ]) !!}
                        <p class="text-red-500 text-sm mt-1" id="error-title"></p>
                    </div>
                    <div>
                        {!! Form::label('description', 'Description (optional)') !!}
                        {!! Form::textarea('description', null, [
                            'rows' => 3,
                            'class' => 'w-full px-3 py-2 rounded border ' . ($isDark ? 'bg-gray-900 border-gray-700' : '')
                        ]) !!}
                        <p class="text-red-500 text-sm mt-1" id="error-description"></p>
                    </div>
                    {!! Form::submit('Add Task', ['class' => 'px-4 py-2 bg-blue-500 text-white rounded pointer']) !!}
                {!! Form::close() !!}
                <div id="success-message" class="text-green-600 mt-2"></div>
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

                <div class="overflow-x-auto">
                    <table id="tasksTable" class="min-w-full border">
                        <thead>
                            <tr class="{{ $isDark ? 'bg-gray-700' : 'bg-gray-100' }}">
                                <th class="p-2 border">ID</th>
                                <th class="p-2 border">Title</th>
                                <th class="p-2 border">Description</th>
                                <th class="p-2 border">Status</th>
                                <th class="p-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tasksBody"></tbody>
                    </table>
                    <div id="pagination" class="flex justify-center mt-4"></div>
                    <div id="noTasksMsg" class="text-center text-gray-500 mt-4" style="display:none;">No tasks yet.</div>
                </div>
            </div>
        </div>
    </div>

    
<!-- AJAX Scripts -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Add Task
$('#addTaskForm').on('submit', function(e) {
    e.preventDefault();
    $('#error-title').text('');
    $('#error-description').text('');
    $('#success-message').text('');
    $.ajax({
        url: '{{ route('ajax.todos.store') }}',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            $('#addTaskForm')[0].reset();
            loadTasks();
            $('#success-message').text(response.message);
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                if(errors.title) $('#error-title').text(errors.title[0]);
                if(errors.description) $('#error-description').text(errors.description[0]);
            }
        }
    });
});

// Load Tasks
function loadTasks(page = 1) {
    $.get('/ajax/todos/paginate?page=' + page, function(data) {
        let rows = '';
        data.tasks.forEach(function(t) {
            rows += `<tr>
                <td class="p-2 border">${t.id}</td>
                <td class="p-2 border">${t.title}</td>
                <td class="p-2 border">${t.description ?? ''}</td>
                <td class="p-2 border">${t.status}</td>
                <td class="p-2 border">
                    <button class="toggle-btn px-2 py-1 rounded border" data-id="${t.id}">Toggle</button>
                    <a href="/todos/${t.id}/edit" class="px-2 py-1 rounded border bg-yellow-400 text-black">Edit</a>
                    <button class="delete-btn px-2 py-1 rounded border" data-id="${t.id}">Delete</button>
                </td>
            </tr>`;
        });
        $('#tasksBody').html(rows);

        // Pagination controls
        let pagination = '';
        if (data.pagination && data.pagination.last_page > 1) {
            for (let i = 1; i <= data.pagination.last_page; i++) {
                pagination += `<button class="page-btn px-2 py-1 mx-1 rounded border ${i === data.pagination.current_page ? 'bg-blue-500 text-white' : ''}" data-page="${i}">${i}</button>`;
            }
        }
        $('#pagination').html(pagination);
    });
}
loadTasks();

// Pagination 
$('#pagination').on('click', '.page-btn', function() {
    let page = $(this).data('page');
    loadTasks(page);
});

// Delete Task 
$('#tasksBody').on('click', '.delete-btn', function() {
    if(confirm('Delete this task?')) {
        let id = $(this).data('id');
        $.ajax({
            url: '/ajax/todos/' + id,
            method: 'DELETE',
            success: function(response) {
                loadTasks();
                $('#success-message').text(response.message);
            }
        });
    }
});

// Toggle Task 
$('#tasksBody').on('click', '.toggle-btn', function() {
    let id = $(this).data('id');
    $.post('/ajax/todos/' + id + '/toggle', {}, function(response) {
        loadTasks();
        $('#success-message').text(response.message);
    });
});

// Edit Task 
$('#tasksBody').on('click', '.edit-btn', function() {
    let id = $(this).data('id');
    let currentTitle = $(this).closest('tr').find('td:nth-child(2)').text();
    let currentDesc = $(this).closest('tr').find('td:nth-child(3)').text();
    let currentStatus = $(this).closest('tr').find('td:nth-child(4)').text();

    let newTitle = prompt('Edit Title:', currentTitle);
    if(newTitle === null) return;
    let newDesc = prompt('Edit Description:', currentDesc);
    if(newDesc === null) return;
    let newStatus = prompt('Edit Status (Pending/Completed):', currentStatus);
    if(newStatus === null) return;

    $.ajax({
        url: '/ajax/todos/' + id,
        method: 'PUT',
        data: {
            title: newTitle,
            description: newDesc,
            status: newStatus
        },
        success: function(response) {
            loadTasks();
            $('#success-message').text(response.message);
        },
        error: function(xhr) {
            alert('Update failed. Please check your input.');
        }
    });
});

</script>
</x-app-layout>
