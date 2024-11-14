<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Active Sessions') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("View and Manage your active sessions") }}
        </p>
    </header>

    <div class="w-full">
        @foreach($user->userSessions as $session)
            <div class="bg-white dark:bg-gray-800 sm:rounded-lg p-4 mb-4 w-full border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $session->user_agent }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $session->ip_address }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $session->last_activity }}</p>

                @if($session->session_id === request()->session()->getId())
                    <p class="text-sm text-green-600 dark:text-green-400">{{ __('This is your current session') }}</p>
                @endif

                <div class="text-right">
                    <form action="{{ route('delete-session', $session->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-right text-red-600 dark:text-red-400 hover:underline text-xl">{{ __('Remove Session') }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
