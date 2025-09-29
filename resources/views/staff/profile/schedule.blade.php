@extends('layouts.staff.layout')

@section('title', 'Schedule - Letty\'s Birthing Home')
@section('page-title', 'My Schedule')

@section('content')

    <div class="container-fluid main-content">
        {{-- Monthly Calendar Section --}}
        <div class="calendar-section">
            <div class="calendar-header"
                style="display: flex; justify-content: space-between; align-items: center; 
            background: #4e9d76; padding: 10px 15px; position: relative; z-index: 10;">

                <div class="calendar-title"
                    style="color: white !important; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar-alt"></i> Monthly Shift Calendar
                </div>

                <div class="calendar-controls" style="display: flex; align-items: center; gap: 8px;">
                    <button class="nav-btn" onclick="previousMonth()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-btn" onclick="nextMonth()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="today-btn" onclick="goToToday()">Today</button>
                </div>
            </div>

            <div class="calendar-month">
                <div class="month-header">
                    <div class="month-title" id="monthTitle">August 2025</div>
                </div>
                <div class="calendar-grid" id="calendarGrid"></div>
            </div>
        </div>
    </div>

    {{-- Pass PHP data safely to JS --}}
    <script>
        window.staffData = {
            workDays: @json($staff->workDays->pluck('day')->toArray()),
            shiftType: '{{ $staff->staff_work_days->shift ?? 'day' }}'
        };
    </script>

    <script src="{{ asset('script/staff/schedule.js') }}"></script>
@endsection
