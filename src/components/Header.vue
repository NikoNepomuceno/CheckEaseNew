<template>
  <header class="d-flex justify-content-end align-items-center p-2 header">
    <!-- Email Notification Dropdown Button -->
    <div class="dropdown me-3" ref="dropdown">
      <button 
        class="btn btn-light position-relative d-flex justify-content-center align-items-center shadow-notification"
        id="dropdownMenuButton" 
        @click="toggleDropdown"
        aria-expanded="false"
        style="height: 50px; width: 52px; border-radius: 50%;">
        <span class="material-icons">email</span>
        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
          <span class="visually-hidden">New notifications</span>
        </span>
      </button>
      <!-- Dropdown items -->
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" ref="dropdownMenu">
        <li><a class="dropdown-item" href="#">No new notifications</a></li>
        <li><a class="dropdown-item" href="#">Another notification</a></li>
        <li><a class="dropdown-item" href="#">More notifications</a></li>
      </ul>
    </div>

    <!-- Account Information -->
    <div class="account-info d-flex flex-column justify-content-center bg-light px-3 shadow-account"
         style="height: 50px; width: 270px; border-radius: 20px;">
      <span class="fw-bold">{{userInfo.lastName }}, {{ userInfo.firstName}}</span> 
      <span>{{userInfo.email }}</span> 
    </div>
  </header>
</template>

<script>
export default {
  data() {
    return {
      userInfo: {
        firstName: '',
        lastName: '',
        email: ''
      }
    };
  },
  computed: {
    cardTitle() {
      switch (this.$route.path) {
        case '/clearance':
          return 'Clearance';
        case '/ClearanceRecord':
          return 'Clearance';
        case '/attendance':
          return 'Attendance';
        case '/home':
          return 'Dashboard';
        case '/class':
          return 'Create & View Class';
        case '/ViewStudentAttendance':
          return 'View Student Attendance';
        default:
          return 'Dashboard';
      }
    }
  },
  methods: {
    toggleDropdown() {
      const dropdownElement = this.$refs.dropdown;
      const dropdownMenu = this.$refs.dropdownMenu;
      if (dropdownMenu.classList.contains('show')) {
        dropdownMenu.classList.remove('show');
        dropdownElement.classList.remove('show');
        dropdownElement.setAttribute('aria-expanded', 'false');
      } else {
        dropdownMenu.classList.add('show');
        dropdownElement.classList.add('show');
        dropdownElement.setAttribute('aria-expanded', 'true');
      }
    },
    loadUserInfo() {
      console.log('Loading user info...');
      const firstName = localStorage.getItem('firstname') || 'Guest';
      const lastName = localStorage.getItem('lastname') || '';
      const email = localStorage.getItem('email') || '';

      console.log('Email from localStorage:', email);  
      this.userInfo = { firstName, lastName, email };  
    }
  },
  mounted() {
    this.loadUserInfo();
    console.log(localStorage.getItem('email')); 
    window.addEventListener('storage', this.loadUserInfo);
  },
  beforeUnmount() {
    window.removeEventListener('storage', this.loadUserInfo); 
  }
};
</script>


<style scoped>
.header {
  position: fixed;
  top: 0;
  right: 0;
  z-index: 1000; 
  background: transparent; 
  border: none;
}

.material-icons {
  font-size: 1.5rem;
}

.account-info {
  border: 1px solid #dee2e6; 
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 50px; 
  width: 270px;
  border-radius: 20px;
}

.shadow-account {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
}

.shadow-notification {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); 
}

span {
  font-size: 0.9rem;
}

.fw-bold {
  font-weight: bold;
}

@media (max-width: 576px) {
  .account-info {
    height: 50px; 
    width: 50px; 
    border-radius: 50%; 
    padding: 0; 
    justify-content: center;
    align-items: center; 
    text-align: center; 
  }

  .account-info span {
    display: none;
  }

  .account-info::before {
    content: "👤";
    font-size: 2rem; 
  }
}
</style>
