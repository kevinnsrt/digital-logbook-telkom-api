<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data NPK Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <form action="{{ route('documents.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full md:col-span-2">
                                <label class="label">
                                    <span class="label-text font-bold">Judul Pekerjaan (NPK)</span>
                                </label>
                                <input type="text" name="title" placeholder="Contoh: Penyediaan Perangkat..." class="input input-bordered w-full focus:input-primary" required />
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold">Customer / Dinas</span>
                                </label>
                                <input type="text" name="customer" placeholder="Nama instansi" class="input input-bordered w-full" required />
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold">Mitra Kerja (Vendor)</span>
                                </label>
                                <input type="text" name="mitra" placeholder="Nama perusahaan mitra" class="input input-bordered w-full" required />
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold">Nominal (Price)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                    <input type="number" name="price" placeholder="0" class="input input-bordered w-full pl-10" required />
                                </div>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold">Periode Pekerjaan</span>
                                </label>
                                <input type="text" name="jangka_waktu" placeholder="Contoh: Tahun 2026" class="input input-bordered w-full" required />
                            </div>

                        </div>

                        <div class="flex justify-end mt-8 border-top pt-4">
                            <button type="submit" class="btn btn-primary px-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Simpan Data NPK
                            </button>
                        </div>

                    </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>