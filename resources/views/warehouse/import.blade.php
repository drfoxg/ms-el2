<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="my-2">
                <div class="col mx-auto">
                    <form action="{{ route($routeName) }}" method="POST" enctype="multipart/form-data" class="p-1">
                        @csrf
                        <div class="flex flex-col">
                            <div class="">
                                <label for="excel" class="mb-2 inline-block">Заполненный по шаблону файл Excel:&nbsp;</label>
                                <input class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary" type="file" name="excel" id="excel" accept=".xlsx">
                                @error('excel')
                                <div role="alert" class="alert alert-error py-2 rounded-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <br />
                            <div class="">
                                <button type="button" onclick="location.href='{{ route($routeBack) }}'" class="btn-orange" name="cancel">Отмена</button>
                                <button type="submit" class="btn-orange" value="Импортировать" name="import">Импортировать</button>
                            </div>
                        </div>
                    </form>

                    {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif --}}

                    @if ($errors->import->any())
                        <div class="alert alert-danger">
                            <strong>Ошибки в файле:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->import->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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

