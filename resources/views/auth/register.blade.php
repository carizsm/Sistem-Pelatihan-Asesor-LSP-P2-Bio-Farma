<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
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
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      width: 50%;
    }

    .right {
      flex: 1;
      background-color: #F3F3F3;
      margin-left: 50%;
      overflow-y: auto;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 0;
    }

    .register-box {
      width: 100%;
      max-width: 420px;
      text-align: center;
    }

    .logo {
      width: 266px;
      margin-top : 98px;
      margin-bottom: 60px;
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
    }

    label {
      width: 100%;
      text-align: left;
      font-size: 16px;
      font-weight: 600;
    }

    input, select {
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

    input:focus, select:focus {
      border-color: #F26E22; 
      background-color: #FFF; 
    }

    .form-row {
      display: flex;
      gap: 10px;
      width: 100%;
    }

    .form-row select {
      flex: 1;
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
      margin-top: 42px;
    }

    button:hover {
      background-color: #d95f1f;
    }

    .login-link {
      margin-top: 20px;
      font-size: 14px;
      color: #555;
    }

    .login-link a {
      color: #0097B2;
      font-weight: 600;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 900px) {
      .left { display: none; }
      .right {
        margin-left: 0;
        flex: 1;
        padding: 40px;
      }
      .register-box { max-width: 100%; }
    }
  </style>
</head>
<body>
  <div class="left"></div>
  <div class="right">
    <div class="register-box">
      <img src="/images/logo-lsp.png" alt="Logo LSP" class="logo">
      <h2>Buat Akun Baru</h2>
      
      @if ($errors->any())
      <div style="color:red; margin-bottom:20px; text-align:left;">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="nama"><b>Nama Lengkap</b></label>
        <input type="text" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus />

        <label for="nik"><b>NIK</b></label>
        <!-- <input type="text" id="nik" name="nik" placeholder="NIK" value="{{ old('nik') }}" disabled /> -->
        <input type="text" id="nik" name="nik" placeholder="NIK disabled" disabled />

        <label for="email"><b>Email Biofarma</b></label>
        <input type="email" id="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required />

        <label for="password"><b>Password</b></label>
        <input type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password" />

        <div class="form-row">
          <div>
            <label for="jabatan"><b>Jabatan</b></label>
            <select id="position_id" name="position_id" disabled>
              <option value="" disabled selected>Pilih Jabatan disabled</option>
              <option value="1">Staff</option>
              <option value="2">Supervisor</option>
              <option value="3">Manager</option>
              <option value="4">Direktur</option>
            </select>
          </div>
          <div>
            <label for="unit"><b>Unit</b></label>
            <select id="unit_id" name="unit_id" disabled>
              <option value="" disabled selected>Pilih Unit disabled</option>
              <option value="1">Produksi</option>
              <option value="2">Quality Control</option>
              <option value="3">R&D</option>
              <option value="4">HR</option>
            </select>
          </div>
        </div>

        <button type="submit">Daftar</button>
      </form>

      <div class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk disini</a>
      </div>
    </div>
  </div>
</body>
</html>
