<template>
  <div class="container d-flex justify-content-center align-items-center vh-100" style="margin-left: 150px;">
    <!-- Form Section -->
    <div class="card p-4 m-5 mt-3" style="width: 830px; border-radius: 20px; background-color: #fdf6f5;">
      <form @submit.prevent="createClass" class="d-flex flex-column justify-content-between">
        <div class="form-group mb-3">
          <label for="className" class="form-label font-weight-bold">Class Name</label>
          <input v-model="className" type="text" class="form-control custom-input" id="className" placeholder="Enter Class Name" required />
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

    <!-- Add Students Section -->
    <div v-if="classes.length" class="card p-4 mt-4" style="width: 830px; border-radius: 20px; background-color: #fdf6f5;">
      <h5>Add Students to Class</h5>
      <div class="form-group mb-3">
        <label for="classSelect" class="form-label font-weight-bold">Select Class</label>
        <select v-model="selectedClass" class="form-control custom-input" id="classSelect" required>
          <option v-for="(classItem, index) in classes" :key="index" :value="classItem.class_id">
            {{ classItem.class_name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <h6>Select Students</h6>
        <ul class="list-group">
          <li v-for="(student, index) in students" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ student.name }}</span>
            <input type="checkbox" v-model="selectedStudents" :value="student.id" />
          </li>
        </ul>
      </div>

      <div class="d-flex justify-content-end mt-3">
        <button @click="addStudents" class="btn custom-btn"><b>Add Students</b></button>
      </div>
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
      students: [],
      selectedClass: null,
      selectedStudents: [],
      capacityError: '',
      token: localStorage.getItem('token') || '',
    };
  },
  methods: {
    async fetchClasses() {
      try {
        const response = await axios.get('http://localhost/CheckEaseExp-NEW/vue-login-backend/fetchclasses.php', {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });
        this.classes = response.data.classes;
      } catch (error) {
        console.error('Error fetching classes:', error);
      }
    },

    async fetchStudents() {
      try {
        const response = await axios.get('http://localhost/CheckEaseExp-NEW/vue-login-backend/fetchstudents.php', {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });
        this.students = response.data.students;
      } catch (error) {
        console.error('Error fetching students:', error);
      }
    },

    async createClass() {
      this.capacityError = '';

      if (!this.className || !this.capacity || this.capacity <= 0 || isNaN(this.capacity)) {
        this.capacityError = 'Invalid class name or capacity';
        return;
      }

      try {
        const response = await axios.post(
          'http://localhost/CheckEaseExp-NEW/vue-login-backend/createclass.php',
          {
            class_name: this.className,
            capacity: this.capacity,
          },
          {
            headers: {
              'Content-Type': 'application/json',
              Authorization: `Bearer ${this.token}`,
            },
          }
        );

        if (response.data.success) {
          alert('Class created successfully!');
          this.fetchClasses();
          this.className = '';
          this.capacity = '';
        } else {
          alert(response.data.error || 'Failed to create class');
        }
      } catch (error) {
        console.error('Error creating class:', error);
      }
    },

    async addStudents() {
      if (!this.selectedClass || this.selectedStudents.length === 0) {
        alert('Select a class and at least one student');
        return;
      }

      try {
        const response = await axios.post(
          'http://localhost/CheckEaseExp-NEW/vue-login-backend/addstudents.php',
          {
            class_id: this.selectedClass,
            students: this.selectedStudents,
          },
          {
            headers: {
              'Content-Type': 'application/json',
              Authorization: `Bearer ${this.token}`,
            },
          }
        );

        if (response.data.success) {
          alert('Students added successfully!');
          this.selectedStudents = [];
        } else {
          alert(response.data.error || 'Failed to add students');
        }
      } catch (error) {
        console.error('Error adding students:', error);
      }
    },
  },
  mounted() {
    this.fetchClasses();
    this.fetchStudents();
  },
};
</script>

<style scoped>
.container {
  height: 100vh;
  margin-top: -200px;
}

.card {
  border-radius: 20px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  background-color: #fdf6f5;
}

.font-weight-bold {
  font-weight: bold;
}

.custom-input {
  border-radius: 10px;
  box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.1);
}

.custom-btn {
  background-color: #78B7D0;
  color: white;
  border-radius: 10px;
  padding: 8px 16px;
  border: none;
}

.custom-btn:hover {
  background-color: #66a3c7;
}

.list-group-item {
  background-color: #fdf6f5;
  border: none;
}

.list-group-item:hover {
  background-color: #f2e6e2;
}
</style>
