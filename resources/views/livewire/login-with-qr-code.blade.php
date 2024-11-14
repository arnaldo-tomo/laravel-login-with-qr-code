<div>
    @if(!$active)
        <button wire:click="generateCode"
                type="button"
                class="mx-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Login with QR Code
        </button>
    @else
        <p wire:poll.1s>
            Scan this code using your Mobile Phone:
        </p>

        <p>
            <span class="font-bold text-red-300">Mobile Phone has to have an active session</span>
        </p>

        <img src="data:image/png;base64, {!! $qrCode !!}" alt="QR Code" class="mx-auto my-8"/>

        <p>
            QR Code is valid for 5 minutes
        </p>
    @endif
</div>