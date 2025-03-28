<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Task Manager</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        #loading-spinner {
            display: none;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">

            @auth
            <div class="d-flex align-items-center">
                <span class="text-dark fw-bold me-3">👤 {{ Auth::user()->name }}</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
            @endauth

            <h2 class="text-center flex-grow-1">Task Manager</h2>
        </div>

        <div id="message-box"></div>

        <!-- Task Form -->
        <div class="card p-4 shadow">
            <form id="task-form">
                @csrf
                <input type="hidden" id="task-id">
                <div class="mb-3">
                    <label class="form-label">Task Title</label>
                    <input type="text" id="title" class="form-control" placeholder="Enter task title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="description" class="form-control" placeholder="Task description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Task</button>
                <button type="button" class="btn btn-secondary" id="cancel-edit" style="display: none;">Cancel</button>
            </form>
        </div>

        <!-- Filter & Search -->
        <div class="mt-4">
            <input type="text" id="search" class="form-control" placeholder="Search tasks...">
            <select id="status-filter" class="form-select mt-2">
                <option value="all">All Tasks</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <!-- Task List -->
        <div class="card p-4 mt-4 shadow">
            <h4 class="mb-3">Task List</h4>
            <div id="loading-spinner">
                <span class="spinner-border text-primary"></span> Loading...
            </div>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="task-list"></tbody>
            </table>
            <nav>
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script -->
    <script src="/js/main.js"></script>


</body>
</html>
