<template>
  <div class="container d-flex justify-content-center align-items-center vh-100" style="margin-left: 150px;">
    <!-- Form Section -->
    <div class="card p-4 m-5 mt-3" style="width: 830px; height: 260px; border-radius: 20px; background-color: #fdf6f5;">
      <form @submit.prevent="createClass" class="d-flex flex-column justify-content-between">
        <div class="form-group mb-3">
          <label for="className" class="form-label font-weight-bold">Class Name</label>
          <input v-model="className" type="text" class="form-control custom-input" id="className" placeholder="Enter Class Name" required>
        </div>
        
        <div class="form-group mb-4">
          <label for="capacity" class="form-label font-weight-bold">Capacity</label>
          <input 
            v-model="capacity" 
            type="number" 
            class="form-control custom-input" 
            id="capacity" 
            placeholder="Enter Capacity" 
            required
          />
          <!-- Display Capacity Error below the input (only once) -->
          <div v-if="capacityError" style="color: red; font-size: 12px;">
            {{ capacityError }}
          </div>
        </div>
        
        <!-- Create Button -->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn custom-btn"><b>Create</b></button>
        </div>
      </form>
    </div>

    <!-- Display Created Classes Below the Form -->
    <div v-if="classes.length" class="card p-4 mt-4" style="width: 830px; border-radius: 20px; background-color: #fdf6f5;">
      <h5>Created Classes</h5>
      <ul class="list-group">
        <li v-for="(classItem, index) in classes" :key="index" class="list-group-item">
          {{ classItem.class_name }} - Capacity: {{ classItem.capacity }} - Code: {{ classItem.class_code }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      className: '',
      capacity: '',
      classes: [],
      capacityError: '',  
      token: localStorage.getItem('jwtToken') || '',  // Retrieve token from localStorage
    };
  },
  methods: {
    async createClass() {
      // Reset error message
      this.capacityError = '';

      // Validate inputs
      if (!this.className || !this.capacity) {
        this.capacityError = 'Missing class name or capacity';
        return;
      }

      // Ensure capacity is a positive number
      if (this.capacity <= 0) {
        this.capacityError = 'Capacity must be a positive number';
        return;
      }

      // Check if token exists
      if (!this.token) {
        alert('You must be logged in to create a class');
        return;
      }

      try {
        const response = await axios.post('http://localhost/CheckEaseExp-NEW/vue-login-backend/createclass.php', {
          className: this.className,
          capacity: this.capacity,
        }, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.token}`,  
          }
        });

        const result = response.data;
        console.log('Response Data:', result);

        if (result.success) {
          alert('Class created successfully!');
          this.classes.push({
            class_name: this.className,
            capacity: this.capacity,
            class_code: result.class_code,
          });

          this.className = '';
          this.capacity = '';
        } else {
          alert(result.error || 'Failed to create class');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error occurred while creating the class.');
      }
    },
  },
};
</script>


<style scoped>
.container {
  height: 100vh; 
  margin-top: -200px; 
}

.card {
  border-radius: 20px; /* Rounded corners */
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Drop shadow */
  background-color: #fdf6f5; /* Light background color similar to the image */
}

.font-weight-bold {
  font-weight: bold;
}

.custom-input {
  border-radius: 10px; /* Rounded corners for inputs */
  box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.1); /* Inner shadow for inputs */
}

.custom-btn {
  background-color: #78B7D0; /* Light blue color for the button */
  color: white;
  border-radius: 10px; /* Rounded corners for button */
  padding: 8px 16px;
  border: none;
}

.custom-btn:hover {
  background-color: #66a3c7; /* Darker blue on hover */
}

.list-group-item {
  background-color: #fdf6f5;
  border: none;
}

.list-group-item:hover {
  background-color: #f2e6e2;
}
</style>
