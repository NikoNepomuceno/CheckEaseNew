<template>
  <div>
    <!-- Burger Icon for Phone Screens -->
    <div 
      v-if="isPhoneView" 
      class="burger-menu-icon position-fixed"
      @click="toggleSidebar">
      <i class="material-icons">menu</i>
    </div>

    <!-- Sidebar -->
    <div 
      class="sidebar d-flex flex-column align-items-center py-4" 
      :class="{ 'hidden': isPhoneView && isCollapsed }" 
      id="sidebar">
      <!-- Logo (hidden in phone view when collapsed) -->
      <img 
        v-if="!isCollapsed || !isPhoneView" 
        src="/public/images/logo.png" 
        alt="Logo" 
        class="mb-4 logo">

      <div class="nav-icons d-flex flex-column align-items-center justify-content-center flex-grow-1">
        <router-link to="/StudentHome" class="nav-icon mb-3 button">
          <i class="material-icons">apps</i>
        </router-link>
        <router-link to="/StudentAttendance" class="nav-icon mb-3 button">
          <i class="material-icons">event</i>
        </router-link>
        <router-link to="/StudentClearance" class="nav-icon mb-3 button">
          <i class="material-symbols-outlined">inventory</i>
        </router-link>
      </div>

      <div class="logout-icon mt-auto" @click="confirmLogout">
        <i class="material-icons">exit_to_app</i>
      </div>
    </div>

    <!-- Custom Logout Confirmation Dialog -->
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
</template>

<script>
export default {
  name: "Sidebar",
  data() {
    
    return {
      isCollapsed: true,
      isPhoneView: window.innerWidth <= 768,
      showDialog: false, // To control the visibility of the dialog
    };
  },
  methods: {
    toggleSidebar() {
      this.isCollapsed = !this.isCollapsed; // Toggle sidebar visibility
    },
    confirmLogout() {
      // Show the dialog when the user clicks the logout button
      this.showDialog = true;
    },
    closeDialog() {
      // Close the dialog without logging out
      this.showDialog = false;
    },
    logout() {
      // Proceed with logout and close the dialog
      localStorage.removeItem("authToken");
      this.$router.push("/Login");
      this.showDialog = false; // Close the dialog after logout
    },
    updateView() {
      this.isPhoneView = window.innerWidth <= 768;
      if (!this.isPhoneView) {
        this.isCollapsed = false;
      } else {
        this.isCollapsed = true;
      }
    },
  },
  mounted() {
    window.addEventListener("resize", this.updateView);
    this.updateView(); // Ensure initial state is set correctly
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.updateView);
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
  background: rgba(0, 0, 0, 0.7); /* Semi-transparent overlay */
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
  transition: background-color 0.3s, transform 0.2s ease; /* Smooth transition for hover */
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
/* Sidebar Styles */
#sidebar {
  width: 60px;
  background-color: #DBF4F8 !important;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
  z-index: 1000;
  transition: transform 0.3s ease, opacity 0.3s ease;
  overflow: hidden; /* Prevent content overflow */
}

#sidebar.hidden {
  transform: translateX(-100%); /* Move sidebar out of view */
  opacity: 0; /* Ensure it's visually hidden */
}

.logo {
  width: 40px;
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.nav-icons {
  flex-grow: 1;
  display: flex;
  justify-content: center;
}

.nav-icon {
  text-decoration: none;
  color: inherit;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border-radius: 20px;
  transition: background-color 0.3s ease;
}

.nav-icon:hover {
  background-color: #78B7D0;
  color: white;
}

/* Burger Menu Icon */
.burger-menu-icon {
  z-index: 1100;
  cursor: pointer;
  top: 7px;
  left: 5px;
  position: fixed;
  width: 50px;
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.burger-menu-icon i {
  font-size: 30px;
}

/* Responsive Adjustments */
@media (max-width: 576px) {
  #sidebar {
    width: 60px;
  }
  .logo {
    display: none;
  }
}

</style>
