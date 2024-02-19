<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create a new position') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="my-2">
                <div class="flex">
                    <form class="w-full xl:w-1/2" action="{{ route('warehouse.store') }}" method="POST">
                        @csrf
                        <div class="w-full join join-vertical{{ $errors->has('part_number') ? ' alert-error' : '' }}">
                            <label class="form-control" for="part_name">
                                <div class="label">
                                    <span class="label-text">Парт номер</span>
                                </div>
                                <input type="text" name="part_number" id="part_number" class="input-bordered">
                            </label>

                            @if ($errors->has('part_number'))
                            <div role="alert" class="alert alert-error py-2 rounded-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $errors->first('part_number') }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="w-full join join-vertical{{ $errors->has('manufacturer_id') ? ' alert-error' : '' }}">
                            <label class="form-control" for="manufacturer_name">
                                <div class="label">
                                    <span class="label-text">Производитель</span>
                                </div>
                                <select class="select-bordered" size="10" name="manufacturer_id">
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }} ({{ $manufacturer->brand }})</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="w-full join join-vertical{{ $errors->has('vendor_id') ? ' alert-error' : '' }}">
                            <label class="form-control" for="vendor_name">
                                <div class="label">
                                    <span class="label-text">Поставщик</span>
                                </div>
                                <select class="select-bordered" size="10" name="vendor_id">
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="w-full join join-vertical{{ $errors->has('stock_quantity') ? ' alert-error' : '' }}">
                            <label class="form-control" for="stock_quantity">
                                <div class="label">
                                    <span class="label-text">Количество</span>
                                </div>
                                <input type="text" name="stock_quantity" id="stock_quantity" class="input-bordered">
                            </label>

                            @if ($errors->has('stock_quantity'))
                            <div role="alert" class="alert alert-error py-2 rounded-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $errors->first('stock_quantity') }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="mb-4 w-full join join-vertical{{ $errors->has('comment') ? ' alert-error' : '' }}">
                            <label class="form-control" for="comment">
                                <div class="label">
                                    <span class="label-text">Комментарий</span>
                                </div>
                                <input type="text" name="comment" id="comment" class="input-bordered">
                            </label>

                            @if ($errors->has('comment'))
                            <div role="alert" class="alert alert-error py-2 rounded-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $errors->first('comment') }}</span>
                            </div>
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
