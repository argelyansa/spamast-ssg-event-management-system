@extends('layouts.app')

@section('title', 'Fines and Sanctions')

@section('content')
<div class="penalty-page">

    <div class="penalty-header">
        <div>
            <span class="penalty-eyebrow">STUDENT PORTAL</span>
            <h1>Fines and Sanctions</h1>
            <p>Monitor your attendance-related fines and assigned sanctions.</p>
        </div>
        <div class="penalty-header-icon">♧</div>
    </div>

    <div class="penalty-summary">
        <div class="penalty-summary-card">
            <div class="penalty-summary-icon">▤</div>
            <div>
                <span>Total Records</span>
                <strong>{{ $penalties->total() }}</strong>
            </div>
        </div>

        <div class="penalty-summary-card">
            <div class="penalty-summary-icon">₱</div>
            <div>
                <span>Total Fines</span>
                <strong>₱{{ number_format((float) $totalFines, 2) }}</strong>
            </div>
        </div>
    </div>

    <div class="penalty-section">
        <div class="penalty-section-heading">
            <div>
                <h2>My Records</h2>
                <p>Your recorded fines and sanctions appear here.</p>
            </div>
            <span class="penalty-record-count">{{ $penalties->total() }} records</span>
        </div>

        @forelse ($penalties as $penalty)
            <article class="penalty-record">
                <div class="penalty-record-top">
                    <div>
                        <span class="penalty-record-label">EVENT</span>
                        <h3>{{ $penalty->event?->title ?? 'Event' }}</h3>
                        <p>{{ $penalty->event?->event_date?->format('F d, Y') ?? 'Date unavailable' }}</p>
                    </div>
                    <span class="penalty-status">
                        {{ ucfirst($penalty->status ?? 'Pending') }}
                    </span>
                </div>

                <div class="penalty-record-details">
                    <div>
                        <span>Fine Amount</span>
                        <strong>₱{{ number_format((float) $penalty->amount, 2) }}</strong>
                    </div>
                    <div>
                        <span>Missed Scans</span>
                        <strong>{{ $penalty->missed_scans }}</strong>
                    </div>
                </div>

                <div class="penalty-sanction">
                    <span>Sanction</span>
                    <p>{{ $penalty->sanction ?: 'No sanction specified' }}</p>
                </div>
            </article>
        @empty
            <div class="penalty-empty">
                <div class="penalty-empty-icon">✓</div>
                <h3>You're all clear!</h3>
                <p>There are currently no fines or sanctions recorded on your account.</p>
                <span class="penalty-empty-note">Your records will appear here if any are assigned.</span>
            </div>
        @endforelse

        <div class="penalty-pagination">
            {{ $penalties->links() }}
        </div>
    </div>

    <a href="{{ route('student.dashboard') }}" class="penalty-back">
        ← Back to Dashboard
    </a>

</div>

<style>
.penalty-page {
    max-width: 1050px;
    margin: 0 auto;
    padding: 25px 20px 40px;
    color: #172b46;
}
.penalty-header {
    background: linear-gradient(120deg, #102b50, #1d4775);
    color: white;
    padding: 30px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}
.penalty-eyebrow {
    font-size: 11px;
    letter-spacing: 2px;
    font-weight: 700;
    color: #b8d5f5;
}
.penalty-header h1 {
    color: white;
    font-size: 28px;
    margin: 8px 0;
}
.penalty-header p {
    color: #dbeafe;
    margin: 0;
    font-size: 14px;
}
.penalty-header-icon {
    font-size: 45px;
    color: #dbeafe;
}
.penalty-summary {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 30px;
}
.penalty-summary-card {
    background: white;
    border: 1px solid #e3eaf2;
    border-radius: 13px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 3px 12px rgba(20, 45, 75, .04);
}
.penalty-summary-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #eaf2fc;
    color: #1d4775;
    display: grid;
    place-items: center;
    font-size: 23px;
    font-weight: 700;
}
.penalty-summary-card span,
.penalty-record-details span,
.penalty-sanction span {
    display: block;
    color: #718096;
    font-size: 12px;
    margin-bottom: 5px;
}
.penalty-summary-card strong {
    font-size: 23px;
    color: #17385f;
}
.penalty-section {
    background: white;
    border: 1px solid #e3eaf2;
    border-radius: 15px;
    padding: 24px;
}
.penalty-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}
.penalty-section-heading h2 {
    font-size: 19px;
    margin: 0 0 5px;
}
.penalty-section-heading p {
    color: #718096;
    font-size: 13px;
    margin: 0;
}
.penalty-record-count {
    background: #edf3fa;
    color: #244d79;
    border-radius: 20px;
    padding: 7px 12px;
    font-size: 12px;
    white-space: nowrap;
}
.penalty-record {
    border: 1px solid #e5ebf2;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 14px;
}
.penalty-record-top {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}
.penalty-record-label {
    font-size: 10px;
    letter-spacing: 1px;
    color: #718096;
    font-weight: 700;
}
.penalty-record-top h3 {
    font-size: 16px;
    margin: 6px 0;
}
.penalty-record-top p {
    color: #718096;
    font-size: 13px;
    margin: 0;
}
.penalty-status {
    background: #fff4dc;
    color: #946200;
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 12px;
    height: fit-content;
}
.penalty-record-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
    border-top: 1px solid #e9eef4;
    border-bottom: 1px solid #e9eef4;
    padding: 16px 0;
    margin: 17px 0;
}
.penalty-record-details strong {
    color: #17385f;
    font-size: 17px;
}
.penalty-sanction p {
    margin: 0;
    font-size: 14px;
}
.penalty-empty {
    border: 1px dashed #cbd8e7;
    background: #f8fafd;
    border-radius: 13px;
    padding: 42px 20px;
    text-align: center;
}
.penalty-empty-icon {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #e4f5eb;
    color: #24834c;
    display: grid;
    place-items: center;
    margin: 0 auto 15px;
    font-size: 30px;
    font-weight: 700;
}
.penalty-empty h3 {
    color: #17385f;
    font-size: 19px;
    margin: 0 0 8px;
}
.penalty-empty p {
    color: #64748b;
    font-size: 14px;
    margin: 0 0 10px;
}
.penalty-empty-note {
    color: #8492a6;
    font-size: 12px;
}
.penalty-pagination {
    margin-top: 18px;
}
.penalty-back {
    display: inline-block;
    margin-top: 22px;
    color: #1d4775;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}
.penalty-back:hover {
    text-decoration: underline;
}
@media (max-width: 600px) {
    .penalty-page { padding: 15px 12px 30px; }
    .penalty-header { padding: 22px 18px; }
    .penalty-header h1 { font-size: 23px; }
    .penalty-summary { grid-template-columns: 1fr; gap: 10px; }
    .penalty-section { padding: 17px 14px; }
    .penalty-section-heading { align-items: flex-start; flex-direction: column; }
    .penalty-record { padding: 15px; }
}
</style>
@endsection
