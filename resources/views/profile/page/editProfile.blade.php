<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profil</title>

    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="py-8 px-8 grid gap-8">
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Edit Profil</h2>
    </div>

    <div class="flex justify-center items-center">
        <div class="flex relative w-fit mt-10">
            <img src="{{ asset('images/risma-cantik.jpg') }}" alt="Foto Profil" class="profile-image" />
            <i class="fa-solid fa-pen-to-square absolute bottom-1 right-1 p-1"></i>
        </div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="grid gap-5">
            <div class="grid gap-2">
                <label for="name">Nama</label>
                <input class="input" id="name" name="name" value="{{ old('name', $user->name) }}">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="grid gap-2">
                <label for="birth_date">Tanggal Lahir</label>
                <input class="input" type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
                @error('birth_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="grid gap-2">
                <label for="phone">No. Telepon</label>
                <input class="input" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="grid gap-2">
                <label for="address">Alamat</label>
                <input class="input" id="address" name="address" value="{{ old('address', $user->address) }}">
                @error('address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mt-5 flex justify-center">
            <button class="long-button" type="submit">Simpan</button>
        </div>
    </form>
</body>
</html>
