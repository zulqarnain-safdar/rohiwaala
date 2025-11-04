<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Registration Form -->
    <div v-if="!showOtpVerification" class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
        <p class="mt-2 text-sm text-gray-600">
          Join RohiWala.pk Platform
        </p>
      </div>

      <!-- Registration Form -->
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <form @submit.prevent="handleRegister" class="space-y-6">
          <!-- Role Selection -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Register as:
            </label>
            <div v-if="form.role" class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
              <p class="text-sm text-gray-600">
                Registering as: <span class="font-semibold text-green-700 capitalize">{{ form.role }}</span>
              </p>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <label class="relative cursor-pointer">
                <input
                  type="radio"
                  v-model="form.role"
                  value="farmer"
                  class="sr-only"
                />
                <div class="flex items-center justify-center p-4 border-2 rounded-xl transition-all duration-200"
                     :class="form.role === 'farmer' 
                       ? 'border-green-500 bg-green-50 text-green-700' 
                       : 'border-gray-200 hover:border-gray-300 text-gray-600'">
                  <div class="text-center">
                    <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-sm font-medium">Farmer</span>
                  </div>
                </div>
              </label>
              <label class="relative cursor-pointer">
                <input
                  type="radio"
                  v-model="form.role"
                  value="technician"
                  class="sr-only"
                />
                <div class="flex items-center justify-center p-4 border-2 rounded-xl transition-all duration-200"
                     :class="form.role === 'technician' 
                       ? 'border-green-500 bg-green-50 text-green-700' 
                       : 'border-gray-200 hover:border-gray-300 text-gray-600'">
                  <div class="text-center">
                    <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">Technician</span>
                  </div>
                </div>
              </label>
            </div>
            <div v-if="errors.role" class="mt-2 text-sm text-red-600">
              {{ errors.role[0] }}
            </div>
          </div>

          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
              Full Name
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                placeholder="Enter your full name"
              />
            </div>
            <div v-if="errors.name" class="mt-2 text-sm text-red-600">
              {{ errors.name[0] }}
            </div>
          </div>

          <!-- Mobile Field -->
          <div>
            <label for="mobile" class="block text-sm font-semibold text-gray-700 mb-2">
              Mobile Number
            </label>
            <FlagSelector 
              v-model="form.mobile"
              input-id="mobile"
              input-name="mobile"
              input-type="tel"
              :required="true"
              placeholder="3001234567"
            />
            <div v-if="errors.mobile" class="mt-2 text-sm text-red-600">
              {{ errors.mobile[0] }}
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
              Password
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
              </div>
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                placeholder="Create a password"
              />
              <button
                type="button"
                @click="togglePasswordVisibility"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
              </button>
            </div>
            <div v-if="errors.password" class="mt-2 text-sm text-red-600">
              {{ errors.password[0] }}
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
              Confirm Password
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
              </div>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showConfirmPassword ? 'text' : 'password'"
                required
                class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                placeholder="Confirm your password"
              />
              <button
                type="button"
                @click="toggleConfirmPasswordVisibility"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="!showConfirmPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
              </button>
            </div>
            <div v-if="errors.password_confirmation" class="mt-2 text-sm text-red-600">
              {{ errors.password_confirmation[0] }}
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
              <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ error }}
            </div>
          </div>

          <!-- Register Button -->
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
              <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              {{ loading ? 'Creating account...' : 'Create Account' }}
            </button>
          </div>

          <!-- Login Link -->
          <div class="text-center">
            <p class="text-sm text-gray-600">
              Already have an account?
              <router-link to="/login" class="font-semibold text-green-600 hover:text-green-800 transition-colors duration-200">
                Sign in here
              </router-link>
            </p>
          </div>
        </form>
      </div>
    </div>

    <!-- OTP Verification -->
    <OtpVerification
      v-if="showOtpVerification"
      :mobile="form.mobile"
      :user-id="pendingUserId"
      @verified="handleOtpVerified"
      @back-to-registration="handleBackToRegistration"
    />
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import FlagSelector from '@/components/FlagSelector.vue'
import OtpVerification from '@/components/OtpVerification.vue'

export default {
  name: 'Register',
  components: {
    FlagSelector,
    OtpVerification
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const form = reactive({
      role: '',
      name: '',
      mobile: '',
      password: '',
      password_confirmation: ''
    })
    
    const loading = ref(false)
    const error = ref('')
    const errors = ref({})
    const showPassword = ref(false)
    const showConfirmPassword = ref(false)
    const showOtpVerification = ref(false)
    const pendingUserId = ref(null)
    
    const handleRegister = async () => {
      loading.value = true
      error.value = ''
      errors.value = ''
      
      try {
        const result = await authStore.register(form)
        
        if (result.success) {
          if (result.requiresVerification) {
            // Show OTP verification screen
            pendingUserId.value = result.userId
            showOtpVerification.value = true
          } else {
            // Redirect to appropriate dashboard
            if (result.redirectTo) {
              router.push(result.redirectTo)
            } else {
              // Fallback redirect based on user role
              const role = authStore.userRole
              if (role === 'farmer') {
                router.push('/farmer/dashboard')
              } else if (role === 'technician') {
                router.push('/technician/dashboard')
              } else {
                router.push('/dashboard')
              }
            }
          }
        } else {
          error.value = result.message
          if (result.errors) {
            errors.value = result.errors
          }
        }
      } catch (err) {
        error.value = 'An unexpected error occurred'
      } finally {
        loading.value = false
      }
    }

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value
    }

    const toggleConfirmPasswordVisibility = () => {
      showConfirmPassword.value = !showConfirmPassword.value
    }

    const handleOtpVerified = (data) => {
      // OTP verification successful, redirect to dashboard
      if (data.redirectTo) {
        router.push(data.redirectTo)
      } else {
        // Fallback redirect based on user role
        const role = authStore.userRole
        if (role === 'farmer') {
          router.push('/farmer/dashboard')
        } else if (role === 'technician') {
          router.push('/technician/dashboard')
        } else {
          router.push('/dashboard')
        }
      }
    }

    const handleBackToRegistration = () => {
      showOtpVerification.value = false
      pendingUserId.value = null
    }
    
    // Reset form when component mounts
    onMounted(() => {
      form.mobile = ''
      form.password = ''
      form.password_confirmation = ''
      form.name = ''
      form.role = ''
      error.value = ''
      errors.value = {}
    })
    
    return {
      authStore,
      form,
      loading,
      error,
      errors,
      showPassword,
      showConfirmPassword,
      showOtpVerification,
      pendingUserId,
      handleRegister,
      togglePasswordVisibility,
      toggleConfirmPasswordVisibility,
      handleOtpVerified,
      handleBackToRegistration
    }
  }
}
</script>