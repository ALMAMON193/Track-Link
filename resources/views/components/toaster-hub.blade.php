<div
    x-data="{ show: false, message: '', type: 'success' }"
    x-show="show"
    x-transition
    x-cloak
    @toast.window="
        show = true;
        message = $event.detail.message;
        type = $event.detail.type;
        setTimeout(() => show = false, 3000);
    "
    class="fixed bottom-5 right-5 z-50"
>
    <div
        class="px-4 py-3 rounded-lg shadow-lg text-white"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-yellow-500': type === 'warning',
            'bg-blue-600': type === 'info'
        }"
        x-text="message"
    ></div>
</div>
