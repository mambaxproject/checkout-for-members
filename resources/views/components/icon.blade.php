@php
    $type = $type ?? '';
    $custom = $custom ?? '';
    $iconClasses = 'material-symbols-rounded';

    if ($type === 'fill') {
        $iconClasses = 'material-symbols-fill ' . $iconClasses;
    }

    if (!empty($custom)) {
        $iconClasses .= ' ' . $custom;
    }
@endphp

<i
    class="{{ $iconClasses }}"
    translate="no"
>
    {{ $icon }}
</i>
