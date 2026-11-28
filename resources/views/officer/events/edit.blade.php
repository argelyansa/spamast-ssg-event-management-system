
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('officer.events.index') }}"
           class="text-decoration-none fw-semibold"
           style="color: #3B82F6;">
            <i class="bi bi-arrow-left me-1"></i> Back to Events
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold mb-1" style="color: #0B1120;">Edit Event</h2>
        <p class="text-muted mb-0">Update the information and attendance sessions for this SSG event.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header border-0 rounded-top-4 p-4"
             style="background: #111C32;">
            <h5 class="text-white fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>Event Information
            </h5>
            <small style="color: #CBD5E1;">Modify the event details and session schedules below.</small>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('officer.events.update', $event) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12">
                        <label for="title" class="form-label fw-semibold">Event Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $event->title) }}"
                               placeholder="Enter event title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Provide a brief description of the event...">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="venue" class="form-label fw-semibold">Venue <span class="text-danger">*</span></label>
                        <input type="text" id="venue" name="venue"
                               class="form-control form-control-lg @error('venue') is-invalid @enderror"
                               value="{{ old('venue', $event->venue) }}"
                               placeholder="Enter event venue" required>
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="event_date" class="form-label fw-semibold">Event Date <span class="text-danger">*</span></label>
                        <input type="date" id="event_date" name="event_date"
                               class="form-control form-control-lg @error('event_date') is-invalid @enderror"
                               value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                        @error('event_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="day_type" class="form-label fw-semibold">Day Type <span class="text-danger">*</span></label>
                        <select id="day_type" name="day_type"
                                class="form-select form-select-lg @error('day_type') is-invalid @enderror" required>
                            <option value="">Select day type</option>
                            <option value="whole_day" {{ old('day_type', $event->day_type) === 'whole_day' ? 'selected' : '' }}>Whole Day</option>
                            <option value="half_day" {{ old('day_type', $event->day_type) === 'half_day' ? 'selected' : '' }}>Half Day</option>
                        </select>
                        @error('day_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Changing the day type is blocked if attendance records already exist.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="availability" class="form-label fw-semibold">Attendance Availability <span class="text-danger">*</span></label>
                        <select id="availability" name="availability"
                                class="form-select form-select-lg @error('availability') is-invalid @enderror" required>
                            <option value="scheduled" {{ old('availability', $event->availability) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="manual" {{ old('availability', $event->availability) === 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('availability')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Scheduled uses time windows. Manual uses Officer-controlled buttons.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="fine_per_miss" class="form-label fw-semibold">Fine Per Miss (₱) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light fw-semibold">₱</span>
                            <input type="number" id="fine_per_miss" name="fine_per_miss"
                                   class="form-control @error('fine_per_miss') is-invalid @enderror"
                                   value="{{ old('fine_per_miss', $event->fine_per_miss) }}"
                                   min="0" max="999999.99" step="0.01" required>
                            @error('fine_per_miss')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Enter 0.00 if no fine will be imposed.</small>
                    </div>
                </div>

                <div class="form-check form-switch mt-4">
    <input class="form-check-input" type="checkbox"
           id="show_on_homepage" name="show_on_homepage"
           value="1" {{ old('show_on_homepage', $event->show_on_homepage) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="show_on_homepage">
        Show on Public Homepage
    </label>
    <div class="form-text">
        Enable this to display the event details on the public SPAMAST SSG homepage.
        Attendance records, sessions, and fines will never be displayed publicly.
    </div>
</div>
<hr class="my-5">

                <div class="mb-4">
                    <h5 class="fw-bold mb-1" style="color: #0B1120;">
                        <i class="bi bi-clock-history me-2"></i>Attendance Sessions
                    </h5>
                    <p class="text-muted mb-0">
                        Configure check-in and check-out times for each active session.
                    </p>
                </div>

                @php
                    $sessionPeriods = [
                        'morning' => 'Morning Session',
                        'afternoon' => 'Afternoon Session',
                        'half_day' => 'Half-Day Session',
                    ];

                    $timeFields = [
                        'check_in_start' => 'Check-in Start',
                        'check_in_end' => 'Check-in End',
                        'check_out_start' => 'Check-out Start',
                        'check_out_end' => 'Check-out End',
                    ];
                @endphp

                <div class="row g-4">
                    @foreach($sessionPeriods as $period => $periodLabel)
                        @php
                            $session = $event->sessions->firstWhere('period', $period);

                            $isActive = $period === 'half_day'
                                ? $event->day_type === 'half_day'
                                : $event->day_type === 'whole_day';
                        @endphp

                        <div class="col-12 session-fields"
                             data-period="{{ $period }}">
                            <div class="card border rounded-3 h-100">
                                <div class="card-header border-0 p-3"
                                     style="background: #F1F5F9;">
                                    <h6 class="fw-bold mb-0" style="color: #111C32;">
                                        {{ $periodLabel }}
                                    </h6>
                                </div>

                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        @foreach($timeFields as $field => $fieldLabel)
                                            @php
                                                $storedValue = $session?->{$field};

                                                $defaultValue = $storedValue
                                                    ? substr((string) $storedValue, 0, 5)
                                                    : '';
                                            @endphp

                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold"
                                                       for="sessions_{{ $period }}_{{ $field }}">
                                                    {{ $fieldLabel }}
                                                </label>

                                                <input type="time"
                                                       id="sessions_{{ $period }}_{{ $field }}"
                                                       name="sessions[{{ $period }}][{{ $field }}]"
                                                       class="form-control session-time"
                                                       data-field="{{ $field }}"
                                                       value="{{ old("sessions.$period.$field", $defaultValue) }}">

                                                @error("sessions.$period.$field")
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="alert alert-info border-0 mt-4 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong>
                    Scheduled availability requires all four time fields for each active session.
                    Manual availability lets the Officer control check-in and check-out using the event details page.
                </div>

                <hr class="my-5">

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                    <a href="{{ route('officer.events.index') }}"
                       class="btn btn-light border px-4 py-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold"
                            style="background: #2563EB; border-color: #2563EB;">
                        <i class="bi bi-check-circle me-1"></i> Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dayTypeSelect = document.getElementById('day_type');
    const availabilitySelect = document.getElementById('availability');

    const sessionCards = document.querySelectorAll('.session-fields');
    const timeInputs = document.querySelectorAll('.session-time');

    function updateSessionFields() {
        const dayType = dayTypeSelect.value;
        const availability = availabilitySelect.value;

        sessionCards.forEach(function (card) {
            const period = card.dataset.period;

            const isActive = period === 'half_day'
                ? dayType === 'half_day'
                : dayType === 'whole_day';

            card.classList.toggle('d-none', !isActive);

            card.querySelectorAll('.session-time').forEach(function (input) {
                input.disabled = !isActive;

                input.required = isActive && availability === 'scheduled';
            });
        });
    }

    dayTypeSelect.addEventListener('change', updateSessionFields);
    availabilitySelect.addEventListener('change', updateSessionFields);

    updateSessionFields();
});
</script>
@endsection
