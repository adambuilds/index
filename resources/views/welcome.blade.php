<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>index.one</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg: #ffffff;
                --fg: #0a0a0a;
                --muted: #4a4a4a;
                --border: #e5e5e5;
                --accent: #000000;
            }
            * { box-sizing: border-box; }
            body {
                background: var(--bg);
                color: var(--fg);
                font: 16px/1.5 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
                text-rendering: optimizeLegibility;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .cta { display: inline-block; background: var(--accent); color: #fff; padding: 12px 18px; text-decoration: none; font-weight: 600; letter-spacing: 0.2px; border: 1px solid #000; box-shadow: 0 1px 0 #000; }
            .cta:hover { transform: translateY(-1px); }
            .cta:active { transform: translateY(0); }

            main { display: grid; place-items: center; padding: 60px 0 80px; }
            .hero { text-align: center; max-width: 820px; }
            .hero img { width: min(640px, 90vw); height: auto; margin: 0 auto 28px; display: block; }
            .tagline { font-size: clamp(24px, 4vw, 42px); line-height: 1.1; font-weight: 800; letter-spacing: -0.02em; margin: 0 0 12px; }
            .desc { font-size: clamp(16px, 2vw, 18px); color: var(--muted); margin: 0 auto 28px; max-width: 720px; }
            .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

            .link { color: var(--fg); text-decoration: none; border-bottom: 2px solid var(--border); padding-bottom: 2px; font-weight: 600; }
            .link:hover { border-color: var(--fg); }

            footer { border-top: 1px solid var(--border); margin-top: 60px; }
            .foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 20px 0; font-size: 14px; color: var(--muted); }
        </style>

    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                    <section class="hero" aria-labelledby="tagline">
                        <img src="/index-one-h.svg" alt="index.one">
                        <h1 class="tagline my-9">One trusted source.</h1>
                        <p class="desc">
                            <b>index</b> exposes a landing page for every immutable Thing: elements, compounds, standards, etc. and their relationships, at a fixed, semantically meaningful URL.
                        </p>
                        <div class="actions">
                            <a class="cta" href="/login">Get access</a>
                        </div>
                    </section>

                <footer>
                    <div class="foot">
                        <span>&copy; {{ date('Y') }} index.one</span>
                        <span>adam@index.one</span>
                    </div>
                </footer>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
