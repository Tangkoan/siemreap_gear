<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
    {{-- icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />



    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    <!-- toastr -->
        </head>

    {{-- <style>
        /* CSS បន្ថែមសម្រាប់ប្រសិទ្ធភាព Shadow និង Background Gradient */
        .login-card {
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 10px -2px rgba(0, 0, 0, 0.05); /* កែប្រែ shadow សម្រាប់រូបរាងកាន់តែទន់ */
            background-image: linear-gradient(to right top, #7e92b3, #a4acb0); /* ស្រមោលពណ៌ខៀវខ្ចី */
        }
        .input-field:focus {
            outline: none;
            border-color: #6366f1; /* Indigo-500 សម្រាប់ focus */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3); /* Shadow ពេល focus */
        }
    </style> --}}
</head>
<body class="bg-gradient-to-r min-h-screen flex items-center justify-center p-4">
    <div class=" shadow-lg border border-gray-200 bg-white rounded-xl p-8 max-w-md w-full relative transform hover:scale-105 transition-transform duration-300 ease-in-out">
        

        {{-- ✅ START: PHP Block to fetch Shop Info --}}
        {{-- ✅ ចាប់ផ្តើម៖ ប្លុក PHP សម្រាប់ទាញយកข้อมูลร้านค้า --}}
        @php
            // ទាញយកข้อมูลร้านค้า Record ទីមួយពី Table 'informationshops'
            $shopInfo = \App\Models\InformationShop::first();
        @endphp
        {{-- ✅ END: PHP Block to fetch Shop Info --}}

        {{-- បន្ថែម Logo នៅទីនេះ --}}
        <img class="rounded-full mx-auto h-32 w-auto mb-4" src="{{ ($shopInfo && $shopInfo->logo) ? asset('upload/shop_info/' . $shopInfo->logo) : asset('upload/no_image.jpg') }}" alt="Shop Logo">
 
        
        <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-6">
             {{ $shopInfo->name_en ?? 'Siem Reap Gear' }}
        </h2>
        <p class="text-center text-gray-600 mb-8">Please Login To Dashboard</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                <input type="text" id="login" name="login"  placeholder="Enter username" 
                       class="@error('login') is-invalid @enderror
                       input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                @error('login')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6 relative">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                
                <input type="password" id="password" name="password" placeholder="Enter password"
                       class="@error('password') is-invalid @enderror
                             input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 pr-10">
                @error('password')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <!-- Icon Centered Vertically -->
                <span class="absolute right-3 top-1/2 transform -translate-y-2/2 cursor-pointer text-gray-600" id="togglePassword">
                  <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </span>
              </div>


            
            {{-- <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember</label>
                </div>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition duration-200">Forgot Password?</a>
            </div> --}}

            <button type="submit"
                    class="w-full bg-red-500 text-white font-bold py-3 px-4 rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition duration-300 ease-in-out transform hover:-translate-y-1">
                Login
            </button>
        </form>

        
    </div>



    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
      
        togglePassword.addEventListener('click', () => {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
      
          // ប្ដូរប្រភេទ Icon
          toggleIcon.classList.toggle('fa-eye');
          toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>      

<script type="text/javascript">
    // Get original image source
    // This variable should be defined once when the page loads, outside of any functions that might redefine it.
    const originalImageSrc = document.getElementById('showImage').src;

    $(document).ready(function(){
        const imageInput = $('#image');
        const showImage = $('#showImage');
        const fileNameSpan = $('#file-name');
        const clearImageBtn = $('#clearImageBtn'); // Get the new clear button

        // Function to reset image and file input to its original state
        function resetImageUpload() {
            imageInput.val(''); // Clear the file input
            showImage.attr('src', originalImageSrc); // Reset image to original
            fileNameSpan.text('No file chosen'); // Reset file name text
            clearImageBtn.addClass('hidden'); // Hide the clear button
        }

        // Initialize state on page load: Show clear button if there's an actual profile photo
        if (showImage.attr('src') !== "{{ url('upload/no_image.jpg') }}") {
            clearImageBtn.removeClass('hidden');
        }

        // jQuery for Image Preview
        imageInput.change(function(e){
            // Check if a file is selected before proceeding
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e_reader){
                    showImage.attr('src', e_reader.target.result);
                    fileNameSpan.text(e.target.files[0].name);
                    clearImageBtn.removeClass('hidden'); // Show the clear button
                }
                reader.readAsDataURL(e.target.files[0]);
            } else {
                // If the user opened the file dialog and then cancelled without choosing a file
                // Or if the input value was somehow cleared directly
                resetImageUpload();
            }
        });

        // Event listener for Clear button
        clearImageBtn.click(function() {
            resetImageUpload();
        });
    });
    
</script>

</body>


</html>