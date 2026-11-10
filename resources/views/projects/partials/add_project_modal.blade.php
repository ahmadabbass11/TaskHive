<div class="modal fade" id="addProjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('projects.store') }}" class="modal-content shadow-lg border-0">
            @csrf
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #28a745, #218838);">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle"></i> Add New Project</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body bg-light p-4">
                <!-- Select Team -->
                <label class="form-label fw-bold"><i class="fas fa-users"></i> Select Team</label>
                <select name="team_id" class="form-select mb-3 shadow-sm" required>
                    <option value="" selected disabled>Choose a team...</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>

                <!-- Project Name -->
                <label class="form-label fw-bold"><i class="fas fa-folder"></i> Project Name</label>
                <input type="text" name="name" class="form-control mb-3 shadow-sm" placeholder="Enter project name"
                    required>

                <!-- Project Description -->
                <label class="form-label fw-bold"><i class="fas fa-file-alt"></i> Project Description</label>
                <textarea name="description" class="form-control mb-3 shadow-sm" rows="3"
                    placeholder="Brief description of the project"></textarea>

                <!-- Deadline -->
                <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Deadline</label>
                <input type="date" name="deadline" class="form-control mb-3 shadow-sm" required>

                <!-- Priority -->
                <label class="form-label fw-bold"><i class="fas fa-exclamation-circle"></i> Priority</label>
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
                <button type="submit" class="btn btn-success shadow-sm">
                    <i class="fas fa-check"></i> Create Project
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
        border-color: #28a745;
        box-shadow: 0px 0px 10px rgba(40, 167, 69, 0.2);
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