<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>419 • Página expirada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-white text-slate-800 dark:bg-slate-950 dark:text-slate-100">
    <main class="flex items-center justify-center min-h-screen px-6">
        <section class="w-full max-w-xl text-center">
            <div
                class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300">
                <!-- ícone -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20Zm1 11H11V7h2v6Zm0 4H11v-2h2v2Z" />
                </svg>
            </div>

            <h1 class="text-5xl font-extrabold tracking-tight">419</h1>
            <p class="mt-2 text-xl font-semibold">Página expirada</p>
            <p class="mt-3 text-slate-500 dark:text-slate-400">
                A sua sessão expirou por segurança. Atualize a página e tente novamente.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <button onclick="location.reload()"
                    class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-5 py-2.5 font-medium text-white hover:bg-amber-700 transition">
                    Atualizar página
                </button>
                <a href="/"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 font-medium hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800 transition">
                    Ir para a Home
                </a>
            </div>

            <p class="mt-6 text-xs text-slate-400">Dica: verifique se os cookies/CSRF estão ativos.</p>
        </section>
    </main>
</body>

</html>
