<?php

// ===== CONFIG =====
$secret = 'CHANGE_THIS_SECRET';
$branch = 'refs/heads/beta';
$deployScript = '/home/lydiaslech0n/deploy/deploy_beta.sh';

// ===== VERIFY SIGNATURE =====
$payload = file_get_contents('php://input');
$signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

$headers = getallheaders();
if (!isset($headers['X-Hub-Signature-256']) ||
    !hash_equals($signature, $headers['X-Hub-Signature-256'])) {
    http_response_code(403);
    exit('Invalid signature');
}

// ===== VERIFY BRANCH =====
$data = json_decode($payload, true);
if (($data['ref'] ?? '') !== $branch) {
    exit('Not beta branch');
}

// ===== RUN DEPLOY =====
$output = shell_exec($deployScript . ' 2>&1');
file_put_contents(__DIR__ . '/deploy.log', $output, FILE_APPEND);

echo "OK";
