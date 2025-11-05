<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Leader') {
    header("Location: login.html");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITES Dashboard</title>
    <!-- Load Tailwind CSS for utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Lucide Icons for UI elements -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Link to external stylesheet -->
    <link rel="stylesheet" href="Leader.css">
    <!-- Configure custom colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Custom Purple/Magenta Palette
                        'sites-dark': '#6A1B9A', // Deep Violet/Purple 
                        'sites-accent': '#BB33A0', // Vibrant Magenta for main accent (buttons, info card)
                        'sites-medium': '#9C27B0', // Medium Purple for secondary use (hover, borders)
                        'sites-body-bg': '#7B1FA2', // Dark, Rich Purple/Magenta for the main background
                        'sites-light': '#E0D0E7', // Light Lilac (used for gradient blend)
                        'modal-bg': '#f3e8f7', // Very light purple for new modal background
                        
                        'gradient-top': '#A319A3', 
                        'gradient-bottom': '#5A1AAA',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans">

    <!-- Main Application Container -->
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar (Desktop View) -->
        <aside class="sidebar-desktop w-64 main-gradient text-white flex flex-col shadow-2xl">
            
            <div class="p-4 border-b border-white/20 pt-8">
                <div class="flex items-center space-x-3">
                    <div id="sidebar-initial-avatar" class="w-10 h-10 bg-sites-accent rounded-full flex items-center justify-center text-lg font-bold text-white shadow-lg">U</div>
                    <div class="truncate">
                        <p id="sidebar-user-name" class="font-bold text-sm truncate">Loading...</p>
                        <p id="sidebar-user-role" class="text-xs text-white/70">Admin</p>
                    </div>
                </div>
                <p id="sidebar-user-id" class="text-xs text-white/50 mt-2 truncate">ID: Loading...</p>
            </div>

            <nav class="flex-1 mt-4 space-y-2" id="sidebar-nav">
                <a href="#" data-page="dashboard" class="sidebar-item-active flex items-center py-3 pl-4 pr-10 font-bold nav-link">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>Dashboard
                </a>
                <a href="#" data-page="assign" class="flex items-center py-3 px-4 text-sites-light font-semibold hover:bg-white/10 rounded-lg transition-colors nav-link mx-3">
                    <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>Assign Tasks
                </a>
                <a href="#" data-page="members" class="flex items-center py-3 px-4 text-sites-light font-semibold hover:bg-white/10 rounded-lg transition-colors nav-link mx-3">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i>Members
                </a>
                <a href="#" data-page="profile" class="flex items-center py-3 px-4 text-sites-light font-semibold hover:bg-white/10 rounded-lg transition-colors nav-link mx-3">
                    <i data-lucide="user" class="w-5 h-5 mr-3"></i>Profile Information
                </a>
            </nav>

            <div class="p-4 border-t border-white/20">
                <a href="logout.php" class="w-full flex items-center py-3 px-4 text-sites-light font-semibold text-left hover:bg-white/10 rounded-lg transition-colors">
                <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>Logout
                </a>

            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-gray-100 text-gray-800">
            
            <header class="h-20 flex items-center justify-between px-8 shadow-sm border-b bg-white">
                <h1 id="header-title" class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <div>
                    <img src="SITES LOGO.png" alt="SITES Logo" class="h-24 w-60">
                </div>
            </header>

            <main class="flex-1 p-8">
                
                <section id="dashboard-content" class="content-page active">
                    <div class="mb-8">
                        <h2 id="welcome-message" class="text-4xl font-extrabold text-gray-800">Welcome back, User!</h2>
                        <p class="text-lg text-gray-500">The only way to do great work is to love what you do.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold mb-6">Task Overview</h3>
                            <div id="dashboard-task-list" class="space-y-6"></div>
                        </div>
                        <div class="lg:col-span-1 space-y-8">
                            <button onclick="showMeetingModal()" class="w-full text-left bg-white text-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-sites-accent hover:shadow-2xl hover:-translate-y-1 transition-all">
                                <h3 class="text-xl font-bold mb-2">Meeting</h3>
                                <p id="meeting-date" class="text-gray-500">Loading date...</p>
                                <p id="meeting-time" class="text-3xl font-extrabold my-2">9:00 PM</p>
                                <a id="meeting-link" href="#" class="text-sites-accent font-semibold hover:underline break-words">https://meet.google.</a>
                            </button>
                            <button onclick="showCalendarModal()" class="w-full text-left bg-white text-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all">
                                <div class="bg-sites-dark text-white p-4">
                                    <h4 id="calendar-widget-month" class="text-lg font-bold">Month Year</h4>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-7 text-center text-sm font-semibold mb-2 text-gray-500">
                                        <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                                    </div>
                                    <div id="calendar-widget-grid" class="grid grid-cols-7 text-center text-sm gap-y-2">
                                        <!-- Mini calendar days render here -->
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div> 
                </section>

                 <section id="assign-content" class="content-page">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Task List</h2>
                            <button onclick="showAddTaskModal()" class="bg-sites-accent text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 flex items-center"><i data-lucide="plus" class="w-5 h-5 mr-2"></i>Add Task</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b-2">
                                        <th class="p-4 font-bold">Task</th>
                                        <th class="p-4 font-bold">Assigned to</th>
                                        <th class="p-4 font-bold">Status</th>
                                        <th class="p-4 font-bold">Due Date</th>
                                        <th class="p-4 font-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="assign-table-body"></tbody>
                            </table>
                        </div>
                    </div>
                </section>

                 <section id="members-content" class="content-page">
                    <div id="members-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                </section>

                <section id="profile-content" class="content-page max-w-4xl mx-auto">
                     <div class="rounded-xl shadow-2xl bg-white"> 
                        <div class="h-48 bg-sites-dark rounded-t-xl relative"> 
                        </div>
                        <div class="relative p-8 pt-0">
                            <div id="profile-card-avatar" class="w-32 h-32 absolute top-0 left-8 transform -translate-y-1/2 bg-sites-accent text-white flex items-center justify-center text-5xl font-extrabold border-4 border-white shadow-xl rounded-full">U</div>
                            <div class="pt-20"> 
                                <h3 id="profile-full-name-card" class="text-3xl font-extrabold text-gray-800">Loading...</h3>
                                <p id="profile-role-card" class="text-sites-accent text-lg font-semibold">Loading...</p>
                                <p id="profile-student-id-card" class="text-gray-500 text-sm mt-1">ID: Loading...</p>
                            </div>
                            <hr class="my-6 border-gray-200">
                            <div>
                                <h4 class="text-xl font-bold mb-4">Personal info</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-500 uppercase tracking-wider font-semibold">Email</p>
                                        <p id="profile-email-card" class="font-bold text-base mt-1">Loading...</p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-500 uppercase tracking-wider font-semibold">ID</p>
                                        <p id="profile-student-id-main" class="font-bold text-base mt-1">Loading...</p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-500 uppercase tracking-wider font-semibold">Contact</p>
                                        <p id="profile-contact-card" class="font-bold text-base mt-1">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- Floating Edit Profile Button -->
    <button onclick="showEditProfileModal()" id="edit-profile-fab" class="hidden fixed bottom-6 right-6 bg-sites-accent text-white w-12 h-12 rounded-full shadow-xl flex items-center justify-center hover:bg-sites-medium transition-transform hover:scale-110 z-40">
        <i data-lucide="pencil" class="w-6 h-6"></i>
    </button>

    <!-- MODALS -->
    
    <!-- Task Details Modal -->
    <div id="task-details-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full flex flex-col relative">
            <header class="p-6 border-b flex justify-between items-center">
                 <h2 class="text-2xl font-bold text-gray-800">Task Details</h2>
                 <button onclick="hideTaskDetailsModal()" class="text-gray-400 hover:text-gray-700"><i data-lucide="x" class="w-6 h-6"></i></button>
            </header>
            <div class="p-8 space-y-6">
                <h3 id="details-task-title" class="text-3xl font-extrabold text-gray-900"></h3>
                <div class="flex items-center space-x-4">
                    <span id="details-task-status" class="status-badge"></span>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 mr-2"></i>
                        <span id="details-task-priority" class="font-semibold"></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                        <span id="details-task-due-date" class="font-semibold"></span>
                    </div>
                </div>
                <div class="border-t pt-4">
                    <h4 class="font-bold text-lg mb-3">Assigned To</h4>
                    <div id="details-task-members" class="flex flex-wrap gap-4"></div>
                </div>
                 <div class="border-t pt-4">
                    <h4 class="font-bold text-lg mb-3">Subtasks</h4>
                    <div id="details-task-subtasks" class="space-y-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="meeting-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 relative">
            <button onclick="hideMeetingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700"><i data-lucide="x" class="w-6 h-6"></i></button>
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Meeting Details</h2>
            <form id="meeting-form" onsubmit="event.preventDefault(); saveMeetingDetails();" class="space-y-4">
                <div>
                    <label for="edit-meeting-date" class="block text-sm font-medium text-gray-600">Meeting Date</label>
                    <input type="date" id="edit-meeting-date" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="edit-meeting-time" class="block text-sm font-medium text-gray-600">Meeting Time</label>
                    <input type="text" id="edit-meeting-time" placeholder="e.g., 9:00 PM" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="edit-meeting-link" class="block text-sm font-medium text-gray-600">Meeting Link</label>
                    <input type="url" id="edit-meeting-link" placeholder="https://meet.google.com/..." class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full py-2 px-4 bg-sites-accent text-white font-semibold rounded-lg shadow-md hover:opacity-90 transition-opacity">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="calendar-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full h-auto flex flex-col p-4 relative">
            <button onclick="hideCalendarModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 bg-white rounded-full p-1 z-10"><i data-lucide="x" class="w-6 h-6"></i></button>
            <header class="flex items-center justify-between p-4 border-b">
                <div class="flex items-center space-x-4">
                    <button id="calendar-today-btn" class="font-semibold text-gray-600 hover:text-black border px-3 py-1 rounded-md">Today</button>
                    <button id="calendar-prev-btn" class="p-2 rounded-full hover:bg-gray-100"><i data-lucide="chevron-left" class="w-6 h-6"></i></button>
                    <button id="calendar-next-btn" class="p-2 rounded-full hover:bg-gray-100"><i data-lucide="chevron-right" class="w-6 h-6"></i></button>
                </div>
                <h2 id="calendar-header" class="text-2xl font-bold text-gray-800">Month Year</h2>
                <div></div>
            </header>
            <div class="grid grid-cols-7 text-center font-bold text-gray-600 border-b">
                <div class="p-2">Sun</div><div class="p-2">Mon</div><div class="p-2">Tue</div><div class="p-2">Wed</div><div class="p-2">Thu</div><div class="p-2">Fri</div><div class="p-2">Sat</div>
            </div>
            <div id="calendar-grid-body" class="calendar-grid flex-1"></div>
        </div>
    </div>
    
    <div id="add-task-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-modal-bg rounded-xl shadow-2xl max-w-xl w-full flex flex-col relative text-sites-dark">
            <header class="p-6 border-b border-sites-dark/10">
                 <h2 class="text-2xl font-bold">Add New Task</h2>
                 <button onclick="hideAddTaskModal()" class="absolute top-6 right-6 text-sites-dark/70 hover:text-sites-dark"><i data-lucide="x" class="w-6 h-6"></i></button>
            </header>
            <div class="modal-content p-6 overflow-y-auto">
                <form id="add-task-form" class="space-y-6">
                    <div>
                        <label for="task-title" class="block text-sm font-semibold mb-2">Task Title</label>
                        <input type="text" id="task-title" required placeholder="Enter the main task title" class="w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sites-accent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Subtasks</label>
                        <div id="subtask-list" class="space-y-2"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="task-priority" class="block text-sm font-semibold mb-2">Priority</label>
                            <select id="task-priority" class="w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sites-accent appearance-none">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div>
                            <label for="task-due-date" class="block text-sm font-semibold mb-2">Due Date</label>
                            <input type="date" id="task-due-date" required class="w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sites-accent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Assign To</label>
                        <div class="relative" id="assign-member-wrapper">
                            <div id="assign-member-box" class="flex flex-wrap items-center gap-2 p-2 bg-white rounded-md border border-gray-300 min-h-[52px] cursor-pointer">
                                <span id="assign-placeholder" class="text-gray-400 p-1">Select members...</span>
                            </div>
                            <div id="member-selection-dropdown" class="absolute hidden w-full bg-white rounded-md shadow-lg mt-1 max-h-48 z-10 flex flex-col">
                                <div class="p-2 border-b">
                                     <input type="text" id="member-search" placeholder="Search members..." class="w-full p-2 text-gray-800 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-sites-accent">
                                </div>
                                <div id="member-list" class="overflow-y-auto text-gray-800"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <footer class="p-6 border-t border-sites-dark/10 mt-auto">
                 <button onclick="saveNewTask()" class="w-full bg-sites-accent text-white font-bold py-3 rounded-lg hover:opacity-90 transition-opacity">Add Task</button>
            </footer>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="edit-task-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-modal-bg rounded-xl shadow-2xl max-w-xl w-full flex flex-col relative text-sites-dark">
            <header class="p-6 border-b border-sites-dark/10">
                 <h2 class="text-2xl font-bold">Edit Task</h2>
                 <button onclick="hideEditTaskModal()" class="absolute top-6 right-6 text-sites-dark/70 hover:text-sites-dark"><i data-lucide="x" class="w-6 h-6"></i></button>
            </header>
            <div class="modal-content p-6 overflow-y-auto">
                <form id="edit-task-form" class="space-y-6">
                    <div>
                        <label for="edit-task-title" class="block text-sm font-semibold mb-2">Task Title</label>
                        <input type="text" id="edit-task-title" required placeholder="Enter the main task title" class="w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sites-accent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Subtasks</label>
                        <div id="edit-subtask-list" class="space-y-2"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit-task-priority" class="block text-sm font-semibold mb-2">Priority</label>
                            <select id="edit-task-priority" class="w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sites-accent appearance-none">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit-task-due-date" class="block text-sm font-semibold mb-2">Due Date</label>
                            <input type="date" id="edit-task-due-date" required class="w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sites-accent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Assign To</label>
                        <div class="relative" id="edit-assign-member-wrapper">
                            <div id="edit-assign-member-box" class="flex flex-wrap items-center gap-2 p-2 bg-white rounded-md border border-gray-300 min-h-[52px] cursor-pointer">
                                <span id="edit-assign-placeholder" class="text-gray-400 p-1">Select members...</span>
                            </div>
                            <div id="edit-member-selection-dropdown" class="absolute hidden w-full bg-white rounded-md shadow-lg mt-1 max-h-48 z-10 flex flex-col">
                                <div class="p-2 border-b">
                                     <input type="text" id="edit-member-search" placeholder="Search members..." class="w-full p-2 text-gray-800 bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-sites-accent">
                                </div>
                                <div id="edit-member-list" class="overflow-y-auto text-gray-800"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <footer class="p-6 border-t border-sites-dark/10 mt-auto">
                 <button onclick="saveTaskChanges()" class="w-full bg-sites-accent text-white font-bold py-3 rounded-lg hover:opacity-90 transition-opacity">Save Changes</button>
            </footer>
        </div>
    </div>

    <div id="delete-task-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                 <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Task</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Are you sure you want to delete this task? This action cannot be undone.</p>
            </div>
            <div class="items-center px-4 py-3 space-x-4">
                <button id="confirm-delete-btn" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-red-700">Delete</button>
                <button id="cancel-delete-btn" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-auto shadow-sm hover:bg-gray-300">Cancel</button>
            </div>
        </div>
    </div>

    <div id="member-details-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full flex flex-col relative">
            <button onclick="hideMemberDetailsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700"><i data-lucide="x" class="w-6 h-6"></i></button>
            <div class="p-8">
                <div class="flex items-center space-x-6 mb-6">
                    <div id="edit-modal-avatar" class="w-24 h-24 bg-sites-light rounded-full flex items-center justify-center ring-4 ring-white shadow-lg overflow-hidden">
    <img id="edit-modal-avatar-img" src="" alt="User Avatar" class="w-full h-full object-cover rounded-full">
</div>

                    <div>
                        <h2 id="modal-member-name" class="text-3xl font-bold"></h2>
                        <p id="modal-member-role" class="text-sites-accent font-semibold text-lg"></p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-sm mb-6">
                     <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 uppercase tracking-wider font-semibold">Email</p>
                        <p id="modal-member-email" class="font-bold text-base mt-1 truncate"></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 uppercase tracking-wider font-semibold">ID</p>
                        <p id="modal-member-username" class="font-bold text-base mt-1"></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 uppercase tracking-wider font-semibold">Contact</p>
                        <p id="modal-member-contact" class="font-bold text-base mt-1"></p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Current Tasks</h3>
                    <div id="modal-member-tasks" class="space-y-4 max-h-64 overflow-y-auto pr-2">
                        <!-- Tasks will be rendered here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full relative overflow-hidden">

        <!-- Main View -->
        <div id="edit-profile-main-view" class="edit-profile-view active flex-col">
            <header class="p-4 flex items-center justify-center border-b relative">
                <h2 class="text-lg font-bold">Edit Profile</h2>
                <button onclick="hideEditProfileModal()" class="absolute right-4 text-gray-500 hover:text-gray-800">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </header>
            <div>
                <div onclick="showEditView('email')" class="flex justify-between items-center p-4 border-y cursor-pointer hover:bg-gray-50">
                    <span class="font-semibold">Email</span>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-500"></i>
                </div>
                <div onclick="showEditView('contact')" class="flex justify-between items-center p-4 border-b cursor-pointer hover:bg-gray-50">
                    <span class="font-semibold">Contact</span>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-500"></i>
                </div>
            </div>
        </div>

        <!-- Edit Email View -->
        <div id="edit-profile-email-view" class="edit-profile-view flex-col">
            <header class="p-4 flex items-center border-b relative">
                <button onclick="showEditView('main')" class="absolute left-4 text-gray-600 hover:text-gray-900">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <h2 class="text-lg font-bold text-center w-full">Email</h2>
                <button id="save-email-btn" onclick="saveProfileField('email')" class="absolute right-4 text-sites-accent font-semibold">Save</button>
            </header>
            <div class="p-6 flex-grow">
                <input id="edit-input-email" type="email" class="w-full text-lg py-2 bg-transparent border-b focus:outline-none focus:border-sites-accent">
            </div>
        </div>

        <!-- Edit Contact View -->
        <div id="edit-profile-contact-view" class="edit-profile-view flex-col">
            <header class="p-4 flex items-center border-b relative">
                <button onclick="showEditView('main')" class="absolute left-4 text-gray-600 hover:text-gray-900">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <h2 class="text-lg font-bold text-center w-full">Contact</h2>
                <button id="save-contact-btn" onclick="saveProfileField('contact')" class="absolute right-4 text-sites-accent font-semibold">Save</button>
            </header>
            <div class="p-6 flex-grow">
                <input id="edit-input-contact" type="text" class="w-full text-lg py-2 bg-transparent border-b focus:outline-none focus:border-sites-accent">
            </div>
        </div>

    </div>
</div>

    </div>
    
    <script >


// --- APPLICATION STATE (FRONT-END ONLY) ---
const MOCK_CURRENT_DATE = new Date('2025-10-16T15:40:00');

let currentMeetingData = {};
let allTasks = [];
let allMembers = [];
let profileData = {};
let calendarDate = new Date(MOCK_CURRENT_DATE);

// State for Add Task Modal
let assignedMemberIds = new Set();
let isDropdownOpen = false;

// State for Edit Task Modal
let taskIdToEdit = null;
let editAssignedMemberIds = new Set();
let isEditDropdownOpen = false;

let taskIdToDelete = null;
const contentMap = { 'dashboard': 'Dashboard', 'assign': 'Assign Tasks', 'members': 'Members', 'profile': 'Profile Information' };

// --- HELPER FUNCTIONS ---
const getTaskStatus = (dueDate) => {
    if (!dueDate) return 'Pending';
    const due = new Date(dueDate + 'T23:59:59');
    const today = new Date(MOCK_CURRENT_DATE);
    today.setHours(0, 0, 0, 0);
    return due < today ? 'Overdue' : 'Pending';
};

// --- INITIAL DATA ---
function loadInitialData() {
    // BACKEND: This entire function should be replaced by API calls to your server 
    // to fetch the real data from your database (e.g., using PHP and SQL).

    // BACKEND: Replace this mock data by fetching the logged-in user's profile from the database.
fetch('fetch_leader_profile.php')
  .then(response => response.json())
  .then(data => {
      if (data.error) {
          console.error('Error:', data.error);
          return;
      }
      updateProfileUI(data); // render profile info
  })
  .catch(error => console.error('Error fetching profile:', error));
    // BACKEND: Fetch meeting details from the database.
    currentMeetingData = { date: "2025-10-17", time: "9:00 PM", link: "https://meet.google." };
    
    // BACKEND: Fetch all team members from the database.
    allMembers = [];

    fetch('fetch_members.php')  // your PHP endpoint
    .then(response => response.json())
    .then(data => {
        allMembers = data;           // populate the array dynamically
        renderMembersPage();         // call your function to display them
    })
    .catch(error => console.error('Error fetching members:', error));

    loadTasks(); 


    // BACKEND: Fetch all tasks from the database.
   allTasks = [];

function loadTasks() {
    fetch('fetch_tasks.php')
        .then(res => res.json())
        .then(data => {
            // `data` is the array of tasks from the backend
            allTasks = data; 
            
            // Render UI with the fetched tasks
            renderAssignPage();
            renderDashboardTasks();
            renderCalendarModal();
        })
        .catch(err => console.error("Network error:", err));
}

// Call on page load
window.addEventListener('DOMContentLoaded', loadTasks);

}

// --- REFACTORED SHARED LOGIC for Add/Edit Modals ---
const addSubtaskInput = (context = 'add', value = '') => {
    const listId = context === 'add' ? 'subtask-list' : 'edit-subtask-list';
    const subtaskList = document.getElementById(listId);
    const subtaskItem = document.createElement('div');
    subtaskItem.className = 'subtask-item flex items-center gap-2';
    subtaskItem.innerHTML = `<input type="text" class="subtask-input w-full bg-white p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-sites-accent" placeholder="Add a subtask..." value="${value}"><button type="button" class="remove-subtask text-red-500 hover:text-red-700 opacity-50 hover:opacity-100"><i data-lucide="x-circle" class="w-5 h-5"></i></button>`;
    const input = subtaskItem.querySelector('.subtask-input');
    input.addEventListener('input', () => {
        const allInputs = [...subtaskList.querySelectorAll('.subtask-input')];
        if (input === allInputs[allInputs.length - 1] && input.value.trim() !== '') { addSubtaskInput(context); }
    });
    subtaskList.appendChild(subtaskItem);
    lucide.createIcons();
};

const renderMemberSelectionDropdown = (context = 'add', filter = '') => {
    const listId = context === 'add' ? 'member-list' : 'edit-member-list';
    const list = document.getElementById(listId);
    const filteredMembers = allMembers.filter(m => m.name.toLowerCase().includes(filter.toLowerCase()));
    list.innerHTML = filteredMembers.map(member => `<div class="p-2 hover:bg-gray-100 cursor-pointer" data-id="${member.id}">${member.name}</div>`).join('');
};

const toggleMemberAssignment = (context = 'add', memberId) => {
    const idSet = context === 'add' ? assignedMemberIds : editAssignedMemberIds;
    if (idSet.has(memberId)) { idSet.delete(memberId); } else { idSet.add(memberId); }
    renderAssignedMembers(context);
    const searchInputId = context === 'add' ? 'member-search' : 'edit-member-search';
    document.getElementById(searchInputId).focus();
};

const renderAssignedMembers = (context = 'add') => {
    const boxId = context === 'add' ? 'assign-member-box' : 'edit-assign-member-box';
    const placeholderId = context === 'add' ? 'assign-placeholder' : 'edit-assign-placeholder';
    const idSet = context === 'add' ? assignedMemberIds : editAssignedMemberIds;
    const box = document.getElementById(boxId);
    const placeholder = document.getElementById(placeholderId);
    const memberElements = Array.from(box.querySelectorAll('div'));
    memberElements.forEach(el => el.remove());
    if (idSet.size > 0) {
        placeholder.classList.add('hidden');
        idSet.forEach(id => {
            const member = allMembers.find(m => m.id === id);
            if(member) {
                const memberEl = document.createElement('div');
                memberEl.className = 'bg-sites-light text-sites-dark text-sm font-semibold px-2 py-1 rounded-full flex items-center gap-2';
                memberEl.innerHTML = `<span>${member.name}</span><button type="button" class="text-sites-dark/50 hover:text-sites-dark" data-id="${id}"><i data-lucide="x" class="w-4 h-4"></i></button>`;
                box.appendChild(memberEl);
            }
        });
    } else { placeholder.classList.remove('hidden'); }
    lucide.createIcons();
};

// --- ADD TASK MODAL ---
window.showAddTaskModal = () => { document.getElementById('add-task-form').reset(); document.getElementById('subtask-list').innerHTML = ''; assignedMemberIds.clear(); renderMemberSelectionDropdown('add'); renderAssignedMembers('add'); addSubtaskInput('add'); document.getElementById('add-task-modal').classList.remove('hidden'); lucide.createIcons();};
window.hideAddTaskModal = () => document.getElementById('add-task-modal').classList.add('hidden');
window.saveNewTask = () => { 
    const title = document.getElementById('task-title').value.trim();
    const dueDate = document.getElementById('task-due-date').value;
    const priority = document.getElementById('task-priority').value;
    const subtasks = [...document.querySelectorAll('#subtask-list .subtask-input')]
                        .map(input => input.value.trim())
                        .filter(Boolean);
    const assignedToArray = Array.from(assignedMemberIds);

    if (!title || !dueDate || !priority || assignedToArray.length === 0) {
        alert("Please fill in all required fields and assign at least one member!");
        return;
    }

    const status = getTaskStatus(dueDate);

    const newTask = {
        name: title,
        dueDate,
        priority,
        assignedTo: assignedToArray,
        subtasks,
        status
    };

    // Send to backend
    fetch('add_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newTask)
    })
    .then(res => res.json())
    .then(response => {
        console.log("Backend response:", response); // Debugging
        if (response.success) {
            alert("Task saved successfully!");

            // Update local UI with the new task including the generated task ID
            newTask.id = response.task_id;
            allTasks.push(newTask);

            hideAddTaskModal();
            renderAssignPage();
            renderDashboardTasks();
            renderCalendarModal();
        } else {
            alert("Error saving task: " + (response.error || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Network error:", err);
        alert("Network or server error");
    });



    
    hideAddTaskModal(); 
    // After a successful backend call, you would re-fetch the data or expect the updated list.
    // For this UI prototype, we just re-render the local data.
    renderAssignPage(); 
    renderDashboardTasks(); 
    renderCalendarModal(); 
};

// --- EDIT TASK MODAL ---
window.showEditTaskModal = (taskId) => {
    const task = allTasks.find(t => t.id === taskId); if (!task) return;
    taskIdToEdit = taskId;
    document.getElementById('edit-task-title').value = task.name;
    document.getElementById('edit-task-priority').value = task.priority;
    document.getElementById('edit-task-due-date').value = task.dueDate;
    const subtaskList = document.getElementById('edit-subtask-list');
    subtaskList.innerHTML = '';
    if (task.subtasks && task.subtasks.length > 0) { task.subtasks.forEach(subtaskText => addSubtaskInput('edit', subtaskText)); }
    addSubtaskInput('edit');
    editAssignedMemberIds = new Set(task.assignedTo);
    renderAssignedMembers('edit');
    renderMemberSelectionDropdown('edit');
    document.getElementById('edit-task-modal').classList.remove('hidden');
    lucide.createIcons();
};
window.hideEditTaskModal = () => { taskIdToEdit = null; document.getElementById('edit-task-modal').classList.add('hidden'); };
window.saveTaskChanges = () => {
    if (!taskIdToEdit) return;
    const taskIndex = allTasks.findIndex(t => t.id === taskIdToEdit); if (taskIndex === -1) return;
    const title = document.getElementById('edit-task-title').value, dueDate = document.getElementById('edit-task-due-date').value, priority = document.getElementById('edit-task-priority').value;
    const subtasks = [...document.querySelectorAll('#edit-subtask-list .subtask-input')].map(input => input.value.trim()).filter(Boolean);
    if (!title || !dueDate) return;

    // BACKEND: Send the updated task data to the server to update the corresponding record in the database using 'taskIdToEdit'.
    allTasks[taskIndex] = { ...allTasks[taskIndex], name: title, dueDate, priority, subtasks, assignedTo: Array.from(editAssignedMemberIds), status: allTasks[taskIndex].status === 'Done' ? 'Done' : getTaskStatus(dueDate), };
    
    hideEditTaskModal(); 
    // After a successful backend call, re-render the UI.
    renderAssignPage(); 
    renderDashboardTasks(); 
    renderCalendarModal();
};

// --- TASK DETAILS MODAL ---
window.showTaskDetailsModal = (taskId) => {
    const task = allTasks.find(t => t.id === taskId); if (!task) return;
    document.getElementById('details-task-title').textContent = task.name;
    const statusEl = document.getElementById('details-task-status');
    statusEl.textContent = task.status;
    statusEl.className = `status-badge status-${task.status.toLowerCase()}`;
    document.getElementById('details-task-priority').textContent = `${task.priority} Priority`;
    document.getElementById('details-task-due-date').textContent = task.dueDate;
    const membersContainer = document.getElementById('details-task-members');
    membersContainer.innerHTML = task.assignedTo.map(memberId => {
        const member = allMembers.find(m => m.id === memberId);
        if (!member) return '';
        return `<div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-sites-light rounded-full flex items-center justify-center text-sites-dark font-bold text-lg">${member.name.charAt(0)}</div>
                            <span class="font-semibold">${member.name}</span>
                        </div>`;
    }).join('');
    const subtasksContainer = document.getElementById('details-task-subtasks');
    if (task.subtasks && task.subtasks.length > 0) {
        subtasksContainer.innerHTML = task.subtasks.map(subtask => 
            `<div class="flex items-center">
                        <i data-lucide="check-square" class="w-5 h-5 mr-3 text-sites-accent"></i>
                        <span>${subtask}</span>
                    </div>`
        ).join('');
    } else {
        subtasksContainer.innerHTML = `<p class="text-gray-500">No subtasks for this task.</p>`;
    }
    document.getElementById('task-details-modal').classList.remove('hidden');
    lucide.createIcons();
};
window.hideTaskDetailsModal = () => document.getElementById('task-details-modal').classList.add('hidden');

// --- OTHER MODALS ---
window.showDeleteConfirmationModal = (taskId) => { taskIdToDelete = taskId; document.getElementById('delete-task-modal').classList.remove('hidden'); lucide.createIcons(); };
const hideDeleteConfirmationModal = () => { taskIdToDelete = null; document.getElementById('delete-task-modal').classList.add('hidden'); };
const confirmDeleteTask = () => { 
     if (!taskIdToDelete) return;

    fetch('delete_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: taskIdToDelete })
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            // Remove from local state
            allTasks = allTasks.filter(task => task.id !== taskIdToDelete);

            // Update UI
            renderAssignPage(); 
            renderDashboardTasks(); 
            renderCalendarModal(); 

            // Hide modal
            hideDeleteConfirmationModal();

            // ✅ Debug / confirmation message
            console.log(`Task ID ${taskIdToDelete} deleted successfully.`);
            alert("Task deleted successfully!");  // optional pop-up
        } else {
            alert("Error deleting task: " + (response.error || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Network error:", err);
        alert("Network or server error");
    });

    allTasks = allTasks.filter(task => task.id !== taskIdToDelete); 
    // After a successful backend call, re-render the UI.
    renderAssignPage(); 
    renderDashboardTasks(); 
    renderCalendarModal(); 
    hideDeleteConfirmationModal(); 
};
window.showMemberDetailsModal = (memberId) => { const member = allMembers.find(m => m.id === memberId); if (!member) return; const avatarImg = document.getElementById('edit-modal-avatar-img'); avatarImg.src = member.profilePic || 'SITES LOGO.png'; avatarImg.alt = member.name || 'User';document.getElementById('modal-member-name').textContent = member.name; document.getElementById('modal-member-role').textContent = member.role; document.getElementById('modal-member-email').textContent = member.email; document.getElementById('modal-member-username').textContent = member.username; document.getElementById('modal-member-contact').textContent = member.contact; const memberTasks = allTasks.filter(task => task.assignedTo.includes(memberId)); const tasksContainer = document.getElementById('modal-member-tasks'); if (memberTasks.length > 0) { tasksContainer.innerHTML = memberTasks.map(task => { const progress = task.status === 'Done' ? 100 : Math.floor(Math.random() * 81); return `<div><div class="flex justify-between items-center mb-1"><p class="font-semibold">${task.name}</p><span class="font-bold text-sites-accent text-sm">${progress}%</span></div><div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-sites-accent h-2 rounded-full" style="width: ${progress}%"></div></div></div>`; }).join(''); } else { tasksContainer.innerHTML = `<p class="text-gray-500 text-center">No tasks assigned.</p>`; } document.getElementById('member-details-modal').classList.remove('hidden'); };
window.hideMemberDetailsModal = () => document.getElementById('member-details-modal').classList.add('hidden');

window.showEditProfileModal = () => { 
    document.getElementById('edit-profile-modal').classList.remove('hidden'); 
    showEditView('main'); 
};
window.hideEditProfileModal = () => document.getElementById('edit-profile-modal').classList.add('hidden');

window.showEditView = (viewName) => { 
    document.querySelectorAll('.edit-profile-view').forEach(v => v.classList.remove('active')); 
    document.getElementById(`edit-profile-${viewName}-view`).classList.add('active'); 
    if (viewName === 'main') { 
        document.getElementById('edit-modal-avatar').querySelector('span').textContent = (profileData.name || 'U').charAt(0).toUpperCase(); 
    } else if(viewName !== 'password') { 
        const input = document.getElementById(`edit-input-${viewName}`); 
        const saveBtn = document.getElementById(`save-${viewName}-btn`); 
        input.value = profileData[viewName] || ''; 
        saveBtn.classList.add('hidden'); 
        input.oninput = () => { 
            if (input.value !== profileData[viewName]) { 
                saveBtn.classList.remove('hidden'); 
            } else { 
                saveBtn.classList.add('hidden'); 
            } 
        }; 
    } 
    lucide.createIcons(); 
};

// Assume profileData is an object storing current email/contact
window.saveProfileField = (fieldName) => {
    const newValue = document.getElementById(`edit-input-${fieldName}`).value;

    if (newValue === profileData[fieldName]) {
        showEditView('main');
        return;
    }

    fetch('update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ field: fieldName, value: newValue })
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            profileData[fieldName] = newValue;
            updateProfileUI(profileData, fieldName); // only update changed field
            showEditView('main');
        } else {
            alert("Error: " + result.message);
        }
    });
};



window.showCalendarModal = () => { calendarDate = new Date(MOCK_CURRENT_DATE); renderCalendarModal(); document.getElementById('calendar-modal').classList.remove('hidden'); };
window.hideCalendarModal = () => document.getElementById('calendar-modal').classList.add('hidden');
window.showMeetingModal=()=>{document.getElementById("edit-meeting-date").value=currentMeetingData.date||"";document.getElementById("edit-meeting-time").value=currentMeetingData.time||"";document.getElementById("edit-meeting-link").value=currentMeetingData.link||"";document.getElementById("meeting-modal").classList.remove("hidden")};
window.hideMeetingModal=()=>document.getElementById("meeting-modal").classList.add("hidden");
window.saveMeetingDetails=()=>{
    const newDate=document.getElementById("edit-meeting-date").value,newTime=document.getElementById("edit-meeting-time").value,newLink=document.getElementById("edit-meeting-link").value;
    if(!newDate||!newTime||!newLink)return; 
    
    // BACKEND: Send these new meeting details to the server to update the database.
    currentMeetingData = { date: newDate, time: newTime, link: newLink }; 

    updateMeetingUI(currentMeetingData); 
    hideMeetingModal(); 
};

// --- UI RENDERING FUNCTIONS ---
const renderCalendarModal = () => { const calendarBody = document.getElementById('calendar-grid-body'), calendarHeader = document.getElementById('calendar-header'); if (!calendarBody || !calendarHeader) return; const month = calendarDate.getMonth(), year = calendarDate.getFullYear(), today = MOCK_CURRENT_DATE; calendarHeader.textContent = `${calendarDate.toLocaleString('default', { month: 'long' })} ${year}`; const firstDayOfMonth = new Date(year, month, 1), daysInMonth = new Date(year, month + 1, 0).getDate(), lastDayOfPrevMonth = new Date(year, month, 0).getDate(), startingDayOfWeek = firstDayOfMonth.getDay(); calendarBody.innerHTML = ''; let date = 1, nextMonthDate = 1; for (let i = 0; i < 42; i++) { if (i < startingDayOfWeek) { const day = lastDayOfPrevMonth - startingDayOfWeek + i + 1; calendarBody.innerHTML += `<div class="calendar-day other-month"><div class="calendar-day-number">${day}</div></div>`; } else if (date > daysInMonth) { calendarBody.innerHTML += `<div class="calendar-day other-month"><div class="calendar-day-number">${nextMonthDate++}</div></div>`; } else { const isToday = today.getDate() === date && today.getMonth() === month && today.getFullYear() === year; const tasksForDay = allTasks.filter(task => { const taskDate = new Date(task.dueDate + 'T00:00:00'); return taskDate.getDate() === date && taskDate.getMonth() === month && taskDate.getFullYear() === year; }); let tasksHtml = tasksForDay.map(task => `<div class="calendar-event priority-${task.priority?.toLowerCase() || 'medium'}">${task.name}</div>`).join(''); calendarBody.innerHTML += `<div class="calendar-day"><div class="calendar-day-number ${isToday ? 'today' : ''}">${date}</div>${tasksHtml}</div>`; date++; } } };
const renderCalendarWidget = () => { const grid = document.getElementById('calendar-widget-grid'), header = document.getElementById('calendar-widget-month'); if(!grid || !header) return; const today = MOCK_CURRENT_DATE, month = today.getMonth(), year = today.getFullYear(); header.textContent = today.toLocaleString('default', { month: 'long', year: 'numeric' }).toUpperCase(); const firstDayOfMonth = new Date(year, month, 1).getDay(), daysInMonth = new Date(year, month + 1, 0).getDate(); grid.innerHTML = ''; for (let i = 0; i < firstDayOfMonth; i++) grid.innerHTML += `<span></span>`; for (let day = 1; day <= daysInMonth; day++) grid.innerHTML += `<span class="${day === today.getDate() ? 'font-bold bg-sites-accent text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto' : ''}">${day}</span>`; };
const renderDashboardTasks=()=>{const list=document.getElementById("dashboard-task-list");if(!list)return;const tasksToShow=allTasks.filter(t=>t.status!=='Done').slice(0,4);if(tasksToShow.length===0){list.innerHTML='<p class="text-gray-500">No active tasks. Add one in "Assign Tasks".</p>';return}list.innerHTML=tasksToShow.map(task=>{const percentage=task.status==='Done'?100:0;return`<div><div class="flex justify-between items-center mb-1"><p class="font-semibold">${task.name}</p><span class="font-bold text-sites-accent">${percentage}%</span></div><div class="w-full bg-gray-200 rounded-full h-2.5"><div class="bg-sites-accent h-2.5 rounded-full" style="width: ${percentage}%"></div></div></div>`}).join("")};
const renderAssignPage=()=>{const tbody=document.getElementById("assign-table-body");if(!tbody)return;if(allTasks.length===0){tbody.innerHTML='<tr><td colspan="5" class="text-center p-8 text-gray-500">No tasks found. Click "Add Task".</td></tr>';return}tbody.innerHTML=allTasks.map(task=>{const assignedMembersHtml=task.assignedTo?.map(id=>{const member=allMembers.find(m=>m.id===id);return member?`<div title="${member.name}" class="w-8 h-8 bg-sites-light rounded-full flex items-center justify-center text-sites-dark font-bold text-sm ring-2 ring-white">${member.name.charAt(0)}</div>`:'';}).join("")||'';return`<tr class="border-b hover:bg-gray-50"><td class="p-4 font-semibold"><button onclick="showTaskDetailsModal('${task.id}')" class="text-left hover:text-sites-accent">${task.name}</button></td><td class="p-4"><div class="flex -space-x-2">${assignedMembersHtml}</div></td><td class="p-4"><span class="status-badge status-${task.status.toLowerCase()}">${task.status}</span></td><td class="p-4">${task.dueDate}</td><td class="p-4"><div class="flex space-x-2"><button onclick="showEditTaskModal('${task.id}')" class="text-gray-500 hover:text-sites-accent"><i data-lucide="pencil" class="w-5 h-5"></i></button><button onclick="showDeleteConfirmationModal('${task.id}')" class="text-gray-500 hover:text-red-600"><i data-lucide="trash-2" class="w-5 h-5"></i></button></div></td></tr>`}).join("");lucide.createIcons()};
const renderMembersPage=()=>{const grid=document.getElementById("members-grid");if(!grid)return;if(allMembers.length===0){grid.innerHTML='<p class="text-gray-500 col-span-full text-center">No members found.</p>';return}grid.innerHTML=allMembers.map(member=>`<button onclick="showMemberDetailsModal('${member.id}')" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl hover:-translate-y-1 transition-all">
    <div class="w-24 h-24 bg-sites-light rounded-full mx-auto flex items-center justify-center mb-4 ring-4 ring-white shadow-inner overflow-hidden">
        <img src="${member.profilePic}" alt="${member.name}" class="w-full h-full object-cover rounded-full">
    </div>
<h4 class="font-bold text-xl">${member.name}</h4><p class="text-gray-500">${member.role}</p></button>`).join("")};
const formatMeetingDate=dateString=>{if(!dateString)return"Date not set";try{const date=new Date(dateString+"T00:00:00"),today=new Date(MOCK_CURRENT_DATE);today.setHours(0,0,0,0);const tomorrow=new Date(today);tomorrow.setDate(today.getDate()+1);const meetingDate=new Date(date);meetingDate.setHours(0,0,0,0);if(meetingDate.getTime()===today.getTime())return"Today";if(meetingDate.getTime()===tomorrow.getTime())return"Tomorrow";return date.toLocaleString("en-US",{weekday:"long",month:"long",day:"numeric"})}catch(e){return dateString}};
const updateMeetingUI=data=>{document.getElementById("meeting-date").textContent=formatMeetingDate(data.date);document.getElementById("meeting-time").textContent=data.time||"N/A";const linkElement=document.getElementById("meeting-link");linkElement.textContent=data.link||"No Link";linkElement.href=data.link||"#"};
const updateProfileUI = (data, fieldName = null) => {

    // Update only email
    if (fieldName === 'email') {
        const emailEl = document.getElementById("profile-email-card");
        if (emailEl) emailEl.textContent = data.email || "e@example.com";
        return; // stop here, don't touch anything else
    }

    // Update only contact
    if (fieldName === 'contact') {
        const contactEl = document.getElementById("profile-contact-card");
        if (contactEl) contactEl.textContent = data.contact || "N/A";
        return; // stop here
    }

    // If no fieldName is passed, update everything (initial load)
    const name = data.name || "User";
    const role = data.role || "Member";
    const studentId = data.username || "0";
    const email = data.email || "e@example.com";
    const contact = data.contact || "N/A";
    const profilePic = data.profilePic || "SITES LOGO.png"; // fallback image
    const displayName = name.split(",")[0] || "User";
  

  // Sidebar info
  document.getElementById("sidebar-user-name").textContent = name;
  document.getElementById("sidebar-user-role").textContent = role;
  document.getElementById("sidebar-user-id").textContent = `ID: ${studentId}`;
  document.getElementById("welcome-message").textContent = `Welcome back, ${displayName}!`;

  // Profile card info
  document.getElementById("profile-full-name-card").textContent = name;
  document.getElementById("profile-role-card").textContent = role;
  document.getElementById("profile-student-id-card").textContent = `ID: ${studentId}`;
  document.getElementById("profile-email-card").textContent = email;
  document.getElementById("profile-student-id-main").textContent = studentId;
  document.getElementById("profile-contact-card").textContent = contact;

  // ✅ Replace both avatar circles with profile pictures
  const sidebarAvatar = document.getElementById("sidebar-initial-avatar");
  const profileAvatar = document.getElementById("profile-card-avatar");

  [sidebarAvatar, profileAvatar].forEach((el) => {
    if (el) {
      el.innerHTML = `
        <img src="${profilePic}" alt="${name}"
             style="
               width: 100%;
               height: 100%;
               border-radius: 50%;
               object-fit: cover;
               object-position: center;
             ">
      `;
    }
  });
};

// --- NAVIGATION ---
const navigate=pageId=>{
    document.querySelectorAll(".content-page").forEach(p=>p.classList.remove("active"));
    document.getElementById(`${pageId}-content`).classList.add("active");
    document.querySelectorAll("#sidebar-nav .nav-link").forEach(link=>{
        // Reset to default inactive state
        link.classList.remove("sidebar-item-active", "font-bold");
        link.classList.add("hover:bg-white/10", "rounded-lg", "mx-3", "text-sites-light", "font-semibold");
        
        // Apply active state if it matches
        if(link.dataset.page===pageId){
            link.classList.add("sidebar-item-active", "font-bold");
            link.classList.remove("hover:bg-white/10", "rounded-lg", "mx-3", "text-sites-light", "font-semibold");
        }
    });
    document.getElementById("header-title").textContent=contentMap[pageId]||"Dashboard";
    const editProfileFAB = document.getElementById('edit-profile-fab');
    if (pageId === 'profile') {
        editProfileFAB.classList.remove('hidden');
    } else {
        editProfileFAB.classList.add('hidden');
    }
    lucide.createIcons();
};
const setupNavigation=()=>{document.getElementById("sidebar-nav").addEventListener("click",event=>{const link=event.target.closest(".nav-link");if(link&&link.dataset.page){event.preventDefault();navigate(link.dataset.page)}})};
const setupCalendarControls = () => { document.getElementById('calendar-prev-btn').onclick = () => { calendarDate.setMonth(calendarDate.getMonth() - 1); renderCalendarModal(); }; document.getElementById('calendar-next-btn').onclick = () => { calendarDate.setMonth(calendarDate.getMonth() + 1); renderCalendarModal(); }; document.getElementById('calendar-today-btn').onclick = () => { calendarDate = new Date(MOCK_CURRENT_DATE); renderCalendarModal(); }; };

// --- APP INITIALIZATION ---
window.onload = () => {
    loadInitialData();
    updateProfileUI(profileData); updateMeetingUI(currentMeetingData);
    renderDashboardTasks(); renderAssignPage(); renderMembersPage(); renderCalendarWidget(); renderCalendarModal();
    setupNavigation(); setupCalendarControls();
    
    // Event Listeners for Add Task Modal
    const assignWrapper = document.getElementById('assign-member-wrapper');
    const dropdown = document.getElementById('member-selection-dropdown');
    assignWrapper.addEventListener('click', e => { const box=e.target.closest('#assign-member-box'),item=e.target.closest('#member-list > div'),btn=e.target.closest('button[data-id]'); if(box){isDropdownOpen=!isDropdownOpen;dropdown.classList.toggle('hidden',!isDropdownOpen);} else if(item){toggleMemberAssignment('add',item.dataset.id);} if(btn){e.stopPropagation();toggleMemberAssignment('add',btn.dataset.id);} });
    document.getElementById('member-search').addEventListener('input', e => renderMemberSelectionDropdown('add', e.target.value));
    document.getElementById('subtask-list').addEventListener('click', e => { const btn = e.target.closest('.remove-subtask'); if(btn){ const items = document.querySelectorAll('#subtask-list .subtask-item'); if(items.length > 1){btn.closest('.subtask-item').remove();} else {btn.closest('.subtask-item').querySelector('input').value='';}}});
    
    // Event Listeners for Edit Task Modal
    const editAssignWrapper = document.getElementById('edit-assign-member-wrapper');
    const editDropdown = document.getElementById('edit-member-selection-dropdown');
    editAssignWrapper.addEventListener('click', e => { const box=e.target.closest('#edit-assign-member-box'),item=e.target.closest('#edit-member-list > div'),btn=e.target.closest('button[data-id]'); if(box){isEditDropdownOpen=!isEditDropdownOpen;editDropdown.classList.toggle('hidden',!isEditDropdownOpen);} else if(item){toggleMemberAssignment('edit',item.dataset.id);} if(btn){e.stopPropagation();toggleMemberAssignment('edit',btn.dataset.id);} });
    document.getElementById('edit-member-search').addEventListener('input', e => renderMemberSelectionDropdown('edit', e.target.value));
    document.getElementById('edit-subtask-list').addEventListener('click', e => { const btn = e.target.closest('.remove-subtask'); if(btn){ const items = document.querySelectorAll('#edit-subtask-list .subtask-item'); if(items.length > 1){btn.closest('.subtask-item').remove();} else {btn.closest('.subtask-item').querySelector('input').value='';}}});
    
    // Global listeners
    document.addEventListener('click', e => { if (!assignWrapper.contains(e.target)) { isDropdownOpen = false; dropdown.classList.add('hidden'); } if (!editAssignWrapper.contains(e.target)) { isEditDropdownOpen = false; editDropdown.classList.add('hidden'); } });
    document.getElementById('confirm-delete-btn').addEventListener('click', confirmDeleteTask);
    document.getElementById('cancel-delete-btn').addEventListener('click', hideDeleteConfirmationModal);
    document.getElementById('edit-profile-fab').addEventListener('click', showEditProfileModal);
    
    navigate('dashboard'); 
};
    </script>

</body>
</html>