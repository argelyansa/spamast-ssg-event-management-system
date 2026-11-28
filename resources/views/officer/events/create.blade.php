
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
        <h2 class="fw-bold mb-1" style="color: #0B1120;">Create New Event</h2>
        <p class="text-muted mb-0">Enter the details of the SSG event below.</p>
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
                <i class="bi bi-calendar-event me-2"></i>Event Information
            </h5>
            <small style="color: #CBD5E1;">Provide the necessary information for this event.</small>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('officer.events.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-12">
                        <label for="title" class="form-label fw-semibold">Event Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="Enter event title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Provide a brief description of the event...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="venue" class="form-label fw-semibold">Venue <span class="text-danger">*</span></label>
                        <input type="text" id="venue" name="venue"
                               class="form-control form-control-lg @error('venue') is-invalid @enderror"
                               value="{{ old('venue') }}"
                               placeholder="Enter event venue" required>
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="event_date" class="form-label fw-semibold">Event Date <span class="text-danger">*</span></label>
                        <input type="date" id="event_date" name="event_date"
                               class="form-control form-control-lg @error('event_date') is-invalid @enderror"
                               value="{{ old('event_date') }}" required>
                        @error('event_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="day_type" class="form-label fw-semibold">Day Type <span class="text-danger">*</span></label>
                        <select id="day_type" name="day_type"
                                class="form-select form-select-lg @error('day_type') is-invalid @enderror" required>
                            <option value="">Select day type</option>
                            <option value="whole_day" {{ old('day_type') === 'whole_day' ? 'selected' : '' }}>Whole Day</option>
                            <option value="half_day" {{ old('day_type') === 'half_day' ? 'selected' : '' }}>Half Day</option>
                        </select>
                        @error('day_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="availability" class="form-label fw-semibold">Attendance Availability <span class="text-danger">*</span></label>
                        <select id="availability" name="availability"
                                class="form-select form-select-lg @error('availability') is-invalid @enderror" required>
                            <option value="scheduled" {{ old('availability', 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="manual" {{ old('availability') === 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('availability')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Scheduled events use time windows. Manual events are opened and closed by an Officer.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="fine_per_miss" class="form-label fw-semibold">Fine Per Miss (₱) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light fw-semibold">₱</span>
                            <input type="number" id="fine_per_miss" name="fine_per_miss"
                                   class="form-control @error('fine_per_miss') is-invalid @enderror"
                                   value="{{ old('fine_per_miss', '0.00') }}"
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
                           value="1" {{ old('show_on_homepage') ? 'checked' : '' }}>
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
                    <h4 class="fw-bold mb-1" style="color: #0B1120;">
                        <i class="bi bi-clock-history me-2"></i>Event Sessions
                    </h4>
                    <p class="text-muted mb-0">
                        Set the attendance sessions and their time windows.
                    </p>
                </div>

                <div id="sessions-container">
                    <div class="session-fields card border rounded-4 p-4 mb-3"
                         data-period="morning">
                        <h5 class="fw-bold mb-3" style="color: #1E40AF;">
                            <i class="bi bi-sunrise me-2"></i>Morning Session
                        </h5>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-in Start</label>
                                <input type="time" name="sessions[morning][check_in_start]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.morning.check_in_start') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-in End</label>
                                <input type="time" name="sessions[morning][check_in_end]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.morning.check_in_end') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-out Start</label>
                                <input type="time" name="sessions[morning][check_out_start]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.morning.check_out_start') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-out End</label>
                                <input type="time" name="sessions[morning][check_out_end]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.morning.check_out_end') }}">
                            </div>
                        </div>
                    </div>

                    <div class="session-fields card border rounded-4 p-4 mb-3"
                         data-period="afternoon">
                        <h5 class="fw-bold mb-3" style="color: #1E40AF;">
                            <i class="bi bi-sunset me-2"></i>Afternoon Session
                        </h5>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-in Start</label>
                                <input type="time" name="sessions[afternoon][check_in_start]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.afternoon.check_in_start') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-in End</label>
                                <input type="time" name="sessions[afternoon][check_in_end]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.afternoon.check_in_end') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-out Start</label>
                                <input type="time" name="sessions[afternoon][check_out_start]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.afternoon.check_out_start') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-out End</label>
                                <input type="time" name="sessions[afternoon][check_out_end]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.afternoon.check_out_end') }}">
                            </div>
                        </div>
                    </div>

                    <div class="session-fields card border rounded-4 p-4 mb-3"
                         data-period="half_day">
                        <h5 class="fw-bold mb-3" style="color: #1E40AF;">
                            <i class="bi bi-calendar-day me-2"></i>Half-Day Session
                        </h5>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-in Start</label>
                                <input type="time" name="sessions[half_day][check_in_start]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.half_day.check_in_start') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-in End</label>
                                <input type="time" name="sessions[half_day][check_in_end]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.half_day.check_in_end') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-out Start</label>
                                <input type="time" name="sessions[half_day][check_out_start]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.half_day.check_out_start') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Check-out End</label>
                                <input type="time" name="sessions[half_day][check_out_end]"
                                       class="form-control session-time"
                                       value="{{ old('sessions.half_day.check_out_end') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    For scheduled events, all four time fields in each active session are required.
                    Each end time must be later than its corresponding start time.
                    For manual events, the Officer controls when check-in and check-out are open.
                </div>

                <hr class="my-5">

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                    <a href="{{ route('officer.events.index') }}"
                       class="btn btn-light border px-4 py-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold"
                            style="background: #2563EB; border-color: #2563EB;">
                        <i class="bi bi-check-circle me-1"></i> Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dayType = document.getElementById('day_type');
    const availability = document.getElementById('availability');
    const sessionCards = document.querySelectorAll('.session-fields');

    function updateSessions() {
        const selectedDayType = dayType.value;
        const isScheduled = availability.value === 'scheduled';

        sessionCards.forEach(function (card) {
            const period = card.dataset.period;

            const shouldShow =
                (selectedDayType === 'whole_day' &&
                    (period === 'morning' || period === 'afternoon')) ||
                (selectedDayType === 'half_day' && period === 'half_day');

            card.style.display = shouldShow ? '' : 'none';

            card.querySelectorAll('.session-time').forEach(function (input) {
                input.disabled = !shouldShow;
                input.required = shouldShow && isScheduled;
            });
        });
    }

    dayType.addEventListener('change', updateSessions);
    availability.addEventListener('change', updateSessions);

    updateSessions();
});
</script>
@endsection
