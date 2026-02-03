<?php
/**
 * Vote Logic - Core PHP
 * Core voting operations: check, record, and get results
 */

require_once __DIR__ . '/db_helper.php';

function hasVoted($pid, $ip) {
    $db = getDb();
    $stmt = $db->prepare("SELECT id FROM votes WHERE poll_id = ? AND ip_address = ? AND is_released = 0");
    $stmt->execute([$pid, $ip]);
    return $stmt->fetch() !== false;
}

function canVote($pid, $ip) {
    return !hasVoted($pid, $ip);
}

function recordVote($pid, $oid, $ip) {
    if (hasVoted($pid, $ip)) {
        return false;
    }
    
    $db = getDb();
    $stmt = $db->prepare("INSERT INTO votes (poll_id, option_id, ip_address, voted_at, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW(), NOW())");
    $res = $stmt->execute([$pid, $oid, $ip]);
    
    if ($res) {
        // Record in history
        $stmt2 = $db->prepare("INSERT INTO vote_history (poll_id, option_id, ip_address, action, voted_at, created_at) VALUES (?, ?, ?, 'voted', NOW(), NOW())");
        $stmt2->execute([$pid, $oid, $ip]);
    }
    
    return $res;
}

function getPollResults($pid) {
    $db = getDb();
    $stmt = $db->prepare("SELECT o.id, o.option_text, COUNT(v.id) as votes 
                          FROM poll_options o 
                          LEFT JOIN votes v ON o.id = v.option_id AND v.is_released = 0 
                          WHERE o.poll_id = ? 
                          GROUP BY o.id, o.option_text
                          ORDER BY o.display_order");
    $stmt->execute([$pid]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

