<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CHRISBALE — Premium Footwear Marketplace')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    @stack('styles')
</head>

<body>
    <script>
        (function(){
            var token = localStorage.getItem('token');
            var loggedIn = !!token;
            if (loggedIn) {
                fetch("{{ rtrim(env('BE_URL', 'http://127.0.0.1:8002'), '/') }}/api/user", { headers: { 'Authorization': 'Bearer ' + token } })
                    .then(function(r){
                        if (r.status === 401 || r.status === 500) {
                            localStorage.removeItem('token');
                            document.documentElement.className = 'guest';
                            console.log(r)
                        }
                    })
                    .catch(function(){});
            }
            document.documentElement.className = loggedIn ? 'logged-in' : 'guest';
        })();
    </script>

    @include('components.header', ['active' => request()->path()])
    @include('components.mobile-drawer', ['active' => request()->path()])

    @yield('content')

    @include('components.footer')
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>

</html>
