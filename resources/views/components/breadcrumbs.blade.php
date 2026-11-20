@props(['items' => collect()])

@unless ($items->isEmpty())
<nav aria-label="breadcrumb" class="m-0 w-100 p-0">
    <ol class="breadcrumb m-0 w-100 justify-content-start p-0">
        @foreach ($items as $item)
        <li class="breadcrumb-item" @if($loop->last) aria-current="page" @endif>
            <a class="breadcrumb-link d-inline-flex align-items-center gap-1 @if($loop->last) active theme-primary @endif" href="{{ $item->url }}">
                @if($loop->first)
                <i class="bi bi-speedometer2"></i>
                @endif
                <span>{{ $item->title }}</span>
            </a>
        </li>
        @unless ($loop->last)
        <li class="breadcrumb-divider"></li>
        @endunless
        @endforeach
    </ol>
</nav>
@endunless