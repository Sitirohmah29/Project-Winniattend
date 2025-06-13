<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
       {{-- information page --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
        onclick="window.location.href='{{url('/login')}}'"></i>
        <h2 class="text-title text-center w-full">Forgot Password</h2>
    </div>

    <form id="emailForm">
        <div class="flex flex-col gap-y-3">
            <h2 class="text-title2 text-center">Enter e-mail address</h2>


            <div class="flex flex-col gap-y-2">
                <div class="flex flex-col gap-y-2">
                    <p class="text-title">E-mail</p>
                    <input class="input" type="email" id="emailInput" required>
                </div>

                <p class="flex text-reg text-red-500 justify-end hover:text-blue-500">Can't reset your password</p>
            </div>
        </div>

        <div class="mt-5 mb-15 flex justify-center">
            <button class="long-button" type="submit">Send</button>
        </div>
    </form>

    <script>
        const form = document.getElementById('emailForm');
        const emailInput = document.getElementById('emailInput');

        form.addEventListener('submit', function(event) {
            if (!emailInput.checkValidity()) {  // Tambahkan kurung tutup di sini
                event.preventDefault(); // cegah submit form
                alert('please input valid email!');
                emailInput.value = '';
                emailInput.focus();
            } else {
                event.preventDefault(); // cegah submit default agar bisa redirect manual
                window.location.href = '{{url("/verif-code")}}';
            }
        });
    </script>

</body>
</html>
