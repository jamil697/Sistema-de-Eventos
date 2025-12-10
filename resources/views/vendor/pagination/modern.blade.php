@if ($paginator->hasPages())
    <nav class="d-flex align-items-center">
        <ul class="pagination pagination-modern mb-0">
            
            {{-- Botón Anterior --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Elementos de Paginación --}}
            @foreach ($elements as $element)
                {{-- Separador "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array de Enlaces --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botón Siguiente --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- ESTILOS CSS INTEGRADOS PARA QUE FUNCIONE DIRECTO --}}
    <style>
        .pagination-modern .page-link {
            border: none;
            border-radius: 50%; /* Círculos perfectos */
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 4px; /* Espacio entre bolitas */
            color: #6c757d;
            font-weight: 700;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Sombra suave */
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Rebote suave */
        }

        .pagination-modern .page-link:hover {
            background-color: #f8f9fa;
            color: #4f46e5; /* Color primario al hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        /* Estado ACTIVO (La bolita seleccionada) */
        .pagination-modern .page-item.active .page-link {
            /* El mismo gradiente de tu banner */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4); /* Resplandor */
            transform: scale(1.1); /* Un poco más grande */
        }

        /* Estado DESHABILITADO (Flechas cuando no hay más) */
        .pagination-modern .page-item.disabled .page-link {
            background-color: transparent;
            color: #dee2e6;
            box-shadow: none;
            cursor: default;
        }
    </style>
@endif