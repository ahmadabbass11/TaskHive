<?php

use App\Http\Controllers\ProfileController;
use App\Models\Message;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CallController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TeamController;
use App\Events\MessageSent;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserProjectController;
use App\Http\Controllers\FileController;



Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/test-broadcast', function () {
    $m = new Message;
    event(new MessageSent($m));
    return 'Event broadcasted!';
});



/*Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::get('/dashboard', function () {
    return redirect()->route('main.homepage');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/generate-token', [CallController::class, 'generateToken'])->middleware(middleware: 'auth');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    Route::get('/home', [ConversationController::class, 'getConversationsAndChannels'])->name('main.homepage');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


    Route::get('/messages/{conversationId}', [ConversationController::class, 'getMessagesForChannel']);
    Route::get('/user/{userId}', [ConversationController::class, 'getUserDetails']);
    Route::get('/search-users', [UserController::class, 'search'])->name('users.search');
    Route::get('/admin', [UserController::class, 'getAdminPage'])->name('admin.index');

    Route::get('/user-projects', [UserProjectController::class, 'index'])->name('user.projects');
    Route::get('/user-projects/{project}', [UserProjectController::class, 'show'])->name('user.projects.show');
    Route::post('/tasks/{task}/upload', [FileController::class, 'store'])->name('task.file.upload');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');


    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams/create', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/add-member', [TeamController::class, 'addMember'])->name('teams.addMember');
    Route::delete('/teams/{team}/remove-member/{user}', [TeamController::class, 'removeMember'])->name('teams.removeMember');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');

    Route::put('/teams/{team}/update-role/{user}', [TeamController::class, 'updateRole'])->name('teams.updateRole');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');


    Route::get('/video-call', function () {
        return view('main.video-call');
    });

    // Route to create/retrieve a conversation
    Route::post('/create-conversation', [ConversationController::class, 'createConversation'])
        ->name('conversation.create');

    Route::prefix('messages')->group(function () {
        Route::post('/send', [MessageController::class, 'sendMessage'])->name('messages.send');
        Route::get('/{channelId}/messages', [MessageController::class, 'getMessages']);
    });



});


require __DIR__ . '/auth.php';
