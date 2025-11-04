<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-400/20 to-emerald-600/20 rounded-full blur-3xl animate-float"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-blue-400/20 to-cyan-600/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Enhanced Navigation -->
    <nav class="relative bg-white/90 backdrop-blur-xl shadow-2xl border-b border-white/30 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <!-- Logo and Brand -->
          <div class="flex items-center space-x-4 group">
            <div class="relative">
              <div class="w-14 h-14 bg-gradient-to-br from-green-500 via-emerald-600 to-teal-700 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-all duration-300 animate-pulse-glow">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
              </div>
              <div class="absolute -inset-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-300"></div>
              <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full animate-pulse"></div>
            </div>
            <div>
              <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                RohiWala.pk
              </h1>
              <p class="text-sm font-semibold text-green-600 tracking-wide">Shopping Cart</p>
            </div>
          </div>

          <!-- Progress Indicator -->
          <div class="hidden lg:flex items-center space-x-8">
            <div class="flex items-center space-x-6 bg-gradient-to-r from-gray-50/90 to-green-50/90 backdrop-blur-sm rounded-3xl px-8 py-4 shadow-lg border border-white/20">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                  </svg>
                </div>
                <span class="text-green-700 font-bold text-sm">Cart</span>
              </div>
              <div class="w-20 h-1 bg-gray-300 rounded-full"></div>
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <span class="text-gray-500 font-bold text-sm">Checkout</span>
              </div>
            </div>
          </div>

          <!-- Mobile Navigation Icons -->
          <div class="lg:hidden flex items-center space-x-2">
            <!-- Mobile Menu Button -->
            <button @click="toggleMobileMenu" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg v-if="!showMobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>

            <!-- Mobile Cart Icon -->
            <button @click="goToProducts" class="relative p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
              </svg>
              <span v-if="cartItems.length > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                {{ cartItems.length }}
              </span>
            </button>

            <!-- Mobile User Icon -->
            <template v-if="!isAuthenticated">
              <button @click="goToLogin" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </button>
            </template>
            <template v-else>
              <div class="relative">
                <button @click="toggleUserDropdown" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
                  <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xs">{{ userInfo?.name?.charAt(0) || 'U' }}</span>
                  </div>
                </button>
                
                <!-- Mobile User Dropdown -->
                <div v-if="showUserDropdown" class="absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 py-2 z-50 animate-fade-in-up">
                  <button @click="goToDashboard" class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                  </button>
                  
                  <div class="border-t border-gray-100 my-1"></div>
                  
                  <button @click="logout" class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="font-medium">Logout</span>
                  </button>
                </div>
              </div>
            </template>
          </div>

          <!-- CTA Buttons -->
          <div class="hidden lg:flex items-center space-x-4">
            <button @click="goToProducts" class="group flex items-center space-x-2 text-gray-700 hover:text-green-600 font-semibold px-5 py-3 rounded-xl transition-all duration-300 hover:bg-green-50/80 backdrop-blur-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
              </svg>
              <span>Back to Home</span>
            </button>
          </div>

          <!-- Mobile CTA Button -->
          <div class="lg:hidden">
            <button @click="goToProducts" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Dropdown -->
      <div v-if="showMobileMenu" class="lg:hidden bg-white/95 backdrop-blur-xl border-t border-white/30 shadow-lg">
        <div class="px-4 py-4 space-y-2">
          <button @click="navigateToHome" class="w-full text-left block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            Home
          </button>
          <button @click="navigateToProducts" class="w-full text-left block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            Products
          </button>
          <button @click="navigateToAbout" class="w-full text-left block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            About
          </button>
          <button @click="navigateToContact" class="w-full text-left block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            Contact
          </button>
          
          <!-- Mobile Auth Buttons -->
          <div class="border-t border-gray-200 pt-4 mt-4">
            <template v-if="!isAuthenticated">
              <button @click="() => { goToLogin(); showMobileMenu = false; }" class="w-full flex items-center justify-center space-x-2 px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300 mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span>Login</span>
              </button>
              <button @click="() => { goToRegister(); showMobileMenu = false; }" class="w-full bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white px-4 py-3 rounded-xl font-bold transition-all duration-300 shadow-lg">
                Get Started
              </button>
            </template>
            <template v-else>
              <button @click="() => { goToDashboard(); showMobileMenu = false; }" class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-xl font-semibold transition-all duration-300 mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                </svg>
                <span>Dashboard</span>
              </button>
              <button @click="() => { logout(); showMobileMenu = false; }" class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-xl font-semibold transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
              </button>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile Progress Indicator -->
    <div class="lg:hidden bg-white/80 backdrop-blur-sm border-b border-white/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-center">
          <div class="flex items-center space-x-4 bg-gradient-to-r from-gray-50/90 to-green-50/90 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-lg border border-white/20">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-lg">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                </svg>
              </div>
              <span class="text-green-700 font-bold text-xs">Cart</span>
            </div>
            <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs font-bold">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              </div>
              <span class="text-gray-500 font-bold text-xs">Checkout</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
          Your
          <span class="relative inline-block">
            <span class="gradient-text bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent">
              Shopping Cart
            </span>
            <div class="absolute -bottom-2 left-0 w-full h-3 bg-gradient-to-r from-green-400/30 to-emerald-400/30 rounded-full blur-sm"></div>
          </span>
        </h1>
        <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
          Review your selected semen products and proceed to checkout when ready.
        </p>
      </div>

      <!-- Cart Content -->
      <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4 lg:space-y-6">
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Cart Items</h2>
            
            <div class="space-y-4">
              <div
                v-for="(item, index) in cartItems"
                :key="index"
                class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200"
              >
                <!-- Product Image -->
                <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                  <!-- Dynamic Product Image -->
                  <img 
                    v-if="item.semen.image" 
                    :src="getProductImageUrl(item.semen.image)" 
                    :alt="item.semen.name"
                    class="w-full h-full object-cover"
                  />
                  <!-- Fallback SVG Icon -->
                  <svg v-else class="w-12 h-12 sm:w-14 sm:h-14 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                </div>

                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                  <h3 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ item.semen.name }}</h3>
                  <p class="text-sm text-gray-600 mb-1">{{ item.semen.breed?.name }}</p>
                  <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">{{ item.semen.description }}</p>
                </div>

                <!-- Mobile Layout: Quantity and Price Row -->
                <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end space-x-4">
                  <!-- Quantity Controls -->
                  <div class="flex items-center space-x-2 sm:space-x-3">
                    <button
                      @click="decrementQuantity(index)"
                      :disabled="item.quantity <= 1"
                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed flex items-center justify-center transition-colors duration-200"
                    >
                      <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                      </svg>
                    </button>
                    <span class="w-8 sm:w-12 text-center font-bold text-base sm:text-lg">{{ item.quantity }}</span>
                    <button
                      @click="incrementQuantity(index)"
                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition-colors duration-200"
                    >
                      <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                    </button>
                  </div>

                  <!-- Price -->
                  <div class="text-right">
                    <p class="text-base sm:text-lg font-bold text-gray-900">₨{{ (item.semen.price * item.quantity).toFixed(2) }}</p>
                    <p class="text-xs sm:text-sm text-gray-500">₨{{ item.semen.price }} each</p>
                  </div>

                  <!-- Remove Button -->
                  <button
                    @click="removeItem(index)"
                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                  >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 sm:p-6 sticky top-24">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Order Summary</h2>
            
            <!-- Cart Items Preview -->
            <div class="space-y-3 mb-4 sm:mb-6">
              <div v-for="(item, index) in cartItems" :key="index" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-16 h-16 sm:w-18 sm:h-18 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center overflow-hidden">
                  <!-- Dynamic Product Image -->
                  <img 
                    v-if="item.semen.image" 
                    :src="getProductImageUrl(item.semen.image)" 
                    :alt="item.semen.name"
                    class="w-full h-full object-cover"
                  />
                  <!-- Fallback SVG Icon -->
                  <svg v-else class="w-8 h-8 sm:w-9 sm:h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ item.semen.name }}</h3>
                  <p class="text-xs text-gray-600">{{ item.semen.breed?.name }}</p>
                  <p class="text-xs sm:text-sm text-green-600 font-medium">₨{{ item.semen.price }} × {{ item.quantity }}</p>
                </div>
              </div>
            </div>

            <!-- Pricing -->
            <div class="space-y-3 mb-4 sm:mb-6">
              <div class="flex justify-between text-sm sm:text-base">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold">₨{{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-sm sm:text-base">
                <span class="text-gray-600">Visit Fee</span>
                <span class="font-semibold">₨{{ visitFee.toFixed(2) }}</span>
              </div>
              <div class="border-t pt-3">
                <div class="flex justify-between text-lg sm:text-xl font-bold">
                  <span>Total</span>
                  <span class="text-green-600">₨{{ total.toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
              <button
                @click="proceedToCheckout"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 sm:py-4 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-bold text-base sm:text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1"
              >
                Proceed to Checkout
              </button>
              
              <button
                @click="goToProducts"
                class="w-full border-2 border-green-600 text-green-600 py-2 sm:py-3 rounded-xl hover:bg-green-600 hover:text-white transition-all duration-200 font-semibold text-sm sm:text-base"
              >
                Back to Home
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty Cart -->
      <div v-else class="text-center py-12 sm:py-16">
        <div class="max-w-md mx-auto px-4">
          <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
          </div>
          <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
          <p class="text-base sm:text-lg text-gray-600 mb-6 sm:mb-8">
            Looks like you haven't added any items to your cart yet. Start shopping to fill it up!
          </p>
          <button
            @click="goToProducts"
            class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-bold text-base sm:text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1"
          >
            Go to Home
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'StoreCart',
  setup() {
    const router = useRouter()
    const cartItems = ref([])
    const specialInstructions = ref('')
    const visitFee = 50.00
    const showMobileMenu = ref(false)
    const isAuthenticated = ref(false)
    const userInfo = ref(null)
    const showUserDropdown = ref(false)

    // Load cart from localStorage
    const loadCart = () => {
      const savedCart = localStorage.getItem('store_cart')
      if (savedCart) {
        cartItems.value = JSON.parse(savedCart)
      }
    }

    // Save cart to localStorage
    const saveCart = () => {
      localStorage.setItem('store_cart', JSON.stringify(cartItems.value))
    }

    // Computed properties
    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + (item.semen.price * item.quantity)
      }, 0)
    })


    const total = computed(() => {
      return subtotal.value + visitFee
    })

    // Methods
    const incrementQuantity = (index) => {
      cartItems.value[index].quantity += 1
      saveCart()
    }

    const decrementQuantity = (index) => {
      if (cartItems.value[index].quantity > 1) {
        cartItems.value[index].quantity -= 1
        saveCart()
      }
    }

    const removeItem = (index) => {
      cartItems.value.splice(index, 1)
      saveCart()
      window.showSuccess('Item removed from cart!')
    }

    const goToProducts = () => {
      router.push('/')
    }

    const proceedToCheckout = () => {
      if (cartItems.value.length === 0) {
        window.showError('Your cart is empty!')
        return
      }
      
      // Save cart with special instructions
      const cartData = {
        items: cartItems.value,
        specialInstructions: specialInstructions.value
      }
      localStorage.setItem('store_cart', JSON.stringify(cartItems.value))
      localStorage.setItem('cart_instructions', specialInstructions.value)
      
      router.push('/checkout')
    }

    // Mobile menu functions
    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value
    }

    const toggleUserDropdown = () => {
      showUserDropdown.value = !showUserDropdown.value
    }

    // Navigation functions
    const navigateToHome = () => {
      console.log('Home clicked')
      showMobileMenu.value = false
      window.location.href = '/'
    }

    const navigateToProducts = () => {
      console.log('Products clicked')
      showMobileMenu.value = false
      window.location.href = '/#products'
    }

    const navigateToAbout = () => {
      console.log('About clicked')
      showMobileMenu.value = false
      window.location.href = '/#about'
    }

    const navigateToContact = () => {
      console.log('Contact clicked')
      showMobileMenu.value = false
      window.location.href = '/#contact'
    }

    const scrollToSection = (sectionId, event) => {
      event.preventDefault()
      const element = document.getElementById(sectionId)
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' })
      }
    }

    // Auth functions
    const goToLogin = () => {
      router.push('/login')
    }

    const goToRegister = () => {
      router.push('/register')
    }

    const goToDashboard = () => {
      // Redirect to appropriate dashboard based on user role
      if (userInfo.value?.role === 'farmer') {
        router.push('/farmer/dashboard')
      } else if (userInfo.value?.role === 'technician') {
        router.push('/technician/dashboard')
      } else if (userInfo.value?.role === 'admin') {
        router.push('/admin/dashboard')
      } else {
        // Default to farmer dashboard if role is not clear
        router.push('/farmer/dashboard')
      }
    }

    const logout = async () => {
      try {
        const token = localStorage.getItem('token')
        if (token) {
          // Call logout API
          await window.axios.post('/auth/logout', {}, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          })
        }
      } catch (error) {
        console.error('Logout API error:', error)
        // Continue with local logout even if API fails
      } finally {
        // Clear local storage
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        
        // Reset authentication state
        isAuthenticated.value = false
        userInfo.value = null
        
        // Show success message
        window.showSuccess('Logged out successfully!')
        
        // Redirect to home page
        router.push('/')
      }
    }

    // Check authentication status
    const checkAuthStatus = async () => {
      const token = localStorage.getItem('token')
      const user = localStorage.getItem('user')
      
      if (!token) {
        isAuthenticated.value = false
        userInfo.value = null
        return
      }

      // First check if we have user data in localStorage
      if (user) {
        try {
          userInfo.value = JSON.parse(user)
          isAuthenticated.value = true
          console.log('✅ Auth from localStorage - isAuthenticated:', isAuthenticated.value, 'userInfo:', userInfo.value)
        } catch (error) {
          console.error('Error parsing user data:', error)
          userInfo.value = null
          isAuthenticated.value = false
        }
      }

      // Then verify the token by trying to access a protected route
      try {
        const response = await window.axios.get('/auth/me', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
        
        if (response.data.success && response.data.user) {
          // Token is valid, user is authenticated
          isAuthenticated.value = true
          userInfo.value = response.data.user
          localStorage.setItem('user', JSON.stringify(response.data.user))
          console.log('✅ Auth successful - isAuthenticated:', isAuthenticated.value, 'userInfo:', userInfo.value)
        }
      } catch (error) {
        console.error('❌ Auth check failed:', error)
        isAuthenticated.value = false
        userInfo.value = null
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
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
      loadCart()
      checkAuthStatus()
    })

    return {
      cartItems,
      specialInstructions,
      visitFee,
      subtotal,
      total,
      incrementQuantity,
      decrementQuantity,
      removeItem,
      goToProducts,
      proceedToCheckout,
      showMobileMenu,
      toggleMobileMenu,
      scrollToSection,
      isAuthenticated,
      userInfo,
      goToLogin,
      goToRegister,
      goToDashboard,
      logout,
      showUserDropdown,
      toggleUserDropdown,
      navigateToHome,
      navigateToProducts,
      navigateToAbout,
      navigateToContact,
      getProductImageUrl
    }
  }
}
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #10b981, #059669, #047857);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
