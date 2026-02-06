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
                    {{-- Кнопка экспорта для авторизованных --}}
                    <button
                        type="button"
                        class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white"
                        x-data
                        @click="$dispatch('open-modal', 'export-warehouse')"
                    >
                        {{ __('Экспорт товаров') }}
                    </button>
                </div>
                <div class="flex text-right">
                    <a class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white" href="{{ url()->current() . '/removeall' }}">{{ __('Remove All') }}</a>
                </div>
            </div>
            @endauth

            @guest
            {{-- Кнопка экспорта для гостей --}}
            <div class="flex items-center my-2">
                <button
                    type="button"
                    class="border-2 border-black border-spacing-2 mr-2 px-1 py-1 hover:bg-white"
                    x-data
                    @click="$dispatch('open-modal', 'export-warehouse')"
                >
                    {{ __('Экспорт товаров') }}
                </button>
            </div>
            @endguest

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

    {{-- ═══════════════════════════════════════════════
         Модальное окно экспорта товаров
         ═══════════════════════════════════════════════ --}}
    <x-modal name="export-warehouse" maxWidth="md">
        <div class="p-6" x-data="warehouseExport()" x-cloak>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Экспорт товаров в CSV
            </h3>

            {{-- Состояние: ожидание запуска --}}
            <template x-if="status === 'idle'">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Будет сформирован CSV-файл со всеми товарами склада.
                    </p>
                    <div class="flex justify-end gap-4">
                        <button
                            type="button"
                            class="btn-orange"
                            @click="$dispatch('close')"
                        >
                            Отмена
                        </button>
                        <button
                            type="button"
                            class="btn-orange"
                            @click="startExport()"
                        >
                            Начать экспорт
                        </button>
                    </div>
                </div>
            </template>

            {{-- Состояние: экспорт в процессе --}}
            <template x-if="status === 'processing'">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Формирование файла...
                    </p>
                    {{-- Прогресс-бар --}}
                    <div class="w-full bg-gray-200 rounded h-4 mb-2 overflow-hidden">
                        <div
                            class="bg-orange-400 h-4 rounded transition-all duration-300 ease-out"
                            :style="'width: ' + percent + '%'"
                        ></div>
                    </div>
                    <p class="text-sm text-gray-500 text-center" x-text="percent + '%'"></p>
                </div>
            </template>

            {{-- Состояние: файл готов --}}
            <template x-if="status === 'ready'">
                <div>
                    <p class="text-sm text-green-600 dark:text-green-400 mb-4">
                        ✓ Файл готов к скачиванию
                    </p>
                    <div class="flex justify-end gap-4">
                        <button
                            type="button"
                            class="btn-orange"
                            @click="reset(); $dispatch('close')"
                        >
                            Закрыть
                        </button>
                        <a
                            :href="downloadUrl"
                            class="btn-orange inline-block"
                            download
                        >
                            Скачать CSV
                        </a>
                    </div>
                </div>
            </template>

            {{-- Состояние: ошибка --}}
            <template x-if="status === 'error'">
                <div>
                    <p class="text-sm text-red-600 dark:text-red-400 mb-4" x-text="errorMessage"></p>
                    <div class="flex justify-end gap-4">
                        <button
                            type="button"
                            class="btn-orange"
                            @click="reset(); $dispatch('close')"
                        >
                            Закрыть
                        </button>
                        <button
                            type="button"
                            class="btn-orange"
                            @click="startExport()"
                        >
                            Повторить
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </x-modal>

    {{-- Скрипт Alpine-компонента --}}
    <x-slot name="footerScript">
        <script>
            function warehouseExport() {
                return {
                    status: 'idle',        // idle | processing | ready | error
                    exportId: null,
                    percent: 0,
                    downloadUrl: null,
                    errorMessage: '',
                    pollTimer: null,

                    startExport() {
                        this.status = 'processing';
                        this.percent = 0;
                        this.downloadUrl = null;
                        this.errorMessage = '';

                        fetch('{{ route("warehouse.export.start") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Ошибка запуска экспорта');
                            return res.json();
                        })
                        .then(data => {
                            this.exportId = data.export_id;
                            this.pollProgress();
                        })
                        .catch(err => {
                            this.status = 'error';
                            this.errorMessage = err.message || 'Не удалось запустить экспорт';
                        });
                    },

                    pollProgress() {
                        if (!this.exportId) return;

                        const url = '{{ route("warehouse.export.progress", ":id") }}'
                            .replace(':id', this.exportId);

                        fetch(url, {
                            headers: { 'Accept': 'application/json' },
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Ошибка получения прогресса');
                            return res.json();
                        })
                        .then(data => {
                            this.percent = data.percent;

                            if (data.ready && data.download_url) {
                                this.status = 'ready';
                                this.downloadUrl = data.download_url;
                            } else {
                                this.pollTimer = setTimeout(() => this.pollProgress(), 500);
                            }
                        })
                        .catch(err => {
                            this.status = 'error';
                            this.errorMessage = err.message || 'Ошибка при получении прогресса';
                        });
                    },

                    reset() {
                        if (this.pollTimer) clearTimeout(this.pollTimer);
                        this.status = 'idle';
                        this.exportId = null;
                        this.percent = 0;
                        this.downloadUrl = null;
                        this.errorMessage = '';
                    },
                };
            }
        </script>
    </x-slot>
</x-app-layout>
