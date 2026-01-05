<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeployController extends Controller
{
    public function beta(Request $request)
    {
        // 1. Verify GitHub signature
        $secret = env('GITHUB_DEPLOY_SECRET');

        $signature = 'sha256=' . hash_hmac(
            'sha256',
            $request->getContent(),
            $secret
        );

        if (
            !$request->header('X-Hub-Signature-256') ||
            !hash_equals($signature, $request->header('X-Hub-Signature-256'))
        ) {
            return response('Invalid signature', 403);
        }

        // 2. Verify branch
        $payload = json_decode($request->getContent(), true);

        if (($payload['ref'] ?? '') !== 'refs/heads/beta') {
            return response('Not beta branch', 200);
        }

        // 3. Run deploy script (external, NOT inline) for security reasons
        $output = shell_exec(
            'cd /home/lydiaslech0n/beta.lydias-lechon.com && git pull origin beta 2>&1'
        );

        file_put_contents(
            base_path('storage/logs/deploy_beta.log'),
            $output,
            FILE_APPEND
        );

        return response('OK', 200);
    }
    
    public function live(Request $request)
    {
        // 1. Verify GitHub signature
        $secret = env('GITHUB_DEPLOY_SECRET');

        $signature = 'sha256=' . hash_hmac(
            'sha256',
            $request->getContent(),
            $secret
        );

        if (
            !$request->header('X-Hub-Signature-256') ||
            !hash_equals($signature, $request->header('X-Hub-Signature-256'))
        ) {
            return response('Invalid signature', 403);
        }

        // 2. Verify branch
        $payload = json_decode($request->getContent(), true);

        if (($payload['ref'] ?? '') !== 'refs/heads/live') {
            return response('Not live branch', 200);
        }

        // 3. Run deploy script (external, NOT inline) for security reasons
        $output = shell_exec(
            'cd /home/lydiaslech0n/public_html && git pull origin live 2>&1'
        );

        file_put_contents(
            base_path('storage/logs/deploy_live.log'),
            $output,
            FILE_APPEND
        );

        return response('OK', 200);
    }
}
