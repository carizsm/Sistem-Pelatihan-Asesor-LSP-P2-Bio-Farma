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
      align-items: flex-start;
      justify-content: center;
      padding: 40px 0;
      min-height: 100vh;
    }

    .register-box {
      width: 100%;
      max-width: 420px;
      text-align: center;
      margin: auto;
    }

    .logo {
      width: 266px;
      margin-top : 45px;
      margin-bottom: 30px;
    }

    h2 {
      font-size: 22px;
      margin-bottom: 30px;
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
      <form method="POST" action="{{ route('register') }}" data-autosave="register_form">
        @csrf

        <label for="name"><b>Nama Lengkap</b></label>
        <input type="text" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus />
        @error('name')
          <div style="color: #e11d48; font-size: 12px; margin-top: -15px; margin-bottom: 15px; text-align: left; width: 100%;">
            {{  $message }}
          </div>
        @enderror

        <label for="nik"><b>NPK</b></label> 
        <input type="text" id="nik" name="nik" placeholder="NPK 8 digit" value="{{ old('nik') }}" required /> 
        @error('nik')
          <div style="color: #e11d48; font-size: 12px; margin-top: -15px; margin-bottom: 15px; text-align: left; width: 100%;">
            {{ $message }}
          </div>
        @enderror

        <label for="email"><b>Email</b></label>
        <input type="email" id="email" name="email" placeholder="Alamat Email (@gmail.com)" value="{{ old('email') }}" required />
        @error('email')
          <div style="color: #e11d48; font-size: 12px; margin-top: -15px; margin-bottom: 15px; text-align: left; width: 100%;">
            {{ $message }}
          </div>
        @enderror

        <label for="position"><b>Jabatan</b></label>
        <input type="text" id="position" name="position" list="position_list" 
              placeholder="Pilih atau ketik jabatan..." 
              value="{{ old('position') }}" required />
        <datalist id="position_list">
            <option value="Kepala Divisi">
            <option value="Kepala Departemen">
            <option value="Kepala Bagian">
            <option value="Manajer">
            <option value="Kasubbag">
            <option value="Staf">
        </datalist>
        @error('position')
            <div style="color: #e11d48; font-size: 12px; margin-top: -15px; margin-bottom: 15px; text-align: left; width: 100%;">
                {{ $message }}
            </div>
        @enderror

        <label for="unit"><b>Unit Kerja</b></label>
        <input type="text" id="unit" name="unit" placeholder="Contoh: Produksi/QA/QC" 
              value="{{ old('unit') }}" required />
        @error('unit')
            <div style="color: #e11d48; font-size: 12px; margin-top: -15px; margin-bottom: 15px; text-align: left; width: 100%;">
                {{ $message }}
            </div>
        @enderror

        <label for="password"><b>Password</b></label>
        <input type="password" id="password" name="password" placeholder="Password" 
          required autocomplete="new-password" style="margin-bottom: 6px;" />
        <p style="text-align: left; font-size: 12px; color: #666; margin-bottom: 5px; line-height: 1.4;">
          *Min. 8 karakter, wajib mengandung huruf besar, huruf kecil, angka, dan simbol.
        </p>
        @error('password')
          <div style="color: #e11d48; font-size: 12px; margin-top: 2px; margin-bottom: 15px; text-align: left; width: 100%;">
            {{ $message }}
          </div>
        @enderror

        <div style="margin-top: 15px; width: 100%; text-align: left;">
          <label for="password_confirmation"><b>Konfirmasi Password</b></label>
          <input type="password" id="password_confirmation" name="password_confirmation" 
                placeholder="Ulangi Password" required autocomplete="new-password" />
        </div>
        @error('password_confirmation')
          <div style="color: #e11d48; font-size: 12px; margin-top: 5px; text-align: left; width: 100%;">
            {{ $message }}
          </div>
        @enderror

        <button type="submit">Daftar</button>
      </form>

      <div class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </div>
  </div>

  <script>
    // Auto save ke storage brwoser
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form[data-autosave]');
      if (!form) return;

      const formId = form.getAttribute('data-autosave');
      const storageKey = 'autosave_' + formId;

      // Kembalikan data saat halaman dibuka
      const savedData = JSON.parse(localStorage.getItem(storageKey)) || {};

      // Loop semua input di dalam form
      form.querySelectorAll('input, select').forEach(input => {
        if (input.type === 'password' || input.type === 'hidden' || input.type === 'submit') return;
        
        const name = input.name;
        if (!name) return;

        // Restore value jika ada di storage
        if (savedData[name] !== undefined) {
          input.value = savedData[name];
        }

        // Simpan saat user mengubah
        input.addEventListener('input', function() {
          savedData[name] = this.value;
          localStorage.setItem(storageKey, JSON.stringify(savedData));
        });
      });

      form.addEventListener('submit', function() {
        localStorage.removeItem(storageKey);
      });
    });
  </script>
</body>
</html>
</body>
</html>
