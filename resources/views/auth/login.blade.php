<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .left {
      flex: 1;
      background: url('/images/login-bg.png') center/cover no-repeat;
    }

    .right {
      flex: 1;
      background-color: #F3F3F3;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
      text-align: center;
      position: relative;
      top: -40px;
    }

    .logo {
      width: 266px;
      margin-bottom: 98px;
    }

    h2 {
      font-size: 22px;
      margin-bottom: 64px;
      color: #707070;
      font-weight: 600;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    label {
      width: 100%;
      text-align: left;
      font-size: 16px;
      font-weight: 600;
    }

    input {
      width: 100%;
      padding: 14px 14px;
      margin: 8px 0 20px 0;
      border: 2px solid transparent;
      border-radius: 14px;
      background-color: #F6EFEB;
      font-size: 14px;
      outline: none;
      transition: border-color 0.3s ease;
    }

    input:focus {
      border-color: #F26E22; 
      background-color: #FFF; 
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #F26E22;
      color: white;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s ease;
      margin-top: 22px;
    }

    button:hover {
      background-color: #d95f1f;
    }

    .register-link {
      margin-top: 20px;
      font-size: 14px;
      color: #555;
    }

    .register-link a {
      color: #0097B2;
      font-weight: 600;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .error {
      color: #e11d48;
      font-size: 14px;
      margin-bottom: 10px;
      text-align: left;
      width: 100%;
    }

    .status {
      background-color: #dcfce7;
      color: #166534;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
      text-align: left;
      width: 100%;
    }

    @media (max-width: 900px) {
      .left { display: none; }
      .right {
        flex: 1;
        padding: 40px;
      }
      .login-box { max-width: 100%; }
    }
  </style>
</head>
<body>
  <div class="left"></div>
  <div class="right">
    <div class="login-box">
      <img src="/images/logo-lsp.png" alt="Logo LSP" class="logo">
      <h2>Selamat Datang</h2>

      <!-- Menampilkan session status (misal setelah reset password) -->
      @if (session('status'))
        <div class="status">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <label for="email"><b>Email</b></label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus autocomplete="username">
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror

        <!-- Password -->
        <label for="password"><b>Password</b></label>
        <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password">
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror

        <!-- Remember me
        <div style="width:100%; text-align:left; margin-bottom:10px;">
          <label style="font-size:14px;">
            <input type="checkbox" name="remember"> Ingat saya
          </label>
        </div> -->

        <button type="submit">Masuk</button>
      </form>

      <div class="register-link">
        <!-- Lupa password? <a href="{{ route('password.request') }}">Reset di sini</a><br> -->
        Belum punya akun? <a href="{{ route('register') }}">Daftar disini</a>
      </div>
    </div>
  </div>
</body>
</html>
