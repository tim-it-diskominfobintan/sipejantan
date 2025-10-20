<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\UserSession;

class CleanExpiredUserSessions extends Command
{
    public $signature = 'session:clean-expired';
    public $description = 'Delete expired user sessions and session files';

    public function handle()
    {
        $ttlMinutes = config('session.lifetime');
        $expiredAt = now()->subMinutes($ttlMinutes);

        $expiredSessions = UserSession::where('last_activity', '<=', $expiredAt)->get();

        foreach ($expiredSessions as $session) {
            // Hapus file session (kalau pakai file driver)
            app('session')->getHandler()->destroy($session->session_id);

            // Hapus record user_sessions
            $session->delete();
        }

        $this->info("Expired sessions cleaned: " . $expiredSessions->count());
    }
}
