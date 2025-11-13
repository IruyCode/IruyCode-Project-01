<x-guest-layout>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2"> Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Forgot password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Sign In
            </button>
        </form>
    </div>
</x-guest-layout>
