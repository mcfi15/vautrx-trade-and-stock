<?php

namespace App\Services;

use App\Mail\DepositNotificationMail;
use App\Mail\WithdrawalNotificationMail;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send deposit notification email
     */
    public function sendDepositNotification(Deposit $deposit, string $action, string $notes = null): bool
    {
        try {
            // Skip if user doesn't want notifications
            if (!$deposit->user->email_notifications) {
                return true;
            }

            $emailData = [
                'deposit' => $deposit,
                'user' => $deposit->user,
                'cryptocurrency' => $deposit->cryptocurrency,
                'action' => $action,
                'admin_notes' => $notes,
                'old_status' => null,
                'new_status' => $deposit->status,
            ];

            // Get previous status if available (for status change notifications)
            if (method_exists($deposit, 'getOriginal') && $deposit->getOriginal('status')) {
                $emailData['old_status'] = $deposit->getOriginal('status');
            }

            Mail::to($deposit->user->email)
                ->send(new DepositNotificationMail($emailData));

            Log::info('Deposit notification sent successfully', [
                'deposit_id' => $deposit->id,
                'user_email' => $deposit->user->email,
                'action' => $action,
                'status' => $deposit->status,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send deposit notification', [
                'deposit_id' => $deposit->id,
                'user_email' => $deposit->user->email,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send withdrawal notification email
     */
    public function sendWithdrawalNotification(Withdrawal $withdrawal, string $action, string $notes = null): bool
    {
        try {
            // Skip if user doesn't want notifications
            if (!$withdrawal->user->email_notifications) {
                return true;
            }

            $emailData = [
                'withdrawal' => $withdrawal,
                'user' => $withdrawal->user,
                'cryptocurrency' => $withdrawal->cryptocurrency,
                'action' => $action,
                'admin_notes' => $notes,
                'old_status' => null,
                'new_status' => $withdrawal->status,
            ];

            // Get previous status if available (for status change notifications)
            if (method_exists($withdrawal, 'getOriginal') && $withdrawal->getOriginal('status')) {
                $emailData['old_status'] = $withdrawal->getOriginal('status');
            }

            Mail::to($withdrawal->user->email)
                ->send(new WithdrawalNotificationMail($emailData));

            Log::info('Withdrawal notification sent successfully', [
                'withdrawal_id' => $withdrawal->id,
                'user_email' => $withdrawal->user->email,
                'action' => $action,
                'status' => $withdrawal->status,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal notification', [
                'withdrawal_id' => $withdrawal->id,
                'user_email' => $withdrawal->user->email,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send batch notifications (for multiple deposits/withdrawals)
     */
    public function sendBatchNotifications(array $notifications): array
    {
        $results = [];

        foreach ($notifications as $notification) {
            if ($notification['type'] === 'deposit') {
                $results[] = $this->sendDepositNotification(
                    $notification['model'], 
                    $notification['action'], 
                    $notification['notes'] ?? null
                );
            } elseif ($notification['type'] === 'withdrawal') {
                $results[] = $this->sendWithdrawalNotification(
                    $notification['model'], 
                    $notification['action'], 
                    $notification['notes'] ?? null
                );
            }
        }

        return $results;
    }

    /**
     * Check if user has enabled email notifications
     */
    public function userHasNotifications($user): bool
    {
        // Check if email_notifications field exists and is enabled
        if (property_exists($user, 'email_notifications')) {
            return (bool) $user->email_notifications;
        }

        // Default to true if field doesn't exist
        return true;
    }

    /**
     * Format amount with cryptocurrency symbol
     */
    public function formatAmount(float $amount, string $symbol, int $decimals = 8): string
    {
        return number_format($amount, $decimals) . ' ' . strtoupper($symbol);
    }

    /**
     * Get action message based on action type and status
     */
    public function getActionMessage(string $type, string $action, string $status): string
    {
        $messages = [
            'deposit' => [
                'created' => 'A new deposit has been created',
                'approved' => 'Your deposit has been approved',
                'rejected' => 'Your deposit has been rejected',
                'confirmed' => 'Your deposit has been confirmed',
                'completed' => 'Your deposit has been completed',
                'failed' => 'Your deposit has failed',
                'updated' => 'Your deposit has been updated',
            ],
            'withdrawal' => [
                'created' => 'A new withdrawal has been created',
                'approved' => 'Your withdrawal has been approved and is being processed',
                'rejected' => 'Your withdrawal has been rejected',
                'completed' => 'Your withdrawal has been completed successfully',
                'cancelled' => 'Your withdrawal has been cancelled',
                'updated' => 'Your withdrawal has been updated',
            ],
        ];

        return $messages[$type][$action] ?? ucfirst($action) . ' ' . $type;
    }

    /**
     * Get status color for email templates
     */
    public function getStatusColor(string $status): string
    {
        $colors = [
            'pending' => '#f39c12',
            'processing' => '#3498db',
            'approved' => '#27ae60',
            'confirmed' => '#2ecc71',
            'completed' => '#16a085',
            'rejected' => '#e74c3c',
            'failed' => '#c0392b',
            'cancelled' => '#95a5a6',
        ];

        return $colors[$status] ?? '#34495e';
    }
}