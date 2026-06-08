<form action="{{ $action }}" method="POST" style="display:inline" onsubmit="return confirm(@json($message ?? 'Confirmer la suppression ?'));">
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $class ?? 'btn btn-danger' }}" title="{{ $title ?? trans('Supprimer') }}">
        {{ $slot }}
    </button>
</form>
