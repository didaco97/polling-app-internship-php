@extends('layouts.app')

@section('title', 'Active Polls')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📊 Active Polls</h5>
                <span class="badge bg-light text-primary" id="poll-count">{{ count($polls) }}</span>
            </div>
            <div class="list-group list-group-flush" id="poll-list">
                @foreach($polls as $poll)
                <a href="#" class="list-group-item list-group-item-action poll-item" data-id="{{ $poll->id }}">
                    {{ $poll->question }}
                </a>
                @endforeach
            </div>
        </div>
        
        <!-- Admin Panel -->
        <div class="card mt-3" id="admin-panel" style="display: none;">
            <div class="card-header bg-warning">
                <h6 class="mb-0">⚙️ Admin: Manage Voters</h6>
            </div>
            <div class="card-body">
                <div id="voter-list"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div id="poll-content">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <h4>👈 Select a poll to view</h4>
                    <p>Click on any poll from the list to see options and vote.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vote History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="history-content">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}"></script>
@endsection
