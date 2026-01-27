<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vendors') }}
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
            <div class="my-2">
                <div class="flex">
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{url()->current() . '/create'}}">{{ __('Add a vendor') }}</a>
                </div>
            </div>
            @endauth
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                        @isset($vendors)
                            @if ($vendors->isEmpty())
                            <div>
                                Извините, нет поставщиков.
                            </div>
                            @else
                            <table class="min-w-full text-left font-light">
                                <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-6 py-4">#</th>
                                    <th scope="col" class="px-6 py-4">Название</th>
                                    @auth
                                    <th scope="col" class="px-6 py-4">Действия</th>
                                    @endauth
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($vendors as $tag)
                                <tr class="border-b transition duration-300 ease-in-out hover:bg-orange-100 dark:border-neutral-500 dark:hover:bg-neutral-600">
                                    <th class="whitespace-nowrap px-6 py-4 font-medium" scope="row">{{$tag->id}}</th>
                                    <td class="whitespace-nowrap px-6 py-4">{{$tag->name}}</td>
                                    @auth
                                         <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex gap-4">
                                                <a
                                                    href="{{ route('vendor.edit', $tag->id) }}"
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
                                                <form action="{{ route('vendor.destroy', $tag->id) }}"
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

            <x-te-paginator :collection=$vendors :te-paginator-active=$tePaginatorActive/>

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
