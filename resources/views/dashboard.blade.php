@extends('layouts.rice-app')

@section('content')

<div class="page-header">
    <p class="page-eyebrow">Welcome back, {{ auth()->user()->name }}</p>
    <h1 class="page-title">Place an Order</h1>
</div>

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">

    {{-- ── Left: Rice Selection ── --}}
    <div>
        <form method="POST" action="{{ route('orders.store') }}" id="order-form">
            @csrf

            <div class="card">
                <div class="card-title">
                    <span>Rice Menu</span>
                    <span id="item-count" style="color: var(--accent);">0 items selected</span>
                </div>

                @forelse($ricemenu as $i => $rice)
                <div class="rice-row" data-price="{{ $rice->price }}" style="
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 14px 0;
                    border-bottom: 1px solid var(--border);
                    gap: 16px;
                ">
                    <div style="flex: 1;">
                        <div style="font-family: var(--ff-serif); font-size: 1.05rem; font-weight: 300; line-height: 1.2;">
                            {{ $rice->name }}
                        </div>
                        @if($rice->description ?? false)
                            <div class="text-muted text-sm mt-1">{{ $rice->description }}</div>
                        @endif
                    </div>

                    <div style="font-size: 12px; color: var(--muted); white-space: nowrap; min-width: 72px; text-align: right;">
                        ₱{{ number_format($rice->price, 2) }}
                        @if($rice->unit ?? false)
                            <span style="font-size: 9px; display: block; letter-spacing: .1em;">per {{ $rice->unit }}</span>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 0; border: 1px solid var(--border);">
                        <button type="button" class="qty-btn dec" style="
                            width: 32px; height: 36px; background: var(--surface-2);
                            border: none; cursor: pointer; font-size: 16px; color: var(--muted);
                            font-family: var(--ff-mono); transition: color .1s;
                        ">−</button>

                        <input type="number"
                            name="items[{{ $i }}][quantity]"
                            class="qty-input"
                            value="0"
                            min="0"
                            step="0.5"
                            style="
                                width: 52px; height: 36px; border: none;
                                border-left: 1px solid var(--border);
                                border-right: 1px solid var(--border);
                                text-align: center; background: var(--surface);
                                padding: 0; font-size: 13px;
                            "
                        >
                        <input type="hidden" name="items[{{ $i }}][rice_id]" value="{{ $rice->id }}">

                        <button type="button" class="qty-btn inc" style="
                            width: 32px; height: 36px; background: var(--surface-2);
                            border: none; cursor: pointer; font-size: 16px; color: var(--muted);
                            font-family: var(--ff-mono); transition: color .1s;
                        ">+</button>
                    </div>
                </div>
                @empty
                    <div style="padding: 32px 0; text-align: center; color: var(--muted);">
                        No rice items yet.
                        <a href="{{ route('rice.index') }}" style="color: var(--accent); text-decoration: none;">Add some →</a>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 16px; display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                <span id="order-total" style="font-family: var(--ff-serif); font-size: 1.3rem; font-weight: 300; color: var(--muted);">
                    ₱0.00
                </span>
                <button type="submit" class="btn btn-primary" id="submit-btn" disabled style="opacity: .4;">
                    Place Order →
                </button>
            </div>
        </form>
    </div>

    {{-- ── Right: Recent Orders ── --}}
    <div>
        <div class="card">
            <div class="card-title">
                <span>Recent Orders</span>
            </div>

            @forelse($orders as $order)
                <div style="
                    padding: 14px 0;
                    border-bottom: 1px solid var(--border);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 12px;
                ">
                    <div>
                        <div style="font-size: 11px; color: var(--muted); margin-bottom: 2px;">
                            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            · {{ $order->created_at->format('M d, g:ia') }}
                        </div>
                        <div style="font-family: var(--ff-serif); font-size: 1rem; font-weight: 300;">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </div>
                        <div class="mt-1">
                            @if($order->payment)
                                <span class="badge badge-green">Paid</span>
                            @else
                                <span class="badge badge-orange">Unpaid</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-ghost btn-sm">View</a>
                </div>
            @empty
                <div style="padding: 24px 0; text-align: center; color: var(--muted); font-size: 12px;">
                    No orders yet.
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const rows      = document.querySelectorAll('.rice-row');
    const totalEl   = document.getElementById('order-total');
    const countEl   = document.getElementById('item-count');
    const submitBtn = document.getElementById('submit-btn');

    function recalc() {
        let total = 0;
        let count = 0;

        rows.forEach(row => {
            const price = parseFloat(row.dataset.price) || 0;
            const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
            total += price * qty;
            if (qty > 0) count++;
        });

        totalEl.textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
        countEl.textContent = count + (count === 1 ? ' item selected' : ' items selected');
        submitBtn.disabled  = count === 0;
        submitBtn.style.opacity = count === 0 ? '.4' : '1';
    }

    rows.forEach(row => {
        const input = row.querySelector('.qty-input');
        const dec   = row.querySelector('.dec');
        const inc   = row.querySelector('.inc');

        dec.addEventListener('click', () => {
            const v = parseFloat(input.value) || 0;
            if (v > 0) { input.value = Math.max(0, v - 0.5); recalc(); }
        });

        inc.addEventListener('click', () => {
            input.value = (parseFloat(input.value) || 0) + 0.5;
            recalc();
        });

        input.addEventListener('input', recalc);
    });

    // Filter out zero-quantity items before submit
    document.getElementById('order-form').addEventListener('submit', function(e) {
        const allQtyInputs = this.querySelectorAll('.qty-input');
        let hasItem = false;

        allQtyInputs.forEach(input => {
            if (!parseFloat(input.value)) {
                // Disable hidden rice_id sibling so it doesn't submit
                const parent = input.closest('.rice-row');
                const hiddenId = parent.querySelector('input[type="hidden"]');
                input.name        = '';
                hiddenId.name     = '';
            } else {
                hasItem = true;
            }
        });

        if (!hasItem) e.preventDefault();
    });
});
</script>
@endpush