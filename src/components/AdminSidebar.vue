<template>
  <div class=" postion-fixed">
    <!-- Hamburger Icon for Mobile -->
    <button
      class="btn d-md-none"
      @click="toggleSidebar"
      style="position: fixed; top: 10px; left: 10px; z-index: 1001; background: none; border: none; padding: 0;"
    >
      <i class="material-icons" style="color: black;">menu</i>
    </button>

    <!-- Sidebar -->
    <div
      :class="['sidebar', { 'sidebar-hidden': isSidebarHidden }]"
      style="width: 250px; height: 100vh;"
    >
      <!-- Logo Section -->
      <div class="text-center py-3">
        <img src="/public/images/checkEaseLogo.png" alt="Check Ease Logo" width="150" />
      </div>

      <!-- Menu Items -->
      <ul class="list-unstyled mt-4">
        <li>
          <router-link
            to="/home"
            class="sidebar-item"
            :class="{ active: isActive('/home') }"
          >
            <i class="material-icons">dashboard</i> Dashboard
          </router-link>
        </li>
        <li>
          <router-link
            to="/class"
            class="sidebar-item"
            :class="{ active: isActive('/class') }"
          >
            <i class="material-icons">add_circle</i> Create Class
          </router-link>
        </li>

        <!-- Attendance Dropdown -->
        <li>
          <div
            class="sidebar-item"
            @click="toggleAttendanceDropdown"
            style="cursor: pointer;"
          >
            <i class="material-icons">event</i> Attendance
            <i class="material-icons ml-auto">
              {{ isAttendanceDropdownOpen ? 'expand_less' : 'expand_more' }}
            </i>
          </div>
          <ul v-if="isAttendanceDropdownOpen" class="dropdown-list pl-4">
            <li>
              <router-link
                to="/attendance"
                class="sidebar-sub-item"
                :class="{ active: isActive('/attendance') }"
              >
                Take Attendance
              </router-link>
            </li>
            <li>
              <router-link
                to="/ViewStudentAttendance"
                class="sidebar-sub-item"
                :class="{ active: isActive('/ViewStudentAttendance') }"
              >
                View Student Attendance
              </router-link>
            </li>
          </ul>
        </li>

        <li>
          <router-link
            to="/clearance"
            class="sidebar-item"
            :class="{ active: isActive('/clearance') }"
          >
            <i class="material-icons">assignment</i> Clearance
          </router-link>
        </li>
      </ul>

      <!-- Logout Icon Fixed at Bottom -->
      <div class="logout-icon" @click="confirmLogout">
        <i class="material-icons">exit_to_app</i> <b>Logout</b>
      </div>
      <div v-if="showDialog" class="dialog-overlay">
      <div class="dialog">
        <h2>Do you want to Logout?</h2>
        <p>Are you sure you want to logout?</p>
        <div class="dialog-buttons">
          <button @click="logout" class="dialog-confirm">YES</button>
          <button @click="closeDialog" class="dialog-cancel">NO</button>
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
      isSidebarHidden: false,
      isAttendanceDropdownOpen: false,
      currentRoute: '/home',
      showDialog: false,
    };
  },
  
  mounted() {
    this.handleResize();
    this.loadUserInfo();
    console.log(localStorage.getItem('email')); 
    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {

    toggleSidebar() {
      this.isCollapsed = !this.isCollapsed; 
    },
    confirmLogout() {
      this.showDialog = true;
    },
    closeDialog() {
      this.showDialog = false;
    },
    logout() {
      localStorage.removeItem("authToken");
      this.$router.push("/Login");
      this.showDialog = false; 
    },

    toggleSidebar() {
    this.isSidebarHidden = !this.isSidebarHidden;
    },
    toggleAttendanceDropdown() {
      this.isAttendanceDropdownOpen = !this.isAttendanceDropdownOpen;
    },
    handleResize() {
      this.isSidebarHidden = window.innerWidth < 768;
    },
    isActive(route) {
      return this.$route.path === route;
    },
    loadUserInfo() {
      // Load user info from localStorage or set default
      const firstName = localStorage.getItem('firstname') || 'Guest';
      const lastName = localStorage.getItem('lastname') || '';
      const email = localStorage.getItem('email') || '';

      // Update the userInfo object
      this.userInfo = { firstName, lastName, email };
    },
  },
};
</script>


<style scoped>
.dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; 
}

.dialog {
  background-color: #ffffff;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  width: 300px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
  border: 2px solid #e4e4e4;
}

.dialog h2 {
  margin-bottom: 10px;
  color: #333;
}

.dialog-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.dialog-confirm, .dialog-cancel {
  padding: 8px 16px;
  border: none;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s, transform 0.2s ease; 
}

.dialog-confirm {
  background-color:#dc3545;
  color: white;
  border-radius: 4px;
}

.dialog-cancel {
  background-color: #007bff;
  color: white;
  border-radius: 4px;
}

/* Hover effects */
.dialog-confirm:hover {
  background-color: #c82333; 
  transform: scale(1.1); 
}

.dialog-cancel:hover {
  background-color: #0056b3; 
  transform: scale(1.1); 
}
.sidebar {
  background-color: #DBF4F8;
  color: #000;
  position: fixed;
  top: 0;
  left: 0;
  overflow-y: auto;
  transition: transform 0.3s ease;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  padding-top: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5) !important;
}

.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar-item {
  padding: 18px;
  font-size: 20px;
  cursor: pointer;
  color: gray;
  display: flex;
  align-items: center;
  text-decoration: none;
}

.sidebar-item i {
  font-size: 26px;
  margin-right: 12px;
}

.sidebar-item.active {
  color: black;
}

.sidebar-item:hover {
  color: black;
}

.sidebar-item:not(.active) {
  color: gray;
}

.dropdown-list {
  list-style: none;
  padding-left: 0;
  margin-top: 0;
}

.sidebar-sub-item {
  padding: 12px 18px;
  font-size: 18px;
  color: gray;
  display: block;
  text-decoration: none;
}

.sidebar-sub-item.active {
  color: black;
}

.logout-icon {
  font-size: 24px;
  cursor: pointer;
  text-align: center;
  padding: 18px;
  color: #000;
  position: absolute;
  bottom: 0;
  width: 100%;
}

button {
  background: none;
  border: none;
  padding: 0;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: fixed;
    top: 0;
    left: 0;
    box-shadow: none;
    z-index: 1000; 
  }

  .sidebar-hidden {
    transform: translateY(-100%);
  }

  .sidebar-item {
    padding: 10px;
  }

  .logout-icon {
    position: relative;
    bottom: auto;
  }
}
</style>
