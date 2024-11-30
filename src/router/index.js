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


function isAuthenticated() {
  return localStorage.getItem('token') !== null;  
}

function getUserRole() {
  return localStorage.getItem('role'); 
}

const routes = [
  {
    path: '/',
    redirect: '/login', 
  },
  {
    path: '/Home',
    name: 'Home',
    component: HomeView,
    meta: { requiresAuth: true, requiresRole: 'teacher' }  
  },
  {
    path: '/attendance',
    name: 'Attendance',
    component: AttendanceView,
    meta: { requiresAuth: true, requiresRole: 'teacher' }  
  },
  {
    path: '/clearance',
    name: 'Clearance',
    component: ClearanceView,
    meta: { requiresAuth: true, requiresRole: 'teacher' }
  },
  {
    path: '/StudentHome',
    name: 'StudentHome',
    component: StdntHomeView,
    meta: { requiresAuth: true, requiresRole: 'student' }
  },
  {
    path: '/StudentClearance',
    name: 'StudentClearance',
    component: StdntClearanceView,
    meta: { requiresAuth: true, requiresRole: 'student' }
  },
  {
    path: '/StudentAttendance',
    name: 'StudentAttendance',
    component: StdntAttendanceView,
    meta: { requiresAuth: true, requiresRole: 'student' }
  },
  {
    path: '/ViewStudentAttendance',
    name: 'StudentAttendanceCheck',
    component: ViewStdntAttendance,
    meta: { requiresAuth: true, requiresRole: 'student' }
  },
  {
    path: '/ClearanceRecord',
    name: 'ClearanceRecord',
    component: ClearanceRecord,
    meta: { requiresAuth: true, requiresRole: 'teacher' }
  },
  {
    path: '/Class',
    name: 'Class',
    component: CreateClass,
    meta: { requiresAuth: true, requiresRole: 'teacher' }
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

  {
    path: '/:catchAll(.*)',
    redirect: '/login', 
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  console.log(`Trying to navigate to: ${to.name}`);
  console.log('Auth Token:', localStorage.getItem('token'));  
  console.log('User Role:', localStorage.getItem('role'));    

  if (to.meta.requiresAuth && !isAuthenticated()) {
    console.log('Not authenticated, redirecting to login');
    next({ name: 'Login' });
  } else if (to.name === 'Login' && isAuthenticated()) {
    console.log('Already authenticated, redirecting to home');
    const role = getUserRole();
    console.log('Redirecting based on role:', role);

    if (role === 'teacher') {
      next({ name: 'Home' });
    } else if (role === 'student') {
      next({ name: 'StudentHome' });
    } else {
      console.warn('Role not found, redirecting to login');
      next({ name: 'Login' });
    }
  } else if (to.meta.requiresRole && getUserRole() !== to.meta.requiresRole) {
    console.log('User does not have the right role, redirecting to login');
    next({ name: 'Login' });
  } else {
    next();
  }
});

export default router;