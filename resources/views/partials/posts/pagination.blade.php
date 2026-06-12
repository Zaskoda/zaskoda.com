@if ($posts->hasPages())
    <nav class="flex justify-between items-center mt-12" aria-label="Pagination">
        <div>
            @if ($posts->previousPageUrl())
                <a href="{{ $posts->previousPageUrl() }}" class="font-ui text-sm">&larr; Newer posts</a>
            @endif
        </div>
        <p class="meta-text text-xs">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</p>
        <div>
            @if ($posts->nextPageUrl())
                <a href="{{ $posts->nextPageUrl() }}" class="font-ui text-sm">Older posts &rarr;</a>
            @endif
        </div>
    </nav>
@endif
