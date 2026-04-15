@extends('layouts.rice-app')

@section('content')

<div class="page-header flex items-center justify-between">
    <div>
        <p class="page-eyebrow">Management</p>
        <h1 class="page-title">Rice Menu</h1>
    </div>
    <button onclick="document.getElementById('add-modal').style.display='flex'" class="btn btn-primary">
        + Add Rice
    </button>
</div>

<div class="card">
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Unit</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ricemenu as $rice)
            <tr id="row-{{ $rice->id }}">
                <td class="text-muted">{{ str_pad($rice->id, 3, '0', STR_PAD_LEFT) }}</td>

                {{-- View state --}}
                <td class="view-name-{{ $rice->id }}">
                    <span style="font-family: var(--ff-serif); font-size: 1rem; font-weight: 300;">
                        {{ $rice->name }}
                    </span>
                </td>
                <td class="view-price-{{ $rice->id }}">₱{{ number_format($rice->price, 2) }}</td>
                <td class="view-unit-{{ $rice->id }} text-muted">{{ $rice->unit ?? '—' }}</td>

                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 8px;">
                        <button onclick="openEdit({{ $rice->id }}, '{{ addslashes($rice->name) }}', {{ $rice->price }}, '{{ $rice->unit ?? '' }}')"
                            class="btn btn-ghost btn-sm">Edit</button>

                        <form method="POST" action="{{ route('rice.destroy', $rice->id) }}"
                            onsubmit="return confirm('Remove {{ addslashes($rice->name) }}?')"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--muted);">
                    No rice items yet. Add your first one.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- ── Add Modal ── --}}
<div id="add-modal" style="
    display: none; position: fixed; inset: 0; z-index: 200;
    background: rgba(28,26,22,.5); align-items: center; justify-content: center;
">
    <div style="background: var(--surface); border: 1px solid var(--border); padding: 32px; width: 420px; max-width: calc(100vw - 32px);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <span style="font-family: var(--ff-serif); font-size: 1.3rem; font-weight: 300;">New Rice Item</span>
            <button onclick="document.getElementById('add-modal').style.display='none'"
                style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); line-height: 1;">×</button>
        </div>

        <form method="POST" action="{{ route('rice.store') }}">
            @csrf
            <div class="field">
                <label>Name</label>
                <input type="text" name="name" placeholder="e.g. Sinandomeng Premium" required autofocus>
            </div>
            <div class="grid-2">
                <div class="field">
                    <label>Price (₱)</label>
                    <input type="number" name="price" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Unit</label>
                    <input type="text" name="unit" placeholder="kg / sack / cup">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px;">
                <button type="button" onclick="document.getElementById('add-modal').style.display='none'"
                    class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Item</button>
            </div>
        </form>
    </div>
</div>


{{-- ── Edit Modal ── --}}
<div id="edit-modal" style="
    display: none; position: fixed; inset: 0; z-index: 200;
    background: rgba(28,26,22,.5); align-items: center; justify-content: center;
">
    <div style="background: var(--surface); border: 1px solid var(--border); padding: 32px; width: 420px; max-width: calc(100vw - 32px);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <span style="font-family: var(--ff-serif); font-size: 1.3rem; font-weight: 300;">Edit Rice Item</span>
            <button onclick="document.getElementById('edit-modal').style.display='none'"
                style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); line-height: 1;">×</button>
        </div>

        <form method="POST" id="edit-form" action="">
            @csrf
            @method('PATCH')
            <div class="field">
                <label>Name</label>
                <input type="text" name="name" id="edit-name" required>
            </div>
            <div class="grid-2">
                <div class="field">
                    <label>Price (₱)</label>
                    <input type="number" name="price" id="edit-price" step="0.01" min="0" required>
                </div>
                <div class="field">
                    <label>Unit</label>
                    <input type="text" name="unit" id="edit-unit" placeholder="kg / sack / cup">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px;">
                <button type="button" onclick="document.getElementById('edit-modal').style.display='none'"
                    class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEdit(id, name, price, unit) {
    document.getElementById('edit-form').action = '/rice/' + id;
    document.getElementById('edit-name').value  = name;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-unit').value  = unit;
    document.getElementById('edit-modal').style.display = 'flex';
}

// Close modals on backdrop click
['add-modal', 'edit-modal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>
@endpush