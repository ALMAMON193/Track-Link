<?php

namespace App\Livewire\Backend\SystemSetting;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class IndexComponent extends Component
{
    public $mail_mailer = 'smtp';

    public $mail_host;

    public $mail_port;

    public $mail_username;

    public $mail_password;

    public $mail_encryption;

    public $mail_from_address;

    public function mount()
    {
        // Load current SMTP settings from .env
        $this->mail_mailer = env('MAIL_MAILER', 'smtp');
        $this->mail_host = env('MAIL_HOST');
        $this->mail_port = env('MAIL_PORT');
        $this->mail_username = env('MAIL_USERNAME');
        $this->mail_password = env('MAIL_PASSWORD');
        $this->mail_encryption = env('MAIL_ENCRYPTION', 'ssl');
        $this->mail_from_address = env('MAIL_FROM_ADDRESS');

        Log::info('System settings mounted: MAIL_HOST='.$this->mail_host);
    }

    public function updateSmtpSettings()
    {
        $this->validate([
            'mail_mailer' => 'required|in:smtp,mailgun,ses,postmark,sendmail,log',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'required|in:ssl,tls,null',
            'mail_from_address' => 'required|email',
        ]);

        try {
            // Read current .env file
            $envPath = base_path('.env');
            if (! file_exists($envPath)) {
                throw new \Exception('.env file not found');
            }

            $envContent = file_get_contents($envPath);
            $lines = explode("\n", $envContent);
            $newLines = [];

            // Define keys to update
            $keysToUpdate = [
                'MAIL_MAILER' => $this->mail_mailer,
                'MAIL_HOST' => $this->mail_host,
                'MAIL_PORT' => $this->mail_port,
                'MAIL_USERNAME' => $this->mail_username,
                'MAIL_PASSWORD' => $this->mail_password,
                'MAIL_ENCRYPTION' => $this->mail_encryption,
                'MAIL_FROM_ADDRESS' => $this->mail_from_address,
            ];

            $updated = false;
            foreach ($lines as $line) {
                $trimmedLine = trim($line);
                if ($trimmedLine === '' || strpos($trimmedLine, '=') === false) {
                    $newLines[] = $line;

                    continue;
                }

                [$key, $value] = array_pad(explode('=', $trimmedLine, 2), 2, '');
                if (array_key_exists($key, $keysToUpdate)) {
                    $newLines[] = "$key=".$keysToUpdate[$key];
                    unset($keysToUpdate[$key]);
                    $updated = true;
                } else {
                    $newLines[] = $line;
                }
            }

            // Add any remaining keys
            foreach ($keysToUpdate as $key => $value) {
                $newLines[] = "$key=$value";
                $updated = true;
            }

            // Write back to .env file
            if ($updated) {
                file_put_contents($envPath, implode("\n", $newLines));
                \Artisan::call('config:clear');
                Log::info('SMTP settings updated successfully');
                session()->flash('success', 'SMTP settings updated successfully!');
            } else {
                session()->flash('info', 'No changes made to SMTP settings.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating SMTP settings: '.$e->getMessage());
            session()->flash('error', 'Failed to update SMTP settings. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.backend.system-setting.index-component')->layout('backend.app');
    }
}
