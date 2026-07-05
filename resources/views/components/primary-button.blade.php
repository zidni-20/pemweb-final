<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-lg font-semibold text-sm text-white uppercase tracking-wide bg-gradient-to-r from-blue-500 to-indigo-600 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>