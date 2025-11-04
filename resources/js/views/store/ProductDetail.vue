<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-400/20 to-emerald-600/20 rounded-full blur-3xl animate-float"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-blue-400/20 to-cyan-600/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative bg-white/80 backdrop-blur-xl shadow-2xl border-b border-white/30 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <!-- Logo -->
          <div class="flex items-center space-x-4 group">
            <div class="relative">
              <div class="w-14 h-14 bg-gradient-to-br from-green-500 via-emerald-600 to-teal-700 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-all duration-300 animate-pulse-glow">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <div class="absolute -inset-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-300"></div>
            </div>
            <div>
              <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">RohiWala.pk</h1>
              <p class="text-sm font-medium text-green-600">Semen Distribution System</p>
            </div>
          </div>

          <!-- Back Button -->
          <div class="hidden lg:flex items-center space-x-4">
            <button @click="goBack" class="relative group text-gray-700 hover:text-green-600 font-semibold transition-all duration-300 px-4 py-2 rounded-lg hover:bg-green-50">
              <span class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Products</span>
              </span>
            </button>
            <button @click="goToCheckout" class="relative group bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 text-white px-6 py-3 rounded-2xl font-semibold shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
              <span class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <span>View Cart</span>
              </span>
              <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-300"></div>
            </button>
          </div>

          <!-- Mobile Back Button -->
          <div class="lg:hidden flex items-center space-x-2">
            <button @click="goBack" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
            </button>
            <button @click="goToCheckout" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div class="text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-green-600 mx-auto mb-4"></div>
        <p class="text-gray-600 text-lg">Loading product details...</p>
      </div>
    </div>

    <!-- Product Detail Content -->
    <div v-else-if="product" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
        <!-- Product Image -->
        <div class="relative group">
          <div class="aspect-square bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 rounded-3xl flex items-center justify-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-emerald-400/10"></div>
            
            <!-- Dynamic Product Image -->
            <img 
              v-if="product.image" 
              :src="getProductImageUrl(product.image)" 
              :alt="product.name"
              class="w-full h-full object-cover rounded-3xl group-hover:scale-105 transition-transform duration-500"
            />
            
            <!-- Fallback SVG Icon -->
            <div v-else class="relative z-10 w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-500">
              <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <!-- Floating elements -->
            <div class="absolute top-8 left-8 w-4 h-4 bg-green-400/30 rounded-full animate-float"></div>
            <div class="absolute bottom-8 right-8 w-3 h-3 bg-emerald-400/30 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-8 w-2 h-2 bg-teal-400/30 rounded-full animate-float" style="animation-delay: 2s;"></div>
            
            <!-- Stock Badge -->
            <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-10">
              <span :class="product.stock_quantity > 0 ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-pink-500'" 
                    class="text-white px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg">
                {{ product.stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6 sm:space-y-8">
          <!-- Breadcrumb -->
          <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
            <router-link to="/" class="hover:text-green-600 transition-colors">Home</router-link>
            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <router-link to="/products" class="hover:text-green-600 transition-colors">Products</router-link>
            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 font-medium truncate">{{ product.name }}</span>
          </div>

          <!-- Product Title -->
          <div>
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-2 h-2 sm:w-3 sm:h-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full"></div>
              <span class="text-sm sm:text-lg font-semibold text-green-600 uppercase tracking-wide">{{ product.breed?.name }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 leading-tight mb-4">
              {{ product.name }}
            </h1>
            <p class="text-base sm:text-xl text-gray-600 leading-relaxed">
              {{ product.description }}
            </p>
          </div>

          <!-- Price and Stock -->
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 sm:p-6 shadow-xl border border-white/30">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-4 sm:space-y-0">
              <div>
                <div class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
                  ₨{{ product.price }}
                </div>
                <div class="text-sm sm:text-base text-gray-500">per straw</div>
              </div>
              <div class="text-left sm:text-right">
                <div class="flex items-center space-x-2 text-gray-500 mb-2">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span class="font-semibold text-sm sm:text-base">{{ product.stock_quantity }} available</span>
                </div>
                <div class="text-xs sm:text-sm text-gray-400">High-quality genetics</div>
              </div>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-4 sm:mb-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
              <div class="flex items-center space-x-3 sm:space-x-4">
                <button @click="decreaseQuantity" :disabled="quantity <= 1" 
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-green-500 hover:bg-green-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                  </svg>
                </button>
                <span class="text-xl sm:text-2xl font-bold text-gray-900 w-12 sm:w-16 text-center">{{ quantity }}</span>
                <button @click="increaseQuantity" :disabled="quantity >= product.stock_quantity" 
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-green-500 hover:bg-green-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3 sm:space-y-4">
              <button @click="addToCart" 
                      :disabled="product.stock_quantity <= 0"
                      :class="[
                        'w-full py-3 sm:py-4 rounded-2xl font-bold text-base sm:text-lg transition-all duration-300 transform hover:scale-105 relative overflow-hidden',
                        product.stock_quantity > 0
                          ? 'bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 text-white shadow-2xl hover:shadow-3xl hover:-translate-y-1'
                          : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                      ]">
                <span class="relative z-10 flex items-center justify-center space-x-2 sm:space-x-3">
                  <svg v-if="product.stock_quantity > 0" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                  </svg>
                  <span>{{ product.stock_quantity > 0 ? `Add ${quantity} to Cart` : 'Out of Stock' }}</span>
                </span>
                <div v-if="product.stock_quantity > 0" class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-300"></div>
              </button>
              
              <button @click="buyNow" 
                      :disabled="product.stock_quantity <= 0"
                      class="w-full border-2 border-green-600 text-green-600 py-3 sm:py-4 rounded-2xl hover:bg-green-600 hover:text-white transition-all duration-300 font-bold text-base sm:text-lg group-hover:scale-105 transform">
                <span class="flex items-center justify-center space-x-2 sm:space-x-3">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                  <span>Buy Now</span>
                </span>
              </button>
            </div>
          </div>

          <!-- Features -->
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 sm:p-6 shadow-xl border border-white/30">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Product Features</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
              <div class="flex items-center space-x-3">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-gray-700 text-sm sm:text-base">Premium Genetics</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-gray-700 text-sm sm:text-base">High Success Rate</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-gray-700 text-sm sm:text-base">Quality Guaranteed</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-gray-700 text-sm sm:text-base">Expert Support</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else class="flex items-center justify-center min-h-screen">
      <div class="text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Product Not Found</h3>
        <p class="text-gray-600 mb-6">The product you're looking for doesn't exist or has been removed.</p>
        <button @click="goBack" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
          Back to Products
        </button>
      </div>
    </div>

    <!-- Login Modal -->
    <LoginModal 
      :show="showLoginModal" 
      @close="closeModals"
      @login-success="handleLoginSuccess"
      @switch-to-register="switchToRegister"
    />

    <!-- Register Modal -->
    <RegisterModal 
      :show="showRegisterModal" 
      @close="closeModals"
      @register-success="handleRegisterSuccess"
      @switch-to-login="switchToLogin"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import LoginModal from '@/components/LoginModal.vue'
import RegisterModal from '@/components/RegisterModal.vue'

export default {
  name: 'ProductDetail',
  components: {
    LoginModal,
    RegisterModal
  },
  setup() {
    const route = useRoute()
    const router = useRouter()
    const product = ref(null)
    const loading = ref(true)
    const quantity = ref(1)
    const showLoginModal = ref(false)
    const showRegisterModal = ref(false)
    const isAuthenticated = ref(false)

    const loadProduct = async () => {
      try {
        loading.value = true
        const productId = route.params.id
        console.log('Loading product:', productId)
        
        const response = await window.axios.get(`/public/products/semen/${productId}`)
        console.log('Product response:', response.data)
        
        if (response.data.success && response.data.semen) {
          product.value = response.data.semen
          console.log('Product loaded:', product.value)
        } else {
          console.error('Invalid product response:', response.data)
          product.value = null
        }
      } catch (error) {
        console.error('Error loading product:', error)
        console.error('Error details:', error.response?.data)
        product.value = null
      } finally {
        loading.value = false
      }
    }

    const goBack = () => {
      router.push('/')
    }

    const goToCheckout = () => {
      // Align with Buy Now flow: require auth, otherwise show login modal
      checkAuthStatus()
      if (isAuthenticated.value) {
        router.push('/cart')
      } else {
        showLoginModal.value = true
      }
    }

    const increaseQuantity = () => {
      if (product.value && quantity.value < product.value.stock_quantity) {
        quantity.value++
      }
    }

    const decreaseQuantity = () => {
      if (quantity.value > 1) {
        quantity.value--
      }
    }

    const addToCart = () => {
      if (!product.value || product.value.stock_quantity <= 0) return
      
      // Get existing cart from localStorage
      const existingCart = JSON.parse(localStorage.getItem('store_cart') || '[]')
      
      // Check if product already exists in cart
      const existingItem = existingCart.find(item => item.semen.id === product.value.id)
      
      if (existingItem) {
        existingItem.quantity += quantity.value
        // Initialize cowDetails for new quantities
        for (let i = 0; i < quantity.value; i++) {
          existingItem.cowDetails.push({
            cow_type: '',
            cow_age: '',
            heat_started_at: ''
          })
        }
      } else {
        existingCart.push({
          semen: product.value,
          quantity: quantity.value,
          cowDetails: Array.from({ length: quantity.value }, () => ({
            cow_type: '',
            cow_age: '',
            heat_started_at: ''
          }))
        })
      }
      
      // Save to localStorage
      localStorage.setItem('store_cart', JSON.stringify(existingCart))
      
      // Show success message
      window.showSuccess(`Added ${quantity.value} ${product.value.name} to cart!`)
      
      // Reset quantity
      quantity.value = 1
    }

    const checkAuthStatus = () => {
      const token = localStorage.getItem('token')
      const user = localStorage.getItem('user')
      isAuthenticated.value = !!(token && user)
    }

    const buyNow = () => {
      if (!product.value || product.value.stock_quantity <= 0) return
      
      checkAuthStatus()
      
      if (isAuthenticated.value) {
        // User is logged in, add to cart and proceed to checkout
        addToCart()
        router.push('/cart')
      } else {
        // User not logged in, show login modal
        showLoginModal.value = true
      }
    }

    const handleLoginSuccess = (user) => {
      isAuthenticated.value = true
      showLoginModal.value = false
      // Add to cart and proceed to checkout after successful login
      addToCart()
      router.push('/checkout')
    }

    const handleRegisterSuccess = (user) => {
      isAuthenticated.value = true
      showRegisterModal.value = false
      // Add to cart and proceed to checkout after successful registration
      addToCart()
      router.push('/checkout')
    }

    const switchToRegister = () => {
      showLoginModal.value = false
      showRegisterModal.value = true
    }

    const switchToLogin = () => {
      showRegisterModal.value = false
      showLoginModal.value = true
    }

    const closeModals = () => {
      showLoginModal.value = false
      showRegisterModal.value = false
    }

    const getProductImageUrl = (imagePath) => {
      if (!imagePath) return null
      if (imagePath.startsWith('http')) return imagePath
      
      // Get the base URL from the current location, preserving the full path including /demo
      const currentPath = window.location.pathname
      const pathParts = currentPath.split('/').filter(part => part !== '')
      
      // Find the base path (everything before the current page)
      let basePath = ''
      if (pathParts.length > 0) {
        // If we're in a subdirectory like /demo, include it
        if (pathParts[0] === 'demo' || pathParts.includes('demo')) {
          basePath = '/demo'
        }
      }
      
      return `${window.location.origin}${basePath}/storage/${imagePath}`
    }

    onMounted(() => {
      loadProduct()
      checkAuthStatus()
    })

    return {
      product,
      loading,
      quantity,
      goBack,
      goToCheckout,
      increaseQuantity,
      decreaseQuantity,
      addToCart,
      buyNow,
      showLoginModal,
      showRegisterModal,
      handleLoginSuccess,
      handleRegisterSuccess,
      switchToRegister,
      switchToLogin,
      closeModals,
      getProductImageUrl
    }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
