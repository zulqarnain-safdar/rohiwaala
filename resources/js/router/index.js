import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Import views
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import OtpVerificationPage from '@/views/auth/OtpVerificationPage.vue'
import ForgotPassword from '@/views/auth/ForgotPassword.vue'
import Dashboard from '@/views/Dashboard.vue'
import FarmerDashboard from '@/views/farmer/Dashboard.vue'
import TechnicianDashboard from '@/views/technician/Dashboard.vue'
import AdminDashboard from '@/views/admin/Dashboard.vue'
import SemenCatalog from '@/views/farmer/SemenCatalog.vue'
import OrderCart from '@/views/farmer/OrderCart.vue'
import ServiceConfirmation from '@/views/farmer/ServiceConfirmation.vue'
import PregnancyTracking from '@/views/farmer/PregnancyTracking.vue'

// Import storefront views
import StoreHome from '@/views/store/Home.vue'
import StoreProductDetail from '@/views/store/ProductDetail.vue'
import StoreCart from '@/views/store/Cart.vue'
import StoreCheckout from '@/views/store/Checkout.vue'
import StoreOrderSuccess from '@/views/store/OrderSuccess.vue'

const routes = [
  // Storefront routes (public) - now at root
  {
    path: '/',
    name: 'StoreHome',
    component: StoreHome,
    meta: { 
      title: 'RohiWala.pk - Semen Distribution System'
    }
  },
  {
    path: '/products/:id',
    name: 'StoreProductDetail',
    component: StoreProductDetail,
    meta: { 
      title: 'Product Details - RohiWala.pk'
    }
  },
  {
    path: '/cart',
    name: 'StoreCart',
    component: StoreCart,
    meta: { 
      title: 'Shopping Cart - RohiWala.pk'
    }
  },
  {
    path: '/checkout',
    name: 'StoreCheckout',
    component: StoreCheckout,
    meta: { 
      title: 'Checkout - RohiWala.pk'
    }
  },
  {
    path: '/order-success/:id',
    name: 'StoreOrderSuccess',
    component: StoreOrderSuccess,
    meta: { 
      title: 'Order Success - RohiWala.pk'
    }
  },
  // Auth routes
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { 
      requiresGuest: true,
      title: 'Login - RohiWala.pk'
    }
  },
  {
    path: '/forgot-password',
    name: 'ForgotPassword',
    component: ForgotPassword,
    meta: {
      requiresGuest: true,
      title: 'Forgot Password - RohiWala.pk'
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { 
      requiresGuest: true,
      title: 'Register - RohiWala.pk'
    }
  },
  {
    path: '/otp-verification',
    name: 'OtpVerification',
    component: OtpVerificationPage,
    meta: { 
      requiresGuest: true,
      title: 'Verify Phone - RohiWala.pk'
    }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { 
      requiresAuth: true,
      title: 'Dashboard - RohiWala.pk'
    }
  },
  {
    path: '/farmer/dashboard',
    name: 'FarmerDashboard',
    component: FarmerDashboard,
    meta: { 
      requiresAuth: true, 
      role: 'farmer',
      title: 'Farmer Dashboard - RohiWala.pk'
    }
  },
  {
    path: '/technician/dashboard',
    name: 'TechnicianDashboard',
    component: TechnicianDashboard,
    meta: { 
      requiresAuth: true, 
      role: 'technician',
      title: 'Technician Dashboard - RohiWala.pk'
    }
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { 
      requiresAuth: true, 
      role: 'admin',
      title: 'Admin Dashboard - RohiWala.pk'
    }
  },
  {
    path: '/farmer/semen-catalog',
    name: 'SemenCatalog',
    component: SemenCatalog,
    meta: { 
      requiresAuth: true, 
      role: 'farmer',
      title: 'Semen Catalog - RohiWala.pk'
    }
  },
  {
    path: '/farmer/cart',
    name: 'OrderCart',
    component: OrderCart,
    meta: { 
      requiresAuth: true, 
      role: 'farmer',
      title: 'Order Cart - RohiWala.pk'
    }
  },
  {
    path: '/farmer/service-confirmation/:jobId',
    name: 'ServiceConfirmation',
    component: ServiceConfirmation,
    meta: { 
      requiresAuth: true, 
      role: 'farmer',
      title: 'Service Confirmation - RohiWala.pk'
    }
  },
  {
    path: '/farmer/pregnancy-tracking',
    name: 'PregnancyTracking',
    component: PregnancyTracking,
    meta: { 
      requiresAuth: true, 
      role: 'farmer',
      title: 'Pregnancy Tracking - RohiWala.pk'
    }
  }
]

const router = createRouter({
  history: createWebHistory('/'),
  routes
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // Check if route requires authentication
  if (to.meta.requiresAuth) {
    if (!authStore.isLoggedIn) {
      // Redirect to login if not authenticated
      next('/login')
      return
    }
    
    // Check role-based access
    if (to.meta.role && authStore.userRole !== to.meta.role) {
      // Redirect to appropriate dashboard based on user role
      if (authStore.isAdmin) {
        next('/admin/dashboard')
      } else if (authStore.isFarmer) {
        next('/farmer/dashboard')
      } else if (authStore.isTechnician) {
        next('/technician/dashboard')
      } else {
        next('/dashboard')
      }
      return
    }
  }
  
  // Check if route requires guest (not logged in)
  if (to.meta.requiresGuest && authStore.isLoggedIn) {
    // Redirect to appropriate dashboard based on user role
    if (authStore.isAdmin) {
      next('/admin/dashboard')
    } else if (authStore.isFarmer) {
      next('/farmer/dashboard')
    } else if (authStore.isTechnician) {
      next('/technician/dashboard')
    } else {
      next('/dashboard')
    }
    return
  }
  
  next()
})

// Update document title on route change
router.afterEach((to) => {
  // Update document title
  if (to.meta.title) {
    document.title = to.meta.title
  } else {
    document.title = 'RohiWala.pk'
  }
})

export default router