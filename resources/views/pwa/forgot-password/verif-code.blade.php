<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Passoword</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>

<body class="container">
          {{-- information page --}}
        <div class="page-title-container">
            <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
            onclick="window.location.href='{{url('/forgot-password')}}'"></i>
            <h2 class="text-title text-center w-full">Verification Code</h2>
        </div>

        <div class="flex flex-col gap-y-5">
            <h2 class="text-title2 text-center">Enter Verification Code</h2>

            <div class="flex flex-col gap-y-3">
                <div id="otp" class="flex justify-center items-center gap-2">
                    <input
                        type="text"
                        maxlength="1"
                        class="otp-input"
                    />
                    <input
                        type="text"
                        maxlength="1"
                        class="otp-input"
                    />
                    <input
                        type="text"
                        maxlength="1"
                        class="otp-input"
                    />
                    <input
                        type="text"
                        maxlength="1"
                        class="otp-input"
                    />
                    <input
                        type="text"
                        maxlength="1"
                        class="otp-input"
                    />
                    <input
                        type="text"
                        maxlength="1"
                        class="otp-input"
                    />
                </div>

                <script>
                    // Tunggu sampai DOM siap
                    document.addEventListener("DOMContentLoaded", function () {
                      const inputs = document.querySelectorAll("#otp .otp-input");

                      inputs.forEach((input, index) => {
                        input.addEventListener("input", (e) => {
                          if (e.inputType === "insertText" && input.value && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                          }
                        });

                        input.addEventListener("keydown", (e) => {
                          if (e.key === "Backspace" && !input.value && index > 0) {
                            inputs[index - 1].focus();
                          }
                        });
                      });
                    });
                  </script>

                <p class="flex text-reg justify-end">
                    If you didn't receive a code!
                    <button id="resendBtn" class="ml-1 text-red-500 hover:text-blue-500 cursor-pointer" disabled>Resend (60s)</button>
                </p>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                    const resendBtn = document.getElementById('resendBtn');
                    let countdown = 60; // durasi hitung mundur dalam detik
                    let timerId;

                    function updateButton() {
                        if (countdown > 0) {
                        resendBtn.textContent = `Resend (${countdown}s)`;
                        resendBtn.disabled = true;
                        countdown--;
                        } else {
                        resendBtn.textContent = 'Resend';
                        resendBtn.disabled = false;
                        clearInterval(timerId);
                        }
                    }

                    // Mulai hitung mundur dengan setInterval
                    function startCountdown() {
                        updateButton(); // update langsung saat mulai
                        timerId = setInterval(updateButton, 1000);
                    }

                    // Mulai countdown saat halaman dimuat
                    startCountdown();

                    resendBtn.addEventListener('click', function() {
                        // Reset countdown dan mulai lagi
                        countdown = 60;
                        startCountdown();
                    });
                    });
                </script>
            </div>
        </div>

        <div class="mt-5 mb-15 flex justify-center" onclick="window.location.href='{{url('/new-pw')}}'">
            <button class="long-button">Verify</button>
        </div>
</body>
</html>
