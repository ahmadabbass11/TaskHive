<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Task Management</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary-bg: #4A154B;
            --secondary-bg: #350D36;
            --hover-bg: #3E4045;
            --accent-blue: #1264A3;
            --text-light: #D1D2D3;
            --text-white: #FFFFFF;
            --task-high: #E74C3C;
            --task-medium: #F39C12;
            --task-low: #2ECC71;
        }

        body {
            background: url('https://source.unsplash.com/random/1920x1080/?abstract,technology') no-repeat center center/cover;
            color: var(--text-light);
            font-family: "Helvetica Neue", Arial, sans-serif;
            animation: fadeIn 1s ease-in-out;
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, rgba(74, 21, 75, 0.9), rgba(18, 100, 163, 0.9));
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
            animation: slideInDown 0.8s ease-in-out;
            border-radius: 10px;
        }

        .header .logo img {
            height: 60px;
            transition: transform 0.3s ease-in-out;
        }

        .header .logo img:hover {
            transform: rotate(10deg) scale(1.1);
        }

        .nav-links a {
            color: var(--text-white);
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s, transform 0.3s;
        }

        .nav-links a:hover {
            color: #FFD700;
            transform: translateY(-2px) scale(1.1);
        }

        /* Profile Button */
        .profile-btn {
            background: var(--accent-blue);
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            transition: background 0.3s, transform 0.3s;
            font-weight: bold;
        }

        .profile-btn:hover {
            background: #0a4c84;
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background: var(--secondary-bg);
            padding: 20px;
            text-align: center;
            color: var(--text-white);
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slideInUp 0.8s ease-in-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header container-fluid">
        <div class="logo">
            <img src="{{ Vite::asset('resources/assets/taskhivelogo.png') }}" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="{{ route('main.homepage') }}">Admin Dashboard</a>
        </nav>
        <a href="{{ route('profile.edit') }}" class="profile-btn">
            <i class="fas fa-user"></i> Edit Profile
        </a>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            @yield('page-content')
        </div>
    </main>

</body>

</html>