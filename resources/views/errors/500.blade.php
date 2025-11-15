<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>500 • Erro interno do servidor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-white text-slate-800 dark:bg-slate-950 dark:text-slate-100">
    <main class="flex items-center justify-center min-h-screen px-6">
        <section class="w-full max-w-xl text-center">
            <div
                class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-300">
                <!-- ícone -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11.001 10h2v5h-2v-5Zm0-3h2v2h-2V7Zm1-5a10 10 0 100 20 10 10 0 000-20Z" />
                </svg>
            </div>

            <h1 class="text-5xl font-extrabold tracking-tight">500</h1>
            <p class="mt-2 text-xl font-semibold">Algo correu mal</p>
            <p class="mt-3 text-slate-500 dark:text-slate-400">
                Ocorreu um erro inesperado. Tente novamente mais tarde.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="/"
                    class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-5 py-2.5 font-medium text-white hover:bg-rose-700 transition">
                    Voltar para a Home
                </a>
                <button onclick="location.reload()"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 font-medium hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800 transition">
                    Tentar novamente
                </button>
            </div>

            <p class="mt-6 text-xs text-slate-400">Se persistir, contacte o suporte com o horário e o que estava a
                fazer.</p>
        </section>
    </main>
</body>

</html>
