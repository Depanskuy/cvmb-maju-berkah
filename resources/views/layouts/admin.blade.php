{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — CV Maju Sukses')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @include('partials.tailwind')
</head>
<body class="bg-background font-body-md text-on-surface antialiased">

    {{-- Sidebar & Backdrop --}}
    <x-sidebar />
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-xs transition-opacity" onclick="toggleSidebar()"></div>

    <div class="pl-0 lg:pl-[260px] min-h-screen flex flex-col">
        <x-topbar :title="$title ?? 'Dashboard'" />

        <main class="relative bg-surface min-h-screen px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar && backdrop) {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>