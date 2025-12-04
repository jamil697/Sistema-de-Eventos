<h3>Asignar Recurso</h3>

<form action="{{ route('admin.eventos.asignarRecurso', $evento->id) }}" method="POST">
    @csrf

    <label>Recurso:</label>
    <select name="recurso_id" required>
        @foreach ($recursos as $recurso)
            <option value="{{ $recurso->id }}">{{ $recurso->nombre }}</option>
        @endforeach
    </select>

    <label>Cantidad:</label>
    <input type="number" name="cantidad" min="1" required>

    <button type="submit">Asignar</button>
</form>