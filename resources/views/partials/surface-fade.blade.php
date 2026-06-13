{{-- Gradient band between Surface A (ink) and Surface B (slate).
     Pass ['reverse' => true] to fade from slate back to ink. --}}
<div class="h-16 bg-gradient-to-b {{ ($reverse ?? false) ? 'from-slate to-ink' : 'from-ink to-slate' }}" aria-hidden="true"></div>
