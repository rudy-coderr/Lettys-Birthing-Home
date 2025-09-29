@if($emergencies->isNotEmpty())

<div class="modal fade emergency-modal" id="emergencyModal" tabindex="-1" aria-labelledby="emergencyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            {{-- Header --}}
            <div class="modal-header justify-content-center"
                style="background-color: #dc3545; color: white; border-bottom: none; animation: pulseBorder 2s infinite;">
                <h5 class="modal-title" id="emergencyModalLabel">
                    <i class="fas fa-baby fa-shake" style="animation-duration: 0.5s;"></i> Emergency Labor Alert
                </h5>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding: 20px; text-align: left; background-color: #fff5f5;">
                <p class="mb-4 d-flex align-items-start" style="font-size: 16px; font-weight: 500; color: #2c3e50;">
                    <i class="fas fa-exclamation-circle me-2" style="margin-top: 3px;"></i>
                    <span>The following mothers are expected to give birth today and have contacted the chatbot:</span>
                </p>

                <ul class="list-group list-group-flush">
                    @foreach ($emergencies as $emergency)
                        <li class="list-group-item emergency-item d-flex justify-content-between align-items-center"
                            style="padding: 15px; border-radius: 8px; margin-bottom: 10px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);"
                            id="emergency-{{ $emergency->id }}">

                            <div>
                                <div style="font-weight: 600; color: #2c3e50;">
                                    <i class="fas fa-user me-2"></i>{{ $emergency->name ?? 'Unknown' }}
                                </div>
                                <div style="font-size: 14px; color: #6c757d;">
                                    <i class="fas fa-hospital me-2"></i>{{ $emergency->branch->branch_name ?? 'No Branch' }}
                                </div>
                                <div style="font-size: 14px; color: #6c757d;">
                                    <i class="fas fa-clock me-2"></i>
                                    Time Chat:
                                    @if ($emergency->created_at)
                                        {{ $emergency->created_at->format('M d, Y h:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <button class="btn btn-outline-danger btn-sm acknowledge-btn"
                                data-id="{{ $emergency->id }}">
                                <i class="fas fa-check me-2"></i>Acknowledge
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Alert Sound --}}
<audio id="alertSound" preload="auto" loop>
    <source src="{{ asset('sound/alert.wav') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
@endif
