<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
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

            @can('create', App\Models\User::class)
            <div class="my-2">
                <div class="flex">
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{url()->current() . '/create'}}">{{ __('Add a user') }}</a>
                </div>
            </div>
            @endcan

            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                        @isset($users)
                            @if ($users->isEmpty())
                            <div>
                                Извините, нет пользователей.
                            </div>
                            @else
                            <table class="min-w-full text-left font-light">
                                <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-6 py-4">#</th>
                                    <th scope="col" class="px-6 py-4">Имя</th>
                                    <th scope="col" class="px-6 py-4">E-mail</th>
                                    <th scope="col" class="px-6 py-4">Тип</th>
                                    @can('create', App\Models\User::class)
                                    <th scope="col" class="px-6 py-4">Действия</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $tag)
                                <tr class="border-b transition duration-300 ease-in-out hover:bg-orange-100 dark:border-neutral-500 dark:hover:bg-neutral-600">
                                    <th class="whitespace-nowrap px-6 py-4 font-medium" scope="row">{{$tag->id}}</th>
                                    <td class="whitespace-nowrap px-6 py-4">{{$tag->name}}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{$tag->email}}</td>
                                    <td scope="col" class="px-6 py-4">{{$tag->is_admin}}</td>
                                    @can('create', App\Models\User::class)
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex">
                                            <a class="btn-orange mr-2" href="{{ route('dashboard.edit', $tag->id) }}">{{ __('Edit') }}</a>
                                            <form action="{{ route('dashboard.destroy', $tag->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn-red">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                    @endcan
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

            <x-te-paginator :collection=$users :te-paginator-active=$tePaginatorActive/>

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
