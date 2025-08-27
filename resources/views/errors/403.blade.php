<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font for better aesthetics */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Keyframes for the bouncing animation */
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
                /* Adjusted for a slightly more noticeable bounce */
            }
        }

        /* Apply animation to each digit of the 403 */
        .animate-bounce-custom .digit {
            display: inline-block;
            animation: bounce 1.2s infinite ease-in-out;
            /* Slightly longer duration */
        }

        .animate-bounce-custom .digit:nth-child(1) {
            animation-delay: 0s;
        }

        .animate-bounce-custom .digit:nth-child(2) {
            animation-delay: 0.1s;
        }

        .animate-bounce-custom .digit:nth-child(3) {
            animation-delay: 0.2s;
        }

        /* The 4th digit is no longer present for '403', but keeping the rule won't cause issues */
        .animate-bounce-custom .digit:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
</head>

<body
    class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-50 to-gray-200 text-gray-800 p-4">
    <div
        class="text-center bg-white rounded-2xl shadow-xl p-8 md:p-12 max-w-lg w-full transform transition-all duration-300 hover:scale-105">
        <!-- 403 Text with bouncing animation -->
        <h1 class="text-9xl md:text-[10rem] font-extrabold text-gray-700 mb-6 animate-bounce-custom">
            <span class="digit">4</span><span class="digit">0</span><span class="digit">3</span>
        </h1>
        <!-- Message for the user -->
        <p class="text-xl md:text-2xl  mb-8 text-gray-600">
            Forbidden! You don't have permission to access this page.
            {{-- <br>
            ហាមឃាត់! អ្នកគ្មានសិទ្ធិចូលមើលទំព័រនេះទេ។ --}}
        </p>
        <!-- Go Back button -->
        <a href="javascript:history.back()"
            class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl">
            Go Back
        </a>
    </div>

    <script>
        // JavaScript for additional interactivity if needed.
        // For this simple bouncing effect, CSS handles it well.
        // If you wanted more dynamic control over the animation (e.g., stopping/starting on scroll,
        // or more complex sequences), you would use JavaScript here.
        // Example:
        // document.addEventListener('DOMContentLoaded', () => {
        //     console.log('403 page loaded!');
        // });
    </script>
</body>

</html>