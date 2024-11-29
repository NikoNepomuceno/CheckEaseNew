import { createRouter, createWebHistory } from 'vue-router';
import AttendanceView from '@/views/AttendanceView.vue';
import ClearanceView from '@/views/ClearanceView.vue';
import HomeView from '@/views/HomeView.vue';
import StdntHomeView from '@/views/StdntHomeView.vue';
import StdntClearanceView from '@/views/StdntClearanceView.vue';
import StdntAttendanceView from '@/views/StdntAttendanceView.vue';
import ViewStdntAttendance from '@/views/ViewStdntAttendance.vue';
import ClearanceRecord from '@/views/ClearanceRecord.vue';
import Login from '@/views/Login.vue'; 
import SignUp from '@/views/SignUp.vue';
import CreateClass from '@/views/CreateClass.vue';

// Function to check if user is authenticated
function isAuthenticated() {
  // Example check, replace with your actual logic
  return localStorage.getItem('userLoggedIn') === 'true'; // Adjust according to your logic
}

const routes = [
  {
    path: '/',
    redirect: '/login', 
  },
  {
    path: '/:catchAll(.*)',
    redirect: '/login', 
  },
  
  {
    path: '/Home',
    name: 'Home',
    component: HomeView,
    meta: { requiresAuth: true }  
  },
  {
    path: '/attendance',
    name: 'Attendance',
    component: AttendanceView,
    meta: { requiresAuth: true }  
  },
  {
    path: '/clearance',
    name: 'Clearance',
    component: ClearanceView,
    meta: { requiresAuth: true }
  },
  {
    path: '/StudentHome',
    name: 'StudentHome',
    component: StdntHomeView,
    meta: { requiresAuth: true }
  },
  {
    path: '/StudentClearance',
    name: 'StudentClearance',
    component: StdntClearanceView,
    meta: { requiresAuth: true }
  },
  {
    path: '/StudentAttendance',
    name: 'StudentAttendance',
    component: StdntAttendanceView,
    meta: { requiresAuth: true }
  },
  {
    path: '/ViewStudentAttendance',
    name: 'StudentAttendanceCheck',
    component: ViewStdntAttendance,
    meta: { requiresAuth: true }
  },
  {
    path: '/ClearanceRecord',
    name: 'ClearanceRecord',
    component: ClearanceRecord,
    meta: { requiresAuth: true }
  },
  {
    path: '/Class',
    name: 'Class',
    component: CreateClass,
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { layout: 'empty' },
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUp,
    meta: { layout: 'empty' },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  console.log(`Trying to navigate to: ${to.name}`);

  if (to.meta.requiresAuth && !isAuthenticated()) {
    console.log('Not authenticated, redirecting to login');
    next({ name: 'Login' }); 
  } else {
    next(); 
  }
});


export default router;
