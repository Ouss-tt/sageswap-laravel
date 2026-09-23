{{--
  The pager under a data table.

  PREV / NEXT with the position between them, rather than a row of numbered
  links: the numbers wrap badly on a phone at this type size, and nobody jumps
  to page 7 of their own referral history.

  Both ends stay on the page when they are unavailable, as disabled text. A
  control that disappears at the edge of the range makes the row jump about as
  you page through it.
--}}
@if ($paginator->hasPages())
  <nav class="pager" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
      <span class="pager__link is-disabled" aria-disabled="true">PREV</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="pager__link" rel="prev">PREV</a>
    @endif

    <span class="pager__count">
      PAGE {{ $paginator->currentPage() }} OF {{ $paginator->lastPage() }}
    </span>

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="pager__link" rel="next">NEXT</a>
    @else
      <span class="pager__link is-disabled" aria-disabled="true">NEXT</span>
    @endif
  </nav>
@endif
