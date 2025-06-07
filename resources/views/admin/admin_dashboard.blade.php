<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- សារលោត --}}
        <!-- Local toastr CSS (ចាំបាច់ដាក់ CSS ដែរ) -->
        <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}">
    
    @vite('resources/css/app.css')
    {{-- Start ការហៅ CSS មកប្រើ --}}
        <link href="{{ asset('backend/assets/css/profile.css') }}" rel="stylesheet"  />
    {{-- End --}}

    <title>Dashboard</title>
    

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="{{ asset('backend/assets/js/chart.js') }}"></script>

    {{-- Start Call Alpine.js --}}
        <script defer src="{{ asset('backend/assets/js/cdn.min.js') }}"></script>
    {{-- End Call Alpine.js --}}

    
</head>
<body class="flex flex-col min-h-screen bg-gray-100 transition-colors duration-300">

    {{-- Topbar Start --}}
        @include('admin.body.header')
    {{-- End Topbar --}}
    

    <div class="flex flex-1 flex-col md:flex-row">

        {{-- Navication bar --}}
        @include('admin.body.sidebar')
        {{-- End Navication bar --}}

        {{-- Start body --}}
            @yield('admin')
        {{-- End body --}}
    </div>

    {{-- Start Footer --}}
        {{-- @include('admin.body.footer') --}}
    {{-- End Footer --}}

    <script>
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        const themeToggleInput = document.getElementById('theme-toggle');
        const themeToggleLabel = document.querySelector('.toggle-switch-label');
        const body = document.body;

        // Function to set theme
        function setTheme(isDarkMode) {
            if (isDarkMode) {
                body.classList.add('dark');
                themeToggleInput.checked = true;
                themeToggleLabel.setAttribute('aria-checked', 'true');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark');
                themeToggleInput.checked = false;
                themeToggleLabel.setAttribute('aria-checked', 'false');
                localStorage.setItem('theme', 'light');
            }
        }

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            setTheme(true);
        } else {
            setTheme(false); // Default to light mode
        }

        // Event listener for mobile menu toggle
        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });

        // Event listener for theme toggle (listening to the input change)
        themeToggleInput.addEventListener('change', () => {
            setTheme(themeToggleInput.checked);
        });

        // Handle keyboard interaction for accessibility on the label
        themeToggleLabel.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                themeToggleInput.checked = !themeToggleInput.checked;
                setTheme(themeToggleInput.checked);
            }
        });

        // --- NEW: Active Navigation Link Logic ---
        function activateNavLink() {
            // Get the current path from the URL
            const currentPath = window.location.pathname; 
            
            // Get all navigation links within the sidebar
            const navLinksContainer = document.getElementById('nav-links');
            if (!navLinksContainer) return; // Exit if container not found

            const navLinks = navLinksContainer.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                // Remove active class from all links first
                link.classList.remove('active-nav'); 

                // Compare the link's href with the current path
                // Normalize paths to ensure accurate comparison (remove trailing slashes, base URL, etc.)
                const linkPath = new URL(link.href).pathname;
                
                // For exact match:
                if (linkPath === currentPath) {
                    link.classList.add('active-nav');
                } 
                // For partial match (e.g., /admin/products matches /admin/products/create)
                // You might need to adjust this logic based on your route structure
                else if (currentPath.startsWith(linkPath) && linkPath !== '/') { // Avoid activating dashboard for all sub-pages
                    link.classList.add('active-nav');
                }
            });
        }

        // Call the function when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', activateNavLink);

        // Optional: Call again if the URL changes without a full page reload (e.g., SPA behavior if you add it later)
        window.addEventListener('popstate', activateNavLink);


        // Chart.js Configuration
        // Pie Chart
        const pieCtx = document.getElementById('pieChart');
        let myPieChart; // Declare chart variables globally or in a scope accessible to update

        const createPieChart = () => {
            if (myPieChart) myPieChart.destroy(); // Destroy existing chart if it exists
            if (!pieCtx) return;

            myPieChart = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['General Culture', 'Language', 'Physics', 'Biology'],
                    datasets: [{
                        data: [300, 50, 100, 80],
                        backgroundColor: [
                            '#6366f1', /* Indigo-500 */
                            '#ef4444', /* Red-500 */
                            '#f97316', /* Orange-500 */
                            '#10b981'  /* Emerald-500 */
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: body.classList.contains('dark') ? '#e5e7eb' : '#374151' // Dynamic legend text color
                            }
                        }
                    }
                }
            });
        };

        // Bar Chart
        const barCtx = document.getElementById('barChart');
        let myBarChart;

        const createBarChart = () => {
            if (myBarChart) myBarChart.destroy(); // Destroy existing chart if it exists
            if (!barCtx) return;

            myBarChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    datasets: [{
                        label: 'Sales',
                        data: [300, 450, 200, 600, 350, 500, 400],
                        backgroundColor: '#6366f1', /* Indigo-500 */
                        borderColor: '#4f46e5',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: body.classList.contains('dark') ? '#e5e7eb' : '#374151' // Dynamic tick color
                            },
                            grid: {
                                color: body.classList.contains('dark') ? '#4b5563' : '#e5e7eb' // Dynamic grid line color
                            }
                        },
                        x: {
                            ticks: {
                                color: body.classList.contains('dark') ? '#e5e7eb' : '#374151' // Dynamic tick color
                            },
                            grid: {
                                color: body.classList.contains('dark') ? '#4b5563' : '#e5e7eb' // Dynamic grid line color
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide legend for bar chart
                        }
                    }
                }
            });
        };
        
        // Initial chart creation on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            createPieChart();
            createBarChart();
        });

        // Re-render charts on theme change to update colors
        themeToggleInput.addEventListener('change', () => {
            createPieChart(); // Re-create charts with new theme colors
            createBarChart();
        });

    </script>


{{-- លោតសារកន្លែង Delete --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
<script src="{{ asset('backend/assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/code.js') }}"></script>


<script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>
{{-- លោតសារកន្លែង Delete --}}

<script type="text/javascript" src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type', 'info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break; 
 }
 @endif 
</script>

</body>
</html>