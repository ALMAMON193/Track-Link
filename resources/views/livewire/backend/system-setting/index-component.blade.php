<main class="flex-1 overflow-auto p-4 lg:p-6" role="main">
    <nav aria-label="breadcrumb" class="mb-4 lg:mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">System Settings</li>
        </ol>
    </nav>

    {{-- Success message --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error message --}}
    @if (session()->has('error'))
        <div class="mb-4 p-3 text-sm text-red-800 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">SMTP Settings</h2>
        <form wire:submit.prevent="updateSmtpSettings">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label for="mail_mailer" class="block text-sm font-semibold text-gray-700">Mailer</label>
                    <select wire:model="mail_mailer" id="mail_mailer"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="smtp">SMTP</option>
                        <option value="mailgun">Mailgun</option>
                        <option value="ses">SES</option>
                        <option value="postmark">Postmark</option>
                        <option value="sendmail">Sendmail</option>
                        <option value="log">Log</option>
                    </select>
                    @error('mail_mailer')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_host" class="block text-sm font-semibold text-gray-700">Host</label>
                    <input wire:model="mail_host" id="mail_host" type="text" wire:dirty.class="is-invalid"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('mail_host')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_port" class="block text-sm font-semibold text-gray-700">Port</label>
                    <input wire:model="mail_port" id="mail_port" type="number" wire:dirty.class="border-blue-500"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('mail_port')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_username" class="block text-sm font-semibold text-gray-700">Username</label>
                    <input wire:model="mail_username" id="mail_username" type="text"
                        wire:dirty.class="border-blue-500"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('mail_username')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <input wire:model="mail_password" id="mail_password" type="password"
                        wire:dirty.class="border-blue-500"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('mail_password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_encryption" class="block text-sm font-semibold text-gray-700">Encryption</label>
                    <select wire:model="mail_encryption" id="mail_encryption" wire:dirty.class="border-blue-500"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                        <option value="null">None</option>
                    </select>
                    @error('mail_encryption')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_from_address" class="block text-sm font-semibold text-gray-700">From
                        Address</label>
                    <input wire:model="mail_from_address" id="mail_from_address" type="email"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('mail_from_address')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mt-6">

                <x-button type="submit" icon="save" color="blue">
                    Save SMTP Settings
                </x-button>
        </form>

    </div>
</main>
