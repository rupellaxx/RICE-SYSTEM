@extends('layouts.rice-app')

@section('content')

<div class="page-header flex items-center justify-between">
    <div>
        <p class="page-eyebrow">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
        <h1 class="page-title">Order Details</h1>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-ghost">← Back</a>
</div>


<div style="display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: start;">

    {{-- ── Left: Items ── --}}
    <div>
        <div class="card">
            <div class="card-title">
                <span>Items</span>
                @if(!$order->payment)
                    <button onclick="document.getElementById('add-item-modal').style.display='flex'"
                        style="background: none; border: none; cursor: pointer; font-size: 10px;
                               letter-spacing: .15em; text-transform: uppercase; color: var(--accent);
                               font-family: var(--ff-mono); font-weight: 300; padding: 0;">
                        + Add Item
                    </button>
                @endif
            </div>

            <table class="tbl">
                <thead>
                    <tr>
                        <th>Rice</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        @if(!$order->payment)<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                    <tr>
                        <td>
                            <span style="font-family: var(--ff-serif); font-size: 1rem; font-weight: 300;">
                                {{ $item->rice->name }}
                            </span>
                        </td>
                        <td class="text-muted">₱{{ number_format($item->price, 2) }}</td>
                        <td>
                            @if(!$order->payment)
                                <form method="POST" action="{{ route('order-items.update', $item->id) }}"
                                    style="display: inline-flex; align-items: center; gap: 0; border: 1px solid var(--border);">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity"
                                        value="{{ $item->quantity }}"
                                        min="0.5" step="0.5"
                                        style="width: 56px; border: none; text-align: center;
                                               padding: 6px 4px; background: var(--surface);
                                               font-size: 13px; font-family: var(--ff-mono);"
                                        onchange="this.form.submit()">
                                </form>
                            @else
                                {{ $item->quantity }}
                            @endif
                        </td>
                        <td>₱{{ number_format($item->total, 2) }}</td>
                        @if(!$order->payment)
                        <td style="text-align: right;">
                            <form method="POST" action="{{ route('order-items.destroy', $item->id) }}"
                                onsubmit="return confirm('Remove item?')"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">×</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 32px; color: var(--muted);">
                            No items in this order.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border);
                        display: flex; justify-content: flex-end; align-items: baseline; gap: 16px;">
                <span style="font-size: 9px; letter-spacing: .2em; text-transform: uppercase; color: var(--muted);">
                    Order Total
                </span>
                <span style="font-family: var(--ff-serif); font-size: 2rem; font-weight: 300; color: var(--text);">
                    ₱{{ number_format($order->total_amount, 2) }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Right: Summary + Payment ── --}}
    <div style="display: flex; flex-direction: column; gap: 16px;">

        {{-- Order Info --}}
        <div class="card">
            <div class="card-title"><span>Summary</span></div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Order ID</span>
                    <span>#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Date</span>
                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Time</span>
                    <span>{{ $order->created_at->format('g:i a') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Items</span>
                    <span>{{ $order->items->count() }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--border);">
                    <span class="text-muted">Status</span>
                    @if($order->payment)
                        <span class="badge badge-green">Paid</span>
                    @else
                        <span class="badge badge-orange">Unpaid</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment --}}
        @if(!$order->payment)
        <div class="card">
            <div class="card-title"><span>Payment</span></div>

            <p style="font-size: 11px; color: var(--muted); margin-bottom: 16px; line-height: 1.6;">
                Confirm payment of
                <strong style="color: var(--text); font-weight: 400;">
                    ₱{{ number_format($order->total_amount, 2) }}
                </strong>
                for this order.
            </p>

            <form method="POST" action="{{ route('payments.store') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="amount" value="{{ $order->total_amount }}">
                <button type="submit" class="btn btn-primary w-full"
                    onclick="return confirm('Mark order #{{ str_pad($order->id, 4, \'0\', STR_PAD_LEFT) }} as paid?')"
                    style="width: 100%; justify-content: center;">
                    Mark as Paid
                </button>
            </form>
        </div>
        @else
        <div class="card">
            <div class="card-title"><span>Payment</span></div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Amount Paid</span>
                    <span>₱{{ number_format($order->payment->amount, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Paid On</span>
                    <span>{{ $order->payment->created_at->format('M d, g:i a') }}</span>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>


{{-- ── Add Item Modal ── --}}
<div id="add-item-modal" style="
    display: none; position: fixed; inset: 0; z-index: 200;
    background: rgba(28,26,22,.5); align-items: center; justify-content: center;
">
    <div style="background: var(--surface); border: 1px solid var(--border); padding: 32px; width: 380px; max-width: calc(100vw - 32px);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <span style="font-family: var(--ff-serif); font-size: 1.3rem; font-weight: 300;">Add Item</span>
            <button onclick="document.getElementById('add-item-modal').style.display='none'"
                style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); line-height: 1;">×</button>
        </div>

        <form method="POST" action="{{ route('order-items.store') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="field">
                <label>Rice</label>
                <select name="rice_id" required>
                    <option value="">— Select rice —</option>
                    @foreach($ricemenu as $rice)
                        <option value="{{ $rice->id }}">{{ $rice->name }} — ₱{{ number_format($rice->price, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Quantity</label>
                <input type="number" name="quantity" min="0.5" step="0.5" value="1" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px;">
                <button type="button" onclick="document.getElementById('add-item-modal').style.display='none'"
                    class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('add-item-modal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endpush