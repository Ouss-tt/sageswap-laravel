{{--
  Site announcements: a stack in the top-right corner, homepage and affiliate
  area only.

  Included once in the layout rather than page by page - App\Support\Announcement
  works out which surface the current route belongs to and hands back nothing
  for a page that carries no announcements, so this renders on exactly the two
  places the copy is written for.

  Dismissal is the site's usual CSS-only toggle: an sr-only checkbox in front of
  the notice it closes, hidden by the adjacent-sibling rule in app.css. The
  checkbox stays focusable, so the close control works from the keyboard even
  though the thing you click is a <label>. Nothing is stored, so a notice is
  back on the next page load - an outage that has not ended should keep saying
  so.
--}}
@php($announcements = \App\Support\Announcement::visible())

@if ($announcements)
  <div class="announce" role="region" aria-label="Site announcements">
    @foreach ($announcements as $announcement)
      {{-- The id is slugified in App\Support\Announcement, so this can only
           build [a-z0-9-] - safe as an id and as a label's `for`. --}}
      <input
        type="checkbox"
        id="announce-{{ $announcement['id'] }}"
        class="sr-only announce__toggle"
        aria-label="Dismiss announcement{{ $announcement['title'] ? ': '.$announcement['title'] : '' }}"
      />
      {{-- The tone comes from the same class, which narrows it to the three
           .announce__item--* colours defined in app.css. --}}
      <article class="announce__item announce__item--{{ $announcement['tone'] }}">
        @if ($announcement['title'])
          <p class="announce__title">{{ $announcement['title'] }}</p>
        @endif
        <p class="announce__body">{{ $announcement['body'] }}</p>
        {{-- Hidden from screen readers: the checkbox above already carries the
             label, and announcing both reads the control out twice. --}}
        <label for="announce-{{ $announcement['id'] }}" class="announce__close" aria-hidden="true">&times;</label>
      </article>
    @endforeach
  </div>
@endif
