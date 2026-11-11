<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-end w-full py-2">
    <div>
        <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-gray-200 bg-white">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-400 bg-gray-50 cursor-default">
                  Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-blue-50 hover:text-blue-600 transition">
                  Sebelumnya
                </a>
            @endif

            {{-- Daftar Nomor Halaman (Dengan Pembatasan Elemen) --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-400 bg-white cursor-default">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-blue-600 cursor-default">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-blue-50 hover:text-blue-600 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-blue-50 hover:text-blue-600 transition">
                  Berikutnya
                </a>
            @else
                <span aria-disabled="true" class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-400 bg-gray-50 cursor-default">
                  Berikutnya
                </span>
            @endif
        </span>
    </div>
</nav>