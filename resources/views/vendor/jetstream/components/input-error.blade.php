@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-red-400 text-sm ml-6 mt-1']) }}>{{ $message }}</p>
@enderror
