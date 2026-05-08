<div class="toast-host no-print" data-toast-host>
    @if(session('success'))
        <div class="toast-item toast-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast-item toast-error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="toast-item toast-error">{{ $errors->first() }}</div>
    @endif
</div>
