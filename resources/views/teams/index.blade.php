@extends('layouts.website')

@section('page-content')
    <main class="container mt-5">
        <div class="task-container p-4 shadow-lg rounded-3">
            <h2 class="text-center team-title">
                <i class="fas fa-users"></i> Team Management
            </h2>

            {{-- Section 1: List of Teams --}}
            <h3 class="mt-4 text-primary"><i class="fas fa-layer-group"></i> Your Teams</h3>
            <input type="text" id="searchTeam" class="form-control mb-3 shadow-sm" placeholder="Search teams...">

            <div class="row mt-3">
                @foreach ($teams as $team)
                    <div class="col-md-6">
                        <div class="card team-card shadow-lg border-0">
                            <div class="card-header text-white d-flex justify-content-between align-items-center"
                                style="background: linear-gradient(135deg, #3498DB, #2C3E50);">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i> {{ $team->name }}
                                </h5>
                                <span class="badge bg-light text-dark">Admin: {{ $team->teamAdmin->name }}</span>
                            </div>
                            <div class="card-body bg-light">
                                <p class="text-muted">{{ $team->description }}</p>

                                {{-- Add Members Form --}}
                                <form action="{{ route('teams.addMember', $team->id) }}" method="POST"
                                    class="d-flex align-items-center">
                                    @csrf
                                    <select name="user_id" class="form-select me-2 shadow-sm">
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                        <i class="fas fa-user-plus"></i> Add
                                    </button>
                                </form>

                                {{-- List of Team Members --}}
                                <h5 class="mt-3"><i class="fas fa-user-friends"></i> Members:</h5>
                                <ul class="list-group">
                                    @foreach ($team->users as $member)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $member->name }}
                                            <div>
                                                @if (Auth::id() == $team->team_admin && $member->id !== $team->team_admin)
                                                    {{-- Promote/Demote Admin --}}
                                                    <form
                                                        action="{{ route('teams.updateRole', ['team' => $team->id, 'user' => $member->id]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-warning btn-sm shadow-sm">
                                                            <i class="fas fa-user-shield"></i>
                                                            {{ $member->role == 'admin' ? 'Demote' : 'Promote' }}
                                                        </button>
                                                    </form>

                                                    {{-- Remove Member --}}
                                                    <form
                                                        action="{{ route('teams.removeMember', ['team' => $team->id, 'user' => $member->id]) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to remove this member?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                                            <i class="fas fa-user-minus"></i> Remove
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Delete Team Button --}}
                                @if (Auth::id() == $team->team_admin)
                                    <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="mt-3"
                                        onsubmit="return confirm('Are you sure you want to delete this team?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 shadow-sm">
                                            <i class="fas fa-trash"></i> Delete Team
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Section 2: Create a New Team --}}
            <h3 class="mt-4 text-primary"><i class="fas fa-plus-circle"></i> Create a New Team</h3>
            <form action="{{ route('teams.store') }}" method="POST" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="teamName" class="form-label"><i class="fas fa-users"></i> Team Name</label>
                    <input type="text" id="teamName" name="name" class="form-control shadow-sm" required>
                </div>
                <div class="mb-3">
                    <label for="teamDescription" class="form-label"><i class="fas fa-file-alt"></i> Description</label>
                    <textarea id="teamDescription" name="description" class="form-control shadow-sm" rows="3"
                        required></textarea>
                </div>
                <div class="mb-3">
                    <label for="teamMembers" class="form-label"><i class="fas fa-user-plus"></i> Select Members</label>
                    <select id="teamMembers" name="members[]" class="form-control shadow-sm" multiple required>
                        @foreach ($allUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-plus-circle"></i> Create Team
                </button>
            </form>
        </div>
    </main>

    <script>
        // Live search for teams
        document.getElementById('searchTeam').addEventListener('input', function () {
            let searchQuery = this.value.toLowerCase();
            document.querySelectorAll('.team-card h5').forEach(function (teamCard) {
                let teamName = teamCard.textContent.toLowerCase();
                teamCard.closest('.col-md-6').style.display = teamName.includes(searchQuery) ? 'block' : 'none';
            });
        });
    </script>

    <style>
        /* Team Management Container */
        .task-container {
            background: linear-gradient(135deg, #2C3E50, #34495E);
            border-radius: 12px;
            padding: 30px;
            text-align: left;
            color: white;
        }

        /* Team Title */
        .team-title {
            font-size: 2rem;
            background: linear-gradient(90deg, #1ABC9C, #3498DB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 0.3);
        }

        /* Team Card */
        .team-card {
            border-radius: 12px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Buttons */
        .btn-shadow {
            transition: all 0.3s ease-in-out;
        }

        .btn-shadow:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>

@endsection