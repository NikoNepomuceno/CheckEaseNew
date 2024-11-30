<template>
  <div id="app">
    <div class="background-container">
      <div class="square" v-for="n in 26" :key="n" :class="'square-' + n"></div>
    </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-1000 position-absolute top-50 start-50 translate-middle">
      <div class="box-container w-100" style="max-width: 400px;">
        <!-- Logo Section -->
        <div class="mb-4 text-center">
          <img src="/public/images/checkEaseLogo.png" alt="logo here" class="img-fluid" style="max-width: 100%; height: auto;" />
        </div>

        <!-- Form Section -->
        <form @submit.prevent="submitForm">
          <h2 class="text-center"><b>Log in to your Account</b></h2>
          <p class="text-secondary text-center mb-4">
            New to Check Ease?
            <router-link to="/signup" class="text-decoration-none">Sign up!</router-link>
          </p>

          <!-- Email Input -->
          <div class="mb-3">
            <label for="email" class="form-label">Email or Username</label>
            <input type="text" id="email" v-model="email" class="form-control" required />
          </div>

          <!-- Password Input -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" v-model="password" class="form-control" required />
          </div>

          <!-- Forgot Password Link -->
          <div class="text-end mb-4">
            <a href="#" class="text-decoration-none">Forgot password?</a>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary w-100">LOG IN</button>

          <!-- Inline Error Message -->
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

          <!-- Copyright -->
          <div class="text-center mt-4">
            <small class="text-muted">&copy;2024 Databoys</small>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
    };
  },
  methods: {
    async submitForm() {
      try {
        const response = await axios.post('http://localhost/CheckEaseExp-NEW/vue-login-backend/login.php', {
          email: this.email,
          password: this.password,
        });

        const result = response.data;

        if (result.success) {
     
          localStorage.setItem('token', result.token);  
          localStorage.setItem('role', result.role); 
          localStorage.setItem('firstname', result.firstname);
          localStorage.setItem('lastname', result.lastname);
          localStorage.setItem('email', result.email);

          const fullName = `${result.firstname} ${result.lastname}`;
          localStorage.setItem('userFullName', fullName);

          if (result.role === 'teacher') {
            this.$router.push({ name: 'Home' });
          } else if (result.role === 'student') {
            this.$router.push({ name: 'StudentHome' });
          } else {
            console.warn('No role or redirect path specified in response.');
          }
        } else {
          // Display error message if login fails
          this.errorMessage = 'The email address or password you entered is incorrect. Please verify your credentials and try again.';
        }
      } catch (error) {
        console.error('Error:', error);
        this.errorMessage = 'Something went wrong, please try again.';
      }
    },
  },
};
</script>


<style scoped>

.error-message {
  color: #ff4d4d;
  font-size: 14px;
  text-align: center;
  margin-top: 10px;
}

.modal-content {
  background-color: #f8f9fa;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.modal-header {
  background-color: #ff4d4d;
  color: white;
  border-bottom: 1px solid #e6e6e6;
  padding: 15px;
}

.modal-title {
  font-weight: bold;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  padding: 10px;
  border-top: 1px solid #e6e6e6;
}

.modal-footer .btn {
  background-color: #ff4d4d;
  color: white;
  width: 100%;
}

.modal-footer .btn:hover {
  background-color: #ff2a2a;
}

.modal.show {
  display: block;
  z-index: 1050;
  transition: opacity 0.3s ease-in-out;
}

.modal-dialog {
  max-width: 400px;
  margin: auto;
}

.modal-body p {
  font-size: 14px;
  color: #333;
}
.background-container {
  position: fixed;
  z-index: -1;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.box-container {
  background-color: white;
  border-radius: 8px;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  padding: 2rem;
}

.btn-primary {
  background-color: #0d6efd;
  border: none;
}

.btn-primary:hover {
  background-color: #0a58ca;
}

.form-control:focus {
  box-shadow: none;
  border-color: #0a58ca;
}

.square {
  position: absolute;
  background-color: rgba(0, 123, 255, 0.1);
  opacity: 0.5;
  border-radius: 10%;
}

.square-1 { width: 100px; height: 100px; top: 10%; left: 10%; }
.square-2 { width: 150px; height: 150px; top: 20%; left: 60%; }
.square-3 { width: 120px; height: 120px; top: 70%; left: 30%; }
.square-4 { width: 130px; height: 130px; top: 40%; left: 80%; }
.square-5 { width: 140px; height: 140px; top: 30%; left: 20%; }
.square-6 { width: 110px; height: 110px; top: 60%; left: 40%; }
.square-7 { width: 160px; height: 160px; top: 50%; left: 50%; }
.square-8 { width: 90px; height: 90px; top: 20%; left: 80%; }
.square-9 { width: 150px; height: 150px; top: 80%; left: 10%; }
.square-10 { width: 130px; height: 130px; top: 10%; left: 50%; }
.square-11 { width: 170px; height: 170px; top: 70%; left: 20%; }
.square-12 { width: 120px; height: 120px; top: 90%; left: 60%; }
.square-13 { width: 140px; height: 140px; top: 30%; left: 90%; }
.square-14 { width: 100px; height: 100px; top: 20%; left: 10%; }
.square-15 { width: 160px; height: 160px; top: 40%; left: 30%; }
.square-16 { width: 110px; height: 110px; top: 60%; left: 50%; }
.square-17 { width: 150px; height: 150px; top: 50%; left: 80%; }
.square-18 { width: 120px; height: 120px; top: 10%; left: 30%; }
.square-19 { width: 130px; height: 130px; top: 80%; left: 40%; }
.square-20 { width: 140px; height: 140px; top: 10%; left: 60%; }
.square-21 { width: 90px; height: 90px; top: 20%; left: 70%; }
.square-22 { width: 150px; height: 150px; top: 30%; left: 20%; }
.square-23 { width: 130px; height: 130px; top: 50%; left: 10%; }
.square-24 { width: 170px; height: 170px; top: 70%; left: 30%; }
.square-25 { width: 120px; height: 120px; top: 90%; left: 50%; }
.square-26 { width: 140px; height: 140px; top: 20%; left: 80%; }
</style>
