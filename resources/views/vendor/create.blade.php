<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create a new vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="my-2">
                <div class="flex">
                    <form class="w-full xl:w-1/2" action="{{ route('vendor.store') }}" method="POST">
                        @csrf
                        <div class="mb-4 w-full join join-vertical">
                            <label class="form-control" for="part_name">
                                <div class="label">
                                    <span class="label-text">Название</span>
                                </div>
                                <input type="text" name="name" id="name" class="input-bordered">
                            </label>

                            @if ($errors->has('name'))
                            <div role="alert" class="alert alert-error py-2 rounded-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $errors->first('name') }}</span>
                            </div>
                            @endif
                        </div>
                        <br>
                        <button type="submit" class="btn-orange">Создать</button>
                    </form>
                </div>
            </div>

            <div class="row my-4">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>

            @php
            // debug
            //throw new ErrorException('Error found');
            @endphp
        </div>
    </div>
</x-app-layout>
