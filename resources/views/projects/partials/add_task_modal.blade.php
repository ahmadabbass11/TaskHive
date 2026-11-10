<div class="modal fade" id="addTaskModal-{{ $project->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('tasks.store', $project->id) }}" class="modal-content shadow-lg border-0">
            @csrf
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #007bff, #6610f2);">
                <h5 class="modal-title fw-bold"><i class="fas fa-tasks"></i> Add New Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body bg-light p-4">
                <!-- Task Title -->
                <label class="form-label fw-bold"><i class="fas fa-edit"></i> Task Title</label>
                <input type="text" name="title" class="form-control mb-3 shadow-sm" placeholder="Enter task title"
                    required>

                <!-- Task Description -->
                <label class="form-label fw-bold"><i class="fas fa-file-alt"></i> Task Description</label>
                <textarea name="description" class="form-control mb-3 shadow-sm" rows="3"
                    placeholder="Brief description of the task"></textarea>

                <!-- Status Selection -->
                <label class="form-label fw-bold"><i class="fas fa-list"></i> Status</label>
                <select name="status" class="form-select mb-3 shadow-sm" required>
                    <option value="To-Do" class="text-danger">To-Do</option>
                    <option value="In Progress" class="text-warning">In Progress</option>
                    <option value="Done" class="text-success">Done</option>
                </select>

                <!-- Assign Task -->
                <label class="form-label fw-bold"><i class="fas fa-user"></i> Assign To</label>
                <select name="assignee_id" class="form-select mb-3 shadow-sm">
                    <option value="">Unassigned</option>
                    @foreach($project->team->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                <!-- Due Date -->
                <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Due Date</label>
                <input type="date" name="due_date" class="form-control mb-3 shadow-sm">

                <!-- Task Priority -->
                <label class="form-label fw-bold"><i class="fas fa-exclamation-triangle"></i> Priority</label>
                <select name="priority" class="form-select shadow-sm">
                    <option value="Low" class="text-success">Low</option>
                    <option value="Medium" class="text-warning">Medium</option>
                    <option value="High" class="text-danger">High</option>
                </select>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-check"></i> Add Task
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Modal Customization */
    .modal-header {
        border-bottom: none;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
    }

    .modal-body {
        background: #f8f9fa;
        border-radius: 0 0 10px 10px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.2);
    }

    .btn-shadow {
        transition: all 0.3s ease-in-out;
    }

    .btn-shadow:hover {
        transform: scale(1.05);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-footer {
        border-top: none;
    }
</style>