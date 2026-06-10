@php
    $techSlugs = ['blockchain', 'software', 'open-source', 'iot-robotics', 'game'];
    $isTech = in_array($term->slug(), $techSlugs);
@endphp
<span class="{{ $isTech ? 'badge-tech' : 'badge-warm' }}">{{ $term->title }}</span>
