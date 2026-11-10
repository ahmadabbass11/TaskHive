@extends('layouts.website')

@section('page-content')

    <main class="container mt-4">
        <div class="task-container p-4 shadow-lg rounded-3 bg-white">
            <h2 class="text-center text-uppercase fw-bold mb-4 text-primary">
                <i class="fas fa-tasks"></i> Admin Task Management
            </h2>

            <!-- Team Management Section -->
            <h3 class="mt-4 text-secondary fw-bold"><i class="fas fa-users"></i> Team Management Tasks</h3>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="task-card shadow-sm rounded-3">
                        <a href="{{ route('teams.index') }}" class="task-link">
                            <div class="task-content">
                                <h4 class="fw-bold"><i class="fas fa-user-plus"></i> Add Team Members/Create Teams</h4>
                                <p class="text-muted">Review and approve pending team member requests.</p>
                                <span class="task-priority task-medium">Medium Priority</span>
                            </div>
                            <div class="task-icon text-info">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Project Management Section -->
            <h3 class="mt-4 text-secondary fw-bold"><i class="fas fa-project-diagram"></i> Project Tasks</h3>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="task-card shadow-sm rounded-3">
                        <a href="{{ route('projects.index') }}" class="task-link">
                            <div class="task-content">
                                <h4 class="fw-bold"><i class="fas fa-tasks"></i> Assign Project Tasks/Track Progress</h4>
                                <p class="text-muted">Distribute tasks to team members efficiently.</p>
                                <span class="task-priority task-high">High Priority</span>
                            </div>
                            <div class="task-icon text-danger">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Task Container Styling */
        .task-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        /* Task Card Styling */
        .task-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        }

        /* Task Content */
        .task-content h4 {
            margin-bottom: 5px;
            color: #343a40;
        }

        .task-content p {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .task-priority {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        .task-medium {
            background: #ffeb3b;
            color: #856404;
        }

        .task-high {
            background: #ff4b5c;
            color: white;
        }

        /* Task Icon */
        .task-icon i {
            font-size: 1.8rem;
        }

        /* Task Link */
        .task-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
    </style>

@endsection