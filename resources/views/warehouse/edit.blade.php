<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit the position') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="my-2">
                <div class="flex">
                    <form class="w-full xl:w-1/2" action="{{ route('warehouse.update', $warehouse->id) }}" method="POST">
                        @csrf
                        @method('put')

                        {{-- Категория --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="category_id">
                                <div class="label">
                                    <span class="label-text">Категория</span>
                                </div>
                                <select class="select-bordered" name="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $warehouse->category_id === $category->id ? 'selected' : '' }}>
                                            {!! str_repeat('&mdash; ', $category->depth) !!}{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            @error('category_id')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Парт номер --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="part_number">
                                <div class="label">
                                    <span class="label-text">Парт номер</span>
                                </div>
                                <input type="text" name="part_number" id="part_number" class="input-bordered" value="{{ old('part_number', $warehouse->part_number) }}">
                            </label>
                            @error('part_number')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Имя компонента --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="name">
                                <div class="label">
                                    <span class="label-text">Имя компонента</span>
                                </div>
                                <input type="text" name="name" id="name" class="input-bordered" value="{{ old('name', $warehouse->name) }}">
                            </label>
                            @error('name')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Цена --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="price">
                                <div class="label">
                                    <span class="label-text">Цена</span>
                                </div>
                                <input type="text" name="price" id="price" class="input-bordered" value="{{ old('price', $warehouse->price_for_display) }}">
                            </label>
                            @error('price')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- В наличии --}}
                        <div class="w-full join join-vertical">
                            {{-- <label class="form-control cursor-pointer" for="in_stock"> --}}
                            <label class="flex items-center gap-4 mt-2 bg-base-200 rounded-lg cursor-pointer" for="in_stock">
                                <div class="label">
                                    <span class="label-text">В наличии</span>
                                </div>
                                <input type="hidden" name="in_stock" value="0">
                                <input type="checkbox" name="in_stock" id="in_stock" value="1" class="checkbox checkbox-success checkbox-lg" {{ old('in_stock', $warehouse->in_stock) ? 'checked' : '' }}>
                            </label>
                            @error('in_stock')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Рейтинг --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="rating">
                                <div class="label">
                                    <span class="label-text">Рейтинг (0–5)</span>
                                </div>
                                <input type="text" name="rating" id="rating" class="input-bordered" value="{{ old('rating', $warehouse->rating_for_display) }}">
                            </label>
                            @error('rating')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Производитель --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="manufacturer_id">
                                <div class="label">
                                    <span class="label-text">Производитель</span>
                                </div>
                                <select class="select-bordered" name="manufacturer_id">
                                    <option value="">-- Не выбран --</option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}"
                                            {{ $warehouse->manufacturer_id === $manufacturer->id ? 'selected' : '' }}>
                                            {{ $manufacturer->name }} ({{ $manufacturer->brand }})
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            @error('manufacturer_id')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Поставщик --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="vendor_id">
                                <div class="label">
                                    <span class="label-text">Поставщик</span>
                                </div>
                                <select class="select-bordered" name="vendor_id">
                                    <option value="">-- Не выбран --</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ $warehouse->vendor_id === $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            @error('vendor_id')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Количество --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="stock_quantity">
                                <div class="label">
                                    <span class="label-text">Количество</span>
                                </div>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="input-bordered" value="{{ old('stock_quantity', $warehouse->stock_quantity) }}">
                            </label>
                            @error('stock_quantity')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Комментарий --}}
                        <div class="w-full join join-vertical">
                            <label class="form-control" for="comment">
                                <div class="label">
                                    <span class="label-text">Комментарий</span>
                                </div>
                                <input type="text" name="comment" id="comment" class="input-bordered" value="{{ old('comment', $warehouse->comment) }}">
                            </label>
                            @error('comment')
                                <div class="alert alert-error py-2 rounded-none">{{ $message }}</div>
                            @enderror
                        </div>

                        <br>
                        <button type="submit" class="mt-4 btn-orange">Обновить</button>
                    </form>
                </div>
            </div>

            <div class="my-2">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </div>
    </div>
</x-app-layout>

