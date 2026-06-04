<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data NPK Baru</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-white shadow-xs border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Data NPK Baru') }}
            </h2>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-100">
                <div class="p-6 sm:p-8 text-gray-900">
                    
                    <form action="{{ route('documents.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full md:col-span-2">
                                <label class="label mb-1">
                                    <span class="label-text font-semibold text-gray-700">Judul Pekerjaan (NPK)</span>
                                </label>
                                <input type="text" name="title" placeholder="Contoh: Penyediaan Perangkat..." class="input input-bordered w-full focus:input-primary bg-white text-gray-800" required />
                            </div>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-semibold text-gray-700">Customer / Dinas</span>
                                </label>
                                <input type="text" name="customer" placeholder="Nama instansi" class="input input-bordered w-full bg-white text-gray-800" required />
                            </div>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-semibold text-gray-700">Mitra Kerja (Vendor)</span>
                                </label>
                                <input type="text" name="mitra" placeholder="Nama perusahaan mitra" class="input input-bordered w-full bg-white text-gray-800" required />
                            </div>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-semibold text-gray-700">Nominal (Price)</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="price" placeholder="0" class="input input-bordered w-full pl-12 bg-white text-gray-800" required />
                                </div>
                            </div>

                            <div class="form-control w-full">
                                <label class="label mb-1">
                                    <span class="label-text font-semibold text-gray-700">Periode Pekerjaan</span>
                                </label>
                                <input type="text" name="jangka_waktu" placeholder="Contoh: Tahun 2026" class="input input-bordered w-full bg-white text-gray-800" required />
                            </div>

                        </div>

                        <div class="flex justify-end mt-8 border-t border-gray-100 pt-6">
                            <button type="submit" class="btn btn-primary px-8 text-white font-medium flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Simpan Data NPK
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>

</body>
</html>