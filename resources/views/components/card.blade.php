@props([
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
    'class' => ''
])

<div class="card {{ $class }}">
    @if($title || $subtitle || $header)
        <div class="card-header">
            @if($header)
                {{ $header }}
            @else
                @if($title)
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                @endif
                @if($subtitle)
                    <p class="card-text text-muted small mb-0">{{ $subtitle }}</p>
                @endif
            @endif
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>