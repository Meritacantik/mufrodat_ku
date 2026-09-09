@php $warnaAktif = request()->is('admin*') ? 'var(--red)' : 'var(--green)'; @endphp
@if ($paginator->hasPages())
    <nav style="display:flex;align-items:center;gap:4px;flex-wrap:wrap">
        {{-- Tombol Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span style="padding:5px 10px;border:1px solid var(--gray-200);border-radius:var(--rs);font-size:12px;color:var(--gray-300)">
                <x-icon name="chevron-left" size="12"/>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:5px 10px;border:1px solid var(--gray-200);border-radius:var(--rs);font-size:12px;text-decoration:none;color:var(--gray-700)">
                <x-icon name="chevron-left" size="12"/>
            </a>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:5px 8px;font-size:12px;color:var(--gray-400)">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:5px 10px;border:1px solid {{ $warnaAktif }};background:{{ $warnaAktif }};color:white;border-radius:var(--rs);font-size:12px">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:5px 10px;border:1px solid var(--gray-200);border-radius:var(--rs);font-size:12px;text-decoration:none;color:var(--gray-700)">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Selanjutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:5px 10px;border:1px solid var(--gray-200);border-radius:var(--rs);font-size:12px;text-decoration:none;color:var(--gray-700)">
                <x-icon name="chevron-right" size="12"/>
            </a>
        @else
            <span style="padding:5px 10px;border:1px solid var(--gray-200);border-radius:var(--rs);font-size:12px;color:var(--gray-300)">
                <x-icon name="chevron-right" size="12"/>
            </span>
        @endif

        <span style="font-size:11px;color:var(--gray-400);margin-left:8px">
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} dari {{ $paginator->total() }}
        </span>
    </nav>
@endif
