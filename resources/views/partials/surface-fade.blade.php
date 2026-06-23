{{-- Gradient band between Surface A (ink) and Surface B (slate).
     Pass ['reverse' => true] to fade from slate back to ink.
     Pass ['height' => 'h-16'] to override the default band height. --}}
<div class="{{ $height ?? 'h-8' }} bg-gradient-to-b {{ ($reverse ?? false) ? 'from-slate to-ink' : 'from-ink to-slate' }}" aria-hidden="true"></div>
