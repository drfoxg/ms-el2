<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create a new manufacturer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="my-2">
                <div class="flex">
                    <form class="w-full xl:w-1/2" action="{{ route('manufacturer.store') }}" method="POST">
                        @csrf
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="part_name">
                                <div class="label">
                                    <span class="label-text">Название</span>
                                </div>
                                <input type="text" name="name" id="name" class="input-bordered">
                            </label>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="part_name">
                                <div class="label">
                                    <span class="label-text">Бренд</span>
                                </div>
                                <input type="text" name="brand" id="brand" class="input-bordered">
                            </label>

                            @if ($errors->has('brand'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('brand') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-4 w-full join join-vertical">
                            <label class="form-control" for="part_name">
                                <div class="label">
                                    <span class="label-text">Публичный бренд</span>
                                </div>
                                <input type="text" name="public_brand" id="public_brand" class="input-bordered">
                            </label>

                            @if ($errors->has('public_brand'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('public_brand') }}</strong>
                                </span>
                            @endif
                        </div>
                        <br>
                        <button type="submit" class="btn-orange">Создать</button>
                    </form>
                </div>
            </div>

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
