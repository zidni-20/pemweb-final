<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>