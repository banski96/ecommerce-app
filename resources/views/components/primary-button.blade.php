<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 dark:bg-orange-600 dark:hover:bg-orange-700 dark:active:bg-orange-800 text-white dark:text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 border border-transparent rounded-md font-semibold text-xs transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
