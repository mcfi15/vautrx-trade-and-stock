<?php

namespace App\Console\Commands;

use App\Services\CryptoWebSocketService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CryptoWebSocketCommand extends Command
{
    protected $signature = 'crypto:websocket
                            {action=start : Action to perform (start|stop|restart)}';

    protected $description = 'Manage real-time cryptocurrency WebSocket connections';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'restart':
                $this->restart();
                break;
            default:
                $this->error("Invalid action: {$action}");
                $this->info('Available actions: start, stop, restart');
                return 1;
        }

        return 0;
    }

    private function start()
    {
        $this->info('🚀 Starting Cryptocurrency WebSocket Service...');
        $this->info('📡 Connecting to Binance WebSocket streams...');
        $this->newLine();

        try {
            $service = new CryptoWebSocketService();
            
            // Handle graceful shutdown
            pcntl_async_signals(true);
            pcntl_signal(SIGTERM, function () use ($service) {
                $this->warn('\n🛑 Received SIGTERM, shutting down gracefully...');
                $service->stop();
                exit(0);
            });
            pcntl_signal(SIGINT, function () use ($service) {
                $this->warn('\n🛑 Received SIGINT, shutting down gracefully...');
                $service->stop();
                exit(0);
            });

            $service->start();
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to start WebSocket service: ' . $e->getMessage());
            Log::error('WebSocket service error: ' . $e->getMessage());
            return 1;
        }
    }

    private function stop()
    {
        $this->info('🛑 Stopping WebSocket service...');
        
        // Find and kill the WebSocket process
        $pid = $this->getWebSocketPid();
        
        if ($pid) {
            posix_kill($pid, SIGTERM);
            $this->info("✅ Sent stop signal to process {$pid}");
        } else {
            $this->warn('⚠️ WebSocket service is not running');
        }
    }

    private function restart()
    {
        $this->info('🔄 Restarting WebSocket service...');
        $this->stop();
        sleep(2);
        $this->start();
    }

    private function getWebSocketPid()
    {
        // Check if there's a PID file or use process listing
        $output = shell_exec('pgrep -f "crypto:websocket start"');
        return $output ? (int) trim($output) : null;
    }
}
