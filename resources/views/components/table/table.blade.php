@props([
    'headers' => [],
    'striped' => true,
    'hover' => true,
    'responsive' => true,
    'bordered' => false,
    'small' => false
])

@php
    $classes = 'table';
    if ($striped) $classes .= ' table-striped';
    if ($hover) $classes .= ' table-hover';
    if ($bordered) $classes .= ' table-bordered';
    if ($small) $classes .= ' table-sm';
@endphp

@if($responsive)
    <div class="table-responsive">
@endif

<table {{ $attributes->merge(['class' => $classes]) }}>
    @if(!empty($headers))
        <thead class="table-light">
            <tr>
                @foreach($headers as $header)
                    @if(is_array($header))
                        <th scope="col"
                            @if(isset($header['width'])) style="width: {{ $header['width'] }}" @endif
                            @if(isset($header['class'])) class="{{ $header['class'] }}" @endif>
                            {{ $header['label'] ?? $header['title'] }}
                            @if(isset($header['sortable']) && $header['sortable'])
                                <i class="fas fa-sort text-muted ms-1"></i>
                            @endif
                        </th>
                    @else
                        <th scope="col">{{ $header }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
    @endif

    <tbody>
        {{ $slot }}
    </tbody>

    @isset($footer)
        <tfoot>
            {{ $footer }}
        </tfoot>
    @endisset
</table>

@if($responsive)
    </div>
@endif