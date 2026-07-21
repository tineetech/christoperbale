<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mengalihkan...</title>
<style>
body{margin:0;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#FAFAF8;font-family:sans-serif;color:#11100E;}
.loader{text-align:center;}
.spinner{width:32px;height:32px;border:3px solid #E6E3DE;border-top-color:#B8860B;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 16px;}
@keyframes spin{to{transform:rotate(360deg)}}
p{font-size:14px;color:#4A4642;}
</style>
</head>
<body>
<div class="loader">
  <div class="spinner"></div>
  <p>{{ $message }} Mengalihkan...</p>
</div>
<script>
(function(){
  var token = @json($token);
  localStorage.setItem('token', token);
  document.cookie = 'token=' + token + ';path=/;max-age=' + (86400 * 7);
  setTimeout(function(){ window.location.href = '/'; }, 800);
})();
</script>
</body>
</html>
