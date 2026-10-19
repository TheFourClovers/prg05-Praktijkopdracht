@props([
    'title' => config('app.name', 'Tasks')
])

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    </head>

    <body class="min-h-screen bg-slate-400 text-slate-900 antialiased">
        <header class="border-b border-slate-300 bg-slate-700">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight text-white">
                    Taskboard
                </a>

                <div class="flex items-center gap-1 text-sm font-medium text-slate-100">
                    <a href="{{ route('home') }}"
                       class="rounded-md px-3 py-2 transition hover:bg-slate-600 hover:text-white">
                        Home
                    </a>
                    <a href="/about-us"
                       class="rounded-md px-3 py-2 transition hover:bg-slate-600 hover:text-white">
                        About us
                    </a>
                    <a href="/contact"
                       class="rounded-md px-3 py-2 transition hover:bg-slate-600 hover:text-white">
                        Contact
                    </a>
                    @auth
                        <a href="{{ route('profile.edit') }}"
                           class="rounded-md px-3 py-2 transition hover:bg-slate-600 hover:text-white">
                            Profile
                        </a>
                        <a href="{{ route('logout') }}"
                           class="ml-2 rounded-md bg-indigo-500 px-3 py-2 text-white transition hover:bg-indigo-400">
                            Log out
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="ml-2 rounded-md bg-indigo-500 px-3 py-2 text-white transition hover:bg-indigo-400">
                            Log in
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            {{ $slot }}
        </main>
    </body>
</html>