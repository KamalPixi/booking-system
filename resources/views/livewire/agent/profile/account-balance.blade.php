<div>
    <div class="bg-white py-2 text-2 px-3 rounded text-secondary">
        BDT
        <span>{{ number_format(auth()->user()->agent->account->balance, 2) }}</span>
    </div>
</div>
