$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function loadTasks(page = 1) {
        $('#loading-spinner').show();

        let status = $('#status-filter').val();
        let search = $('#search').val().trim();
        let url = `/api/tasks?page=${page}&status=${status}&search=${search}`;

        $.get(url, function (response) {
            $('#loading-spinner').hide();
            let rows = '';

            if (response.data.length === 0) {
                rows = `<tr><td colspan="4" class="text-center text-muted">No tasks available.</td></tr>`;
            } else {
                response.data.forEach(task => {
                    let statusBtnClass = task.status === 'completed' ? 'btn-success' : 'btn-warning';
                    let statusText = task.status === 'completed' ? 'Completed' : 'Mark as Complete';
                    let statusDisabled = task.status === 'completed' ? 'disabled' : '';

                    rows += `<tr>
                        <td>${task.title}</td>
                        <td>${task.description}</td>
                        <td>
                            <button class="btn ${statusBtnClass} btn-sm" onclick="toggleStatus(${task.id})" ${statusDisabled}>
                                ${statusText}
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="editTask(${task.id}, '${task.title}', '${task.description}')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
                        </td>
                    </tr>`;
                });
            }

            $('#task-list').html(rows);
            setupPagination(response);
        });
    }

    function setupPagination(response) {
        let pagination = $('#pagination');
        pagination.html('');

        for (let i = 1; i <= response.last_page; i++) {
            let activeClass = response.current_page === i ? 'active' : '';
            pagination.append(`<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="loadTasks(${i})">${i}</a></li>`);
        }
    }

    $('#task-form').submit(function (e) {
        e.preventDefault();

        let id = $('#task-id').val();
        let title = $('#title').val().trim();
        let description = $('#description').val().trim();
        let url = id ? `/api/tasks/${id}` : '/api/tasks';
        let type = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: { title: title, description: description, _token: '{{ csrf_token() }}' },
            beforeSend: function () {
                $('#loading-spinner').show();
            },
            success: function (response) {
                $('#message-box').html(`<div class="alert alert-success">${response.message}</div>`);
                setTimeout(() => $('#message-box').html(''), 5000);
                $('#task-id').val('');
                $('#title').val('');
                $('#description').val('');
                $('#cancel-edit').hide();
                loadTasks();
            },
            complete: function () {
                $('#loading-spinner').hide();
            }
        });
    });

    window.editTask = function (id, title, description) {
        $('#task-id').val(id);
        $('#title').val(title);
        $('#description').val(description);
        $('#cancel-edit').show();
    };

    window.toggleStatus = function (id) {
        $.ajax({
            url: `/api/tasks/${id}/toggle-status`,
            type: 'PATCH',
            beforeSend: function () {
                $('#loading-spinner').show();
            },
            success: function () { loadTasks(); },
            complete: function () {
                $('#loading-spinner').hide();
            }
        });
    };

    window.deleteTask = function (id) {
        if (!confirm('Are you sure you want to delete this task?')) return;

        $.ajax({
            url: `/api/tasks/${id}`,
            type: 'DELETE',
            beforeSend: function () {
                $('#loading-spinner').show();
            },
            success: function (response) {
                $('#message-box').html(`<div class="alert alert-danger">${response.message}</div>`);
                setTimeout(() => $('#message-box').html(''), 5000);
                loadTasks();
            },
            complete: function () {
                $('#loading-spinner').hide();
            }
        });
    };

    $('#cancel-edit').click(function () {
        $('#task-id').val('');
        $('#title').val('');
        $('#description').val('');
        $('#cancel-edit').hide();
    });

    $('#status-filter, #search').on('input', function () {
        loadTasks();
    });

    loadTasks();
});
