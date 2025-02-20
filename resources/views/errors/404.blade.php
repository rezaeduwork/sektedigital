<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center h-screen text-center">
    <div class="max-w-md">
        <img src="https://placehold.co/400x300/6B46C1/ffffff?text=404+Error" alt="404 Illustration" class="mx-auto">
        <h1 class="text-4xl font-bold text-indigo-700 mt-6">Oops! Halaman Tidak Ditemukan</h1>
        <p class="text-gray-600 mt-4">Sepertinya halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
        <a href="{{url('/')}}" class="mt-6 inline-block px-6 py-3 bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
