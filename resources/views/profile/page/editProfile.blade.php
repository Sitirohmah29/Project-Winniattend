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
        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Edit Profil</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex justify-center items-center">
            <div class="flex relative w-fit mt-10">
                <img id="profile_preview"
                     src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : asset('images/risma-cantik.jpg') }}"
                     alt="Foto Profil"
                     class="profile-image w-32 h-32 rounded-full object-cover border-4 border-gray-300" />

                <label for="profile_photo" class="fa-solid fa-pen-to-square absolute bottom-1 right-1 p-2 bg-blue-500 text-white rounded-full cursor-pointer hover:bg-blue-600 transition-colors">
                    <input type="file"
                           id="profile_photo"
                           name="profile_photo"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           style="display:none;"
                           onchange="loadFile(event)">
                </label>
            </div>
        </div>

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

    <script>
        function loadFile(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('profile_preview');

            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2048 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    event.target.value = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak didukung. Gunakan JPEG, PNG, JPG, atau GIF.');
                    event.target.value = '';
                    return;
                }

                // Preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
