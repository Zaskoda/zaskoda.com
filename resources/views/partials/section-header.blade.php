{{--
    Section heading with optional inline "view all" action.

    Params:
    - title         (required) heading text
    - action_label  (optional) e.g. "View all work"
    - action_url    (optional) where the action links

    Heading and action share one baseline row; on narrow screens the action
    wraps below the heading, left-aligned.
--}}
<div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1 border-b border-circuit-500/40 pb-3">
    <h2 class="font-display font-bold text-4xl md:text-5xl text-cowboy-100">{{ $title }}</h2>
    @if (!empty($action_label) && !empty($action_url))
        <a href="{{ $action_url }}" class="font-ui text-sm md:text-[1.75rem] md:leading-tight whitespace-nowrap">{{ $action_label }} &rarr;</a>
    @endif
</div>
