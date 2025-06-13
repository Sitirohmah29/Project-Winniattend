<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

      <!-- Tailwind CSS -->
      @vite('resources/css/app.css')
      <!-- Alpine.js -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
      <!-- Font Awesome -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>



<body class="bg-[#F5FAFF] font-poppins">
    <div class="py-4 px-4 relative">
    <h2 class="font-bold font-xl text-center">Profile</h2>


    {{-- username profile --}}
    <div class="pt-4 mt-4 flex flex-col items-center text-center">
        <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : asset('images/risma-cantik.jpg') }}"
             alt="Foto Profil {{ $user->name }}"
             class="profile-image w-32 h-32 rounded-full object-cover border-4 border-gray-300" />

        <h2 class="text-title">{{ $user->name ?? 'Nama Pengguna' }}</h2>
        <p class="text-reg text-gray-600">{{ $roles->name ?? 'Laravel Developer' }}</p>

        <button class="short-button mt-4" onclick="window.location.href='{{url('/editProfile')}}'">
            Edit Profile
        </button>

        @if (session('success'))
        <div id="success-alert" class="alert bg-blue-100 border border-blue-400 text-pink-500 px-4 py-3 rounded relative text-center font-bold mt-8 transition-all duration-200 transform">
            {{ session('success') }}
        </div>
        <script>
            const alert = document.getElementById('success-alert');
            setTimeout(() => {
                alert.classList.add('opacity-0', '-translate-y-5');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 3000);
        </script>
    @endif

    </div>

    {{-- account settings --}}
    <div class="py-4 mt-4">
        <div>
            <h2 class="text-title flex-start ">Account</h2>

            {{-- personal information setting --}}
            <button class="profile-card" onclick="window.location.href='{{url('/personInfo')}}'">
                    <div class="icon">
                        <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 4C0 1.7908 1.7908 0 4 0C6.2092 0 8 1.7908 8 4C8 6.2092 6.2092 8 4 8C1.7908 8 0 6.2092 0 4ZM3.4 2.4C3.4 2.24087 3.46321 2.08826 3.57574 1.97574C3.68826 1.86321 3.84087 1.8 4 1.8H4.004C4.16313 1.8 4.31574 1.86321 4.42826 1.97574C4.54079 2.08826 4.604 2.24087 4.604 2.4V2.404C4.604 2.56313 4.54079 2.71574 4.42826 2.82826C4.31574 2.94079 4.16313 3.004 4.004 3.004H4C3.84087 3.004 3.68826 2.94079 3.57574 2.82826C3.46321 2.71574 3.4 2.56313 3.4 2.404V2.4ZM4 3.6C4.10609 3.6 4.20783 3.64214 4.28284 3.71716C4.35786 3.79217 4.4 3.89391 4.4 4V5.6C4.4 5.70609 4.35786 5.80783 4.28284 5.88284C4.20783 5.95786 4.10609 6 4 6C3.89391 6 3.79217 5.95786 3.71716 5.88284C3.64214 5.80783 3.6 5.70609 3.6 5.6V4C3.6 3.89391 3.64214 3.79217 3.71716 3.71716C3.79217 3.64214 3.89391 3.6 4 3.6Z" fill="black"/>
                            </svg>

                    </div>
                    <p class="text-white text-reg" >Personal Information</p>
            </button>

            {{-- change password setting --}}
            <button class="profile-card" onclick="window.location.href='{{url('/changePw')}}'">
                    <div class="icon">
                        <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.3 3.3947V2.60241C1.3 1.91221 1.58446 1.25027 2.09081 0.762228C2.59716 0.274182 3.28392 0 4 0C4.71608 0 5.40284 0.274182 5.90919 0.762228C6.41554 1.25027 6.7 1.91221 6.7 2.60241V3.3947C7.146 3.4267 7.436 3.50766 7.6484 3.71239C8 4.05089 8 4.59643 8 5.68675C8 6.77706 8 7.3226 7.6484 7.66111C7.2972 8 6.7312 8 5.6 8H2.4C1.2688 8 0.7028 8 0.3516 7.66111C-2.38419e-08 7.3226 0 6.77706 0 5.68675C0 4.59643 -2.38419e-08 4.05089 0.3516 3.71239C0.5636 3.50766 0.854 3.4267 1.3 3.3947ZM1.9 2.60241C1.9 2.06559 2.12125 1.55075 2.51508 1.17116C2.9089 0.791566 3.44305 0.578313 4 0.578313C4.55695 0.578313 5.0911 0.791566 5.48492 1.17116C5.87875 1.55075 6.1 2.06559 6.1 2.60241V3.37504C5.94693 3.37375 5.78027 3.37324 5.6 3.37349H2.4C2.21947 3.37324 2.0528 3.37375 1.9 3.37504V2.60241ZM2.4 6.07229C2.50609 6.07229 2.60783 6.03167 2.68284 5.95937C2.75786 5.88706 2.8 5.789 2.8 5.68675C2.8 5.58449 2.75786 5.48643 2.68284 5.41413C2.60783 5.34182 2.50609 5.3012 2.4 5.3012C2.29391 5.3012 2.19217 5.34182 2.11716 5.41413C2.04214 5.48643 2 5.58449 2 5.68675C2 5.789 2.04214 5.88706 2.11716 5.95937C2.19217 6.03167 2.29391 6.07229 2.4 6.07229ZM4 6.07229C4.10609 6.07229 4.20783 6.03167 4.28284 5.95937C4.35786 5.88706 4.4 5.789 4.4 5.68675C4.4 5.58449 4.35786 5.48643 4.28284 5.41413C4.20783 5.34182 4.10609 5.3012 4 5.3012C3.89391 5.3012 3.79217 5.34182 3.71716 5.41413C3.64214 5.48643 3.6 5.58449 3.6 5.68675C3.6 5.789 3.64214 5.88706 3.71716 5.95937C3.79217 6.03167 3.89391 6.07229 4 6.07229ZM6 5.68675C6 5.789 5.95786 5.88706 5.88284 5.95937C5.80783 6.03167 5.70609 6.07229 5.6 6.07229C5.49391 6.07229 5.39217 6.03167 5.31716 5.95937C5.24214 5.88706 5.2 5.789 5.2 5.68675C5.2 5.58449 5.24214 5.48643 5.31716 5.41413C5.39217 5.34182 5.49391 5.3012 5.6 5.3012C5.70609 5.3012 5.80783 5.34182 5.88284 5.41413C5.95786 5.48643 6 5.58449 6 5.68675Z" fill="black"/>
                            </svg>

                    </div>
                    <p class="text-white text-reg">Change Password</p>
            </button>

            {{-- change face id setting --}}
            <button class="profile-card" onclick="window.location.href='{{url('/changeFace')}}'">
                <div class="icon">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 3.39453C1.04379 2.51032 1.17474 1.95874 1.56716 1.56716C1.95874 1.17474 2.51032 1.04379 3.39453 1M9 3.39453C8.95621 2.51032 8.82526 1.95874 8.43284 1.56716C8.04126 1.17474 7.48968 1.04379 6.60547 1M6.60547 9C7.48968 8.95621 8.04126 8.82526 8.43284 8.43284C8.82526 8.04126 8.95621 7.48968 9 6.60547M3.39453 9C2.51032 8.95621 1.95874 8.82526 1.56716 8.43284C1.17474 8.04126 1.04379 7.48968 1 6.60547M7.31579 7.10526L7.23074 6.74779C7.19615 6.60273 7.12366 6.46946 7.02068 6.3616C6.91771 6.25374 6.78793 6.17516 6.64463 6.13389L5.63158 5.84168V5.224C6.00884 4.96926 6.26316 4.49305 6.26316 3.94737C6.26316 3.13347 5.69726 2.47368 5 2.47368C4.30232 2.47368 3.73684 3.13347 3.73684 3.94737C3.73684 4.49305 3.99074 4.96926 4.36842 5.224V5.84168L3.36168 6.13642C3.22267 6.17712 3.09651 6.25299 2.99539 6.35669C2.89427 6.4604 2.8216 6.58843 2.78442 6.72842L2.68421 7.10526" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                </div>
                <p class="text-white text-reg">Change Face ID</p>
            </button>

        </div>

        {{-- settings --}}
        <div>
            <h2 class="text-title flex-start">Settings</h2>

            {{-- notification setting --}}
            <div x-data="{ notify: false }" class="profile-card flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="icon">
                        <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 0C3.16917 0 2.37238 0.310525 1.78489 0.863264C1.19741 1.416 0.867365 2.16568 0.867365 2.94737V4.43284C0.86743 4.49816 0.851343 4.56259 0.820376 4.62105L0.0519851 6.06653C0.01445 6.13715 -0.00327366 6.21562 0.000497323 6.2945C0.00426831 6.37337 0.0294088 6.45003 0.0735309 6.5172C0.117653 6.58436 0.179292 6.6398 0.252593 6.67824C0.325894 6.71668 0.408424 6.73685 0.492344 6.73684H7.50766C7.59158 6.73685 7.67411 6.71668 7.74741 6.67824C7.82071 6.6398 7.88235 6.58436 7.92647 6.5172C7.97059 6.45003 7.99573 6.37337 7.9995 6.2945C8.00327 6.21562 7.98555 6.13715 7.94802 6.06653L7.18007 4.62105C7.14895 4.56263 7.13271 4.49819 7.13264 4.43284V2.94737C7.13264 2.16568 6.80259 1.416 6.21511 0.863264C5.62762 0.310525 4.83083 0 4 0ZM4 8C3.72225 8.00014 3.45128 7.91922 3.22446 7.7684C2.99763 7.61758 2.82611 7.40428 2.73352 7.15789H5.26648C5.17389 7.40428 5.00237 7.61758 4.77554 7.7684C4.54872 7.91922 4.27775 8.00014 4 8Z" fill="black"/>
                            </svg>

                    </div>

                    <p class="text-white text-reg ">Notification Reminder</p>
                </div>

                <button @click="notify = !notify">
                    <i class="fa-solid"
                       :class="notify ? 'fa-toggle-on fa-xl text-green-600' : 'fa-toggle-off fa-xl text-red-600'">
                    </i>
                </button>
            </div>
        </div>


    </div>

    {{-- logout --}}
    <div class="mt-10 mb-15 flex justify-center">
        <a href="{{url('/login')}}" class="long-button text-center">Logout</a>
    </div>

    {{-- Bottom Navigation --}}
    <div class="fixed bottom-6 left-0 right-0 flex justify-center">
        <div class="bg-white rounded-full shadow-lg px-6 py-2 flex space-x-8">
            {{-- home --}}
            <a href="{{url('/dashboard')}}" class="text-gray-400">
                <i class="fa fa-home text-xl"></i>
            </a>

            {{-- profile --}}
            <a href="#" class="text-blue-500" >
                <i class="fa fa-user text-xl"></i>
            </a>
        </div>
    </div>
</body>
</html>
