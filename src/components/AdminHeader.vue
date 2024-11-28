<template>
  <div class="header-container">
    <div class="card header-card">
      <div class="card-body d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0 d-none d-sm-block"><b>{{ cardTitle }}</b></h3>

        <div class="profile-section d-flex align-items-center">
          <div class="dropdown me-3 position-relative" ref="dropdown">
            <button
              class="btn btn-light p-0 position-relative"
              type="button"
              @click="toggleDropdown"
              aria-expanded="false"
              style="border: none; background: transparent;"
            >
              <span class="material-icons">mail</span>
              <span
                class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                style="font-size: 0.75rem;"
              >
                <span class="visually-hidden">New notifications</span>
              </span>
            </button>
            <ul class="dropdown-menu" ref="dropdownMenu">
              <li><a class="dropdown-item" href="#">No new notifications</a></li>
              <li><a class="dropdown-item" href="#">Another notification</a></li>
              <li><a class="dropdown-item" href="#">More notifications</a></li>
            </ul>
          </div>

          <div class="user-info d-flex align-items-center">
            <i class="material-icons">account_circle</i>
           <div class="account-info d-flex flex-column justify-content-center bg-light px-3 shadow-account"
         style="height: 50px; width: 270px; border-radius: 20px;">
      <span class="fw-bold">{{userInfo.lastName }}, {{ userInfo.firstName}}</span> 
      <span>{{userInfo.email }}</span> 
    </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
.header-container {
  position: fixed;
  top: 0;
  right: 0;
  left: 250px; /* Adjust based on sidebar width */
  width: calc(100% - 250px); /* Adjust based on sidebar width */
  padding: 10px 20px;
  z-index: 1000;
  transition: all 0.3s ease;
}

.header-card {
  background-color: #DBF4F8;
  border-radius: 15px;
  width: 100%;
  height: auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card-body {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.card-title {
  font-size: 1.5rem;
  color: black;
}

.profile-section {
  display: flex;
  align-items: center;
  font-size: 14px;
}

.user-info {
  font-size: 14px;
  color: black;
}

.material-icons {
  font-size: 24px;
  color: black;
}

.profile-section i.material-icons {
  font-size: 24px;
}

.user-info i.material-icons {
  font-size: 36px;
  color: black;
  margin-right: 8px;
}

@media (max-width: 992px) {
  .header-container {
    left: 80px; /* Adjust based on collapsed sidebar width */
    width: calc(100% - 80px); /* Adjust based on collapsed sidebar width */
  }
}

@media (max-width: 768px) {
  .card-title {
    font-size: 1.25rem;
  }

  .profile-section {
    font-size: 12px;
  }

  .user-info {
    font-size: 12px;
  }

  .material-icons {
    font-size: 20px;
  }

  .profile-section i.material-icons {
    font-size: 20px;
  }

  .user-info i.material-icons {
    font-size: 28px;
  }
}

@media (max-width: 576px) {
  .header-container {
    left: 0;
    width: 100%;
    padding: 10px;
    z-index: 999; /* Ensure header is below the hamburger menu */
  }

  .card-title {
    display: none;
  }

  .profile-section {
    flex-direction: column;
    align-items: flex-start;
  }

  .user-info {
    font-size: 12px;
    margin-top: 8px;
  }

  .material-icons {
    font-size: 18px;
  }

  .profile-section i.material-icons {
    font-size: 18px;
  }

  .user-info i.material-icons {
    font-size: 28px;
  }
}
</style>
