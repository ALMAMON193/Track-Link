<section class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
    {{-- <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
        Flowbite
    </a> --}}

    <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                Sign in to your account
            </h1>

            @if (session()->has('error'))
                <div class="text-red-600 text-sm mb-2">{{ session('error') }}</div>
            @endif

            <form wire:submit.prevent="login" class="space-y-4 md:space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Your email
                    </label>
                    <input type="email" id="email" wire:model.defer="email" placeholder="name@company.com"
                        class="bg-gray-50 border rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                      @error('email') border-red-500 @else border-gray-300 @enderror">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Password
                    </label>
                    <input type="password" id="password" wire:model.defer="password" placeholder="••••••••"
                        class="bg-gray-50 border rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                      @error('password') border-red-500 @else border-gray-300 @enderror">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" wire:model="remember"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-300">Remember me</label>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Sign in
                </button>
            </form>

        </div>
    </div>
</section>
