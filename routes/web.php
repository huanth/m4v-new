<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BanController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GuildController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard (Super Admin only)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'superadmin', 'check.ban']);

// Profile (All authenticated users)
Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware(['auth', 'check.ban']);
Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update')->middleware(['auth', 'check.ban']);
Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove')->middleware(['auth', 'check.ban']);

    // Public User Profile (All users can view)
    Route::get('/user/{user}', [ProfileController::class, 'show'])->name('user.profile');
    
    // Public Ban History (All users can view)
    Route::get('/user/{user}/ban-history', [BanController::class, 'publicHistory'])->name('user.ban.history');
    
    // User Notifications (Authenticated users only)
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications')->middleware(['auth', 'check.ban']);
    Route::post('/notifications/clear', [ProfileController::class, 'clearNotification'])->name('notifications.clear')->middleware(['auth', 'check.ban']);
    Route::post('/notifications/clear-all', [ProfileController::class, 'clearAllNotifications'])->name('notifications.clear-all')->middleware(['auth', 'check.ban']);

    // Chat System (Authenticated users only)
    Route::get('/chat', [MessageController::class, 'index'])->name('chat.index')->middleware(['auth', 'check.ban']);
    Route::get('/chat/{userId}', [MessageController::class, 'show'])->name('chat.show')->middleware(['auth', 'check.ban']);
    Route::post('/chat/send', [MessageController::class, 'store'])->name('chat.send')->middleware(['auth', 'check.ban']);
    Route::get('/chat/{userId}/messages', [MessageController::class, 'getMessages'])->name('chat.messages')->middleware(['auth', 'check.ban']);
    Route::post('/chat/{userId}/read', [MessageController::class, 'markAsRead'])->name('chat.read')->middleware(['auth', 'check.ban']);
    Route::get('/chat/unread/count', [MessageController::class, 'getUnreadCount'])->name('chat.unread.count')->middleware(['auth', 'check.ban']);
    Route::get('/chat/unread/conversations-count', [MessageController::class, 'getUnreadConversationsCount'])->name('chat.unread.conversations-count')->middleware(['auth', 'check.ban']);

    // Guild System - Public viewing
    Route::get('/guilds', [GuildController::class, 'index'])->name('guilds.index')->middleware(['auth', 'check.ban']);
    Route::get('/{id}', [GuildController::class, 'show'])->name('guilds.show')->where('id', '[0-9]+');
    Route::get('/{id}/members', [GuildController::class, 'showMembers'])->name('guilds.members')->where('id', '[0-9]+');
    
    // Guild System (Authenticated users only)
    Route::get('/guilds/create', [GuildController::class, 'create'])->name('guilds.create')->middleware(['auth', 'check.ban']);
    Route::post('/guilds', [GuildController::class, 'store'])->name('guilds.store')->middleware(['auth', 'check.ban']);
    Route::get('/{id}/manage', [GuildController::class, 'manage'])->name('guilds.manage')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/join', [GuildController::class, 'join'])->name('guilds.join')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/leave', [GuildController::class, 'leave'])->name('guilds.leave')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/member/role', [GuildController::class, 'updateMemberRole'])->name('guilds.member.role')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/category', [GuildController::class, 'createCategory'])->name('guilds.category.create')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::put('/{id}/category/{categoryId}', [GuildController::class, 'updateCategory'])->name('guilds.category.update')->where('id', '[0-9]+')->where('categoryId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::delete('/{id}/category/{categoryId}', [GuildController::class, 'deleteCategory'])->name('guilds.category.delete')->where('id', '[0-9]+')->where('categoryId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/banner', [GuildController::class, 'updateBanner'])->name('guilds.banner.update')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/announcement', [GuildController::class, 'updateAnnouncement'])->name('guilds.announcement.update')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::get('/{id}/posts/create', [GuildController::class, 'createPost'])->name('guilds.posts.create')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/posts', [GuildController::class, 'storePost'])->name('guilds.posts.store')->where('id', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::get('/{id}/posts/{postId}', [GuildController::class, 'showPost'])->name('guilds.posts.show')->where('id', '[0-9]+')->where('postId', '[0-9]+');
    Route::get('/{id}/posts/{postId}/edit', [GuildController::class, 'editPost'])->name('guilds.posts.edit')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::put('/{id}/posts/{postId}', [GuildController::class, 'updatePost'])->name('guilds.posts.update')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::delete('/{id}/posts/{postId}', [GuildController::class, 'deletePost'])->name('guilds.posts.delete')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/posts/{postId}/pin', [GuildController::class, 'togglePinPost'])->name('guilds.posts.pin')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/posts/{postId}/lock', [GuildController::class, 'toggleLockPost'])->name('guilds.posts.lock')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/posts/{postId}/like', [GuildController::class, 'toggleLikePost'])->name('guilds.posts.like')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/posts/{postId}/comments', [GuildController::class, 'addComment'])->name('guilds.posts.comments.store')->where('id', '[0-9]+')->where('postId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::put('/{id}/posts/{postId}/comments/{commentId}', [GuildController::class, 'editComment'])->name('guilds.posts.comments.update')->where('id', '[0-9]+')->where('postId', '[0-9]+')->where('commentId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::delete('/{id}/posts/{postId}/comments/{commentId}', [GuildController::class, 'deleteComment'])->name('guilds.posts.comments.delete')->where('id', '[0-9]+')->where('postId', '[0-9]+')->where('commentId', '[0-9]+')->middleware(['auth', 'check.ban']);
    Route::post('/{id}/posts/{postId}/comments/{commentId}/like', [GuildController::class, 'toggleLikeComment'])->name('guilds.posts.comments.like')->where('id', '[0-9]+')->where('postId', '[0-9]+')->where('commentId', '[0-9]+')->middleware(['auth', 'check.ban']);

// User Profile Routes
Route::middleware(['auth', 'check.ban'])->group(function () {
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->where('id', '[0-9]+');
});

// Ban Management Routes (for users with ban permissions)
Route::middleware(['auth', 'check.ban'])->group(function () {
    Route::get('/users/{user}/ban', [BanController::class, 'create'])->name('admin.ban.create');
    Route::post('/users/{user}/ban', [BanController::class, 'store'])->name('admin.ban.store');
    Route::post('/users/{user}/unban', [BanController::class, 'unban'])->name('admin.ban.unban');
    Route::get('/users/{user}/ban-history', [BanController::class, 'history'])->name('admin.ban.history');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin', 'check.ban'])->group(function () {
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    
    // Ban Management (Admin only)
    Route::get('/bans', [BanController::class, 'index'])->name('admin.bans.index');
});

// Notification Routes
Route::middleware(['auth', 'check.ban'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
});
