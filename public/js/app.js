/**
 * Polling App JavaScript
 * Handles all AJAX interactions for voting, results, and admin
 */

var currentPollId = null;
var resultInterval = null;

$(document).ready(function() {
    // Poll item click handler
    $(document).on('click', '.poll-item', function(e) {
        e.preventDefault();
        var pid = $(this).data('id');
        showPoll(pid);
    });

    // Vote option click handler
    $(document).on('click', '.vote-option:not(.disabled)', function() {
        var oid = $(this).data('id');
        submitVote(currentPollId, oid);
    });
});

// Load and display a poll
function showPoll(pid) {
    currentPollId = pid;
    
    // Update active state
    $('.poll-item').removeClass('active');
    $('.poll-item[data-id="' + pid + '"]').addClass('active');
    
    $.ajax({
        url: '/polls/' + pid,
        type: 'GET',
        success: function(data) {
            renderPoll(data);
            loadVoters(pid);
            startResultUpdates(pid);
        },
        error: function() {
            showAlert('Failed to load poll', 'danger');
        }
    });
}

// Render poll content
function renderPoll(data) {
    var poll = data.poll;
    var hasVoted = data.hasVoted;
    var results = data.results;
    
    var html = '<div class="card">';
    html += '<div class="card-header bg-primary text-white">';
    html += '<h5 class="mb-0">' + poll.question + '</h5>';
    html += '</div>';
    html += '<div class="card-body">';
    
    // Calculate total votes
    var totalVotes = 0;
    results.forEach(function(r) { totalVotes += parseInt(r.votes); });
    
    // Options or results
    poll.options.forEach(function(opt) {
        var optResult = results.find(function(r) { return r.id == opt.id; }) || { votes: 0 };
        var votes = parseInt(optResult.votes);
        var percent = totalVotes > 0 ? Math.round((votes / totalVotes) * 100) : 0;
        
        if (hasVoted) {
            // Show results
            html += '<div class="mb-3">';
            html += '<div class="d-flex justify-content-between mb-1">';
            html += '<span>' + opt.option_text + '</span>';
            html += '<span id="opt-' + opt.id + '-count">' + votes + ' votes (' + percent + '%)</span>';
            html += '</div>';
            html += '<div class="result-container">';
            html += '<div class="result-bar" id="opt-' + opt.id + '-bar" style="width: ' + percent + '%"></div>';
            html += '</div>';
            html += '</div>';
        } else {
            // Show vote options
            html += '<div class="vote-option" data-id="' + opt.id + '">';
            html += '<span>' + opt.option_text + '</span>';
            html += '</div>';
        }
    });
    
    html += '<div class="mt-3 text-muted">';
    html += '<small>Total votes: <span id="total-votes">' + totalVotes + '</span></small>';
    if (hasVoted) {
        html += ' <span class="badge bg-success">You voted</span>';
    }
    html += '</div>';
    html += '</div></div>';
    
    $('#poll-content').html(html);
    $('#poll-content').data('hasVoted', hasVoted);
}

// Submit vote
function submitVote(pid, oid) {
    $.ajax({
        url: '/vote',
        type: 'POST',
        data: {
            poll_id: pid,
            option_id: oid
        },
        success: function(res) {
            if (res.success) {
                showAlert('Vote recorded successfully!', 'success');
                showPoll(pid); // Refresh
            } else {
                showAlert(res.message, 'warning');
            }
        },
        error: function() {
            showAlert('Failed to submit vote', 'danger');
        }
    });
}

// Update results in real-time
function updateResults(pid) {
    $.get('/results/' + pid, function(data) {
        var totalVotes = 0;
        data.forEach(function(opt) { totalVotes += parseInt(opt.votes); });
        
        data.forEach(function(opt) {
            var votes = parseInt(opt.votes);
            var percent = totalVotes > 0 ? Math.round((votes / totalVotes) * 100) : 0;
            
            $('#opt-' + opt.id + '-count').text(votes + ' votes (' + percent + '%)');
            $('#opt-' + opt.id + '-bar').css('width', percent + '%');
        });
        
        $('#total-votes').text(totalVotes);
    });
}

// Start real-time result updates
function startResultUpdates(pid) {
    if (resultInterval) {
        clearInterval(resultInterval);
    }
    resultInterval = setInterval(function() {
        if (currentPollId && $('#poll-content').data('hasVoted')) {
            updateResults(currentPollId);
        }
    }, 1000);
}

// Load voters for admin panel
function loadVoters(pid) {
    $.get('/admin/voters/' + pid, function(data) {
        if (data.voters && data.voters.length > 0) {
            $('#admin-panel').show();
            var html = '';
            data.voters.forEach(function(v) {
                html += '<div class="voter-item">';
                html += '<span>' + v.ip_address + '</span>';
                html += '<div>';
                html += '<button class="btn btn-sm btn-info me-1" onclick="showHistory(' + pid + ', \'' + v.ip_address + '\')">History</button>';
                html += '<button class="btn btn-sm btn-danger" onclick="releaseIp(' + pid + ', \'' + v.ip_address + '\')">Release</button>';
                html += '</div>';
                html += '</div>';
            });
            $('#voter-list').html(html);
        } else {
            $('#admin-panel').hide();
        }
    });
}

// Release an IP
function releaseIp(pid, ip) {
    if (!confirm('Release IP ' + ip + '? This will remove their vote.')) return;
    
    $.ajax({
        url: '/admin/release',
        type: 'POST',
        data: {
            poll_id: pid,
            ip: ip
        },
        success: function(res) {
            if (res.success) {
                showAlert('IP released successfully', 'success');
                updateResults(pid);
                loadVoters(pid);
            } else {
                showAlert(res.message, 'danger');
            }
        }
    });
}

// Show vote history for an IP
function showHistory(pid, ip) {
    $.get('/admin/history/' + pid + '/' + encodeURIComponent(ip), function(data) {
        var html = '<h6>IP: ' + ip + '</h6>';
        if (data.length === 0) {
            html += '<p class="text-muted">No history found</p>';
        } else {
            data.forEach(function(h) {
                var cls = h.action === 'released' ? 'released' : '';
                html += '<div class="history-item ' + cls + '">';
                html += '<strong>' + h.action.toUpperCase() + '</strong><br>';
                html += 'Option: ' + h.option_text + '<br>';
                html += '<small>Voted: ' + (h.voted_at || 'N/A') + '</small>';
                if (h.released_at) {
                    html += '<br><small>Released: ' + h.released_at + '</small>';
                }
                html += '</div>';
            });
        }
        $('#history-content').html(html);
        var modal = new bootstrap.Modal(document.getElementById('historyModal'));
        modal.show();
    });
}

// Show alert message
function showAlert(message, type) {
    var alert = $('<div class="alert alert-' + type + ' alert-vote alert-dismissible fade show">' +
                  message +
                  '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                  '</div>');
    $('body').append(alert);
    setTimeout(function() { alert.alert('close'); }, 3000);
}
