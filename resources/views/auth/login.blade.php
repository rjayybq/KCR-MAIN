<!DOCTYPE html>  
<html lang="en">  
<head>  
   <meta charset="UTF-8">  
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  
   <title>KCR</title>  
   <link rel="shortcut icon" type="image/png" href="../assets/images/KCR.png" />  
   <style>  
     /* Basic reset */  
* {  
  margin: 0;  
  padding: 0;  
  box-sizing: border-box;  
  font-family: Arial, sans-serif;  
}  
  
/* Background */  
body {  
  display: flex;  
  justify-content: center;  
  align-items: center;  
  height: 100vh;  
  background: url("{{ asset('assets/images/KCR-BG.jpg') }}") no-repeat center center fixed;  
  background-size: cover;  
  position: relative;  
  overflow: hidden;  
}  
  
/* Container for logo and login box */  
.container {  
  display: flex;  
  flex-direction: column;  
  align-items: center;  
  margin-top: -100px;  
}  
  
/* Logo */  
.logo {  
  display: block;  
  margin: 30px auto;  
  width: 250px;  
  margin-bottom: 80px;  
}  
  
/* Glassmorphic container */  
.login-container {  
  background: rgba(255, 255, 255, 0.12);  
  border-radius: 12px;  
  padding: 2rem;  
  width: 400px;  
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);  
  backdrop-filter: blur(12px);  
  -webkit-backdrop-filter: blur(12px);  
  border: 1px solid rgba(255, 255, 255, 0.3);  
  margin-top: -50px;  
}  
  
/* Form styling */  
.login-container h2 {  
  color: #ffffff;  
  text-align: center;  
  margin-bottom: 1.5rem;  
}  
  
.form-group {  
  margin-bottom: 1.2rem;  
}  
  
.form-group label {  
  color: #ffffff;  
  font-weight: 500;  
  margin-bottom: 0.5rem;  
  display: block;  
}  
  
.form-control {  
  width: 100%;  
  padding: 0.8rem;  
  border-radius: 8px;  
  border: 1px solid rgba(0, 0, 0, 0.3);  
  outline: none;  
  font-size: 1rem;  
  color: #333;  
  background: rgba(255, 255, 255, 0.95);  
}  
  
.form-control:focus {  
  border: 1px solid #48a462;  
  box-shadow: 0 0 6px rgba(72, 164, 98, 0.5);  
}  
  
/* Button styling */  
.btn-primary {  
  width: 100%;  
  padding: 0.8rem;  
  border: none;  
  border-radius: 8px;  
  color: #fff;  
  font-weight: bold;  
  background: linear-gradient(135deg, #30802c, #48a462);  
  cursor: pointer;  
  transition: background 0.3s ease, transform 0.2s ease;  
}  
  
.btn-primary:hover {  
  background: linear-gradient(135deg, #63e75c, #81ec9f);  
  transform: translateY(-2px);  
}  
  
/* Checkbox styling */  
.form-check {  
  color: #ffffff;  
  display: flex;  
  align-items: center;  
  margin-bottom: 1rem;  
}  
  
.form-check-input {  
  appearance: none;  
  width: 18px;  
  height: 18px;  
  border: 2px solid #48a462;  
  border-radius: 4px;  
  margin-right: 0.5rem;  
  cursor: pointer;  
  position: relative;  
}  
  
.form-check-input:checked {  
  background-color: #48a462;  
}  
  
.form-check-input:checked::after {  
  content: "✔";  
  color: white;  
  font-size: 12px;  
  position: absolute;  
  top: -2px;  
  left: 2px;  
}  
  
.form-check-label {  
  cursor: pointer;  
}  
  
/* Forgot password link */  
.forgot-password {  
  color: #ffffff;  
  text-align: center;  
  margin-top: 1rem;  
  display: block;  
  text-decoration: none;  
  font-size: 0.9rem;  
}  
  
.forgot-password:hover {  
  text-decoration: underline;  
}  
   </style>  
</head>  
<body>  
   <div class="container">  
      <img src="assets/images/KCR.png" alt="Logo" class="logo">  
      <div class="login-container">  
        <h2>Login</h2>  
        <form method="POST" action="{{ route('login') }}">  
           @csrf  
  
           <div class="form-group">  
              <label for="email">Email Address</label>  
              <input id="email" type="email" class="form-control" name="email" required autofocus>  
           </div>  
  
           <div class="form-group">  
              <label for="password">Password</label>  
              <input id="password" type="password" class="form-control" name="password" required>  
           </div>  
  
           <div class="form-check">  
              <input class="form-check-input" type="checkbox" name="remember" id="remember">  
              <label class="form-check-label" for="remember">Remember Me</label>  
           </div>  
  
           <button type="submit" class="btn-primary">Login</button>  
  
           <a href="{{ route('password.request') }}" class="forgot-password">Forgot Your Password?</a>  
        </form>  
      </div>  
   </div>  
</body>  
</html>
