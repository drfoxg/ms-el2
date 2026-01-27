<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Warehouses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session()->has('success'))
            <div class="my-2">
                <div class="alert alert-success rounded">
                    {{ session()->get('success') }}
                </div>
            </div>
            @endif
            @if (session()->has('failure'))
            <div class="my-2">
                <div class="alert alert-warning rounded">
                    {{ session()->get('failure') }}
                </div>
            </div>
            @endif
            @auth
            <div class="flex items-center justify-between my-2">
                <div class="flex">
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{ url()->current() . '/create' }}">{{ __('Create a position') }}</a>
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{ url()->current() . '/import' }}">{{ __('Import from Excel') }}</a>
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{ url()->current() . '/tmpdownload' }}">{{ __('Download Excel template') }}</a>
                </div>
                <div class="flex text-right">
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{ url()->current() . '/removeall' }}">{{ __('Remove All') }}</a>
                </div>
            </div>
            @endauth
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            @isset($warehouses)
                                @if ($warehouses->isEmpty())
                                <div>
                                    Извините, нет товаров.
                                </div>
                                @else
                                <table class="min-w-full text-left font-light">
                                    <thead class="border-b font-medium dark:border-neutral-500">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">#</th>
                                        <th scope="col" class="px-6 py-4">Парт номер</th>
                                        <th scope="col" class="px-6 py-4">Название</th>
                                        <th scope="col" class="px-6 py-4">Цена</th>
                                        <th scope="col" class="px-6 py-4">Наличие</th>
                                        <th scope="col" class="px-6 py-4">Количество</th>
                                        <th scope="col" class="px-6 py-4">Рейтинг</th>
                                        <th scope="col" class="px-6 py-4">Производитель</th>
                                        <th scope="col" class="px-6 py-4">Поставщик</th>
                                        {{-- <th scope="col" class="px-6 py-4">Комментарий</th> --}}
                                        @auth
                                        <th scope="col" class="px-6 py-4">Действия</th>
                                        @endauth
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($warehouses as $tag)
                                    <tr class="border-b transition duration-300 ease-in-out hover:bg-orange-100 dark:border-neutral-500 dark:hover:bg-neutral-600">
                                        <th class="whitespace-nowrap px-6 py-4 font-medium" scope="row">{{ $tag->id }}</th>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->part_number }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->price_for_display }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->in_stock ? __('Yes') : __('No')}}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->stock_quantity }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->rating_for_display }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->manufacturer->name ?? '-'}}</td>
                                        <td class="whitespace-nowrap px-6 py-4">{{ $tag->vendor->name ?? '-'}}</td>
                                        {{-- <td class="whitespace-nowrap px-6 py-4">{{ $tag->comment }}</td> --}}
                                        @auth
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex gap-4">
                                                <a
                                                    href="{{ route('warehouse.edit', $tag->id) }}"
                                                    class="inline-flex items-center justify-center
                                                        w-6 h-6 rounded-md
                                                        text-orange-500 hover:text-white
                                                        hover:bg-orange-500
                                                        transition"
                                                    aria-label="{{ __('Edit') }}"
                                                    title="{{ __('Edit') }}"
                                                >
                                                    <x-icon name="edit" class="w-6 h-6" />
                                                </a>
                                                <form action="{{ route('warehouse.destroy', $tag->id) }}"
                                                    method="post"
                                                    onsubmit="return confirm('{{ __('Delete this item?') }}')">
                                                    @csrf
                                                    @method('delete')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center
                                                            w-6 h-6 rounded-md
                                                            text-red-500 hover:text-white
                                                            transition
                                                            focus:outline-none focus:ring-2 focus:ring-red-400"
                                                        aria-label="{{ __('Delete') }}"
                                                        title="{{ __('Delete') }}"
                                                    >
                                                        <x-icon
                                                            name="delete"
                                                            class="w-6 h-6"
                                                        />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endauth
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @endif
                            @endisset
                        </div>
                    </div>
                </div>
            </div>

            <x-te-paginator :collection=$warehouses :te-paginator-active=$tePaginatorActive/>

            <div class="my-2">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>

            @php
                // debug
                //throw new ErrorException('Error found');
            @endphp
        </div>
    </div>
</x-app-layout>
