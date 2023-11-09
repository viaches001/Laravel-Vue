<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 inline-flex items-center bg-gray-800 border border-transparent rounded-full text-sm lg:text-base text-white font-bold hover:bg-gray-600 active:bg-gray-900 focus:outline-none active:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
