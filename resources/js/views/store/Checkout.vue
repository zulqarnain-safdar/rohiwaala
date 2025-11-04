<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100">
    <!-- Enhanced Checkout Navigation -->
    <nav class="bg-white/95 backdrop-blur-xl shadow-2xl border-b border-green-100/30 sticky top-0 z-50">
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
              <p class="text-sm font-semibold text-green-600 tracking-wide">Semen Distribution System</p>
            </div>
          </div>

          <!-- Progress Indicator - Desktop -->
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
              <div class="w-20 h-1 bg-gradient-to-r from-green-500 to-green-600 rounded-full shadow-sm"></div>
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <span class="text-green-700 font-bold text-sm">Checkout</span>
              </div>
              <div class="w-20 h-1 bg-gray-300 rounded-full"></div>
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <span class="text-gray-500 font-bold text-sm">Complete</span>
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
            <template v-if="!authStatus.isAuthenticated">
              <button @click="showLoginModal = true" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </button>
            </template>
            <template v-else>
              <div class="relative">
                <button @click="toggleUserDropdown" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
                  <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xs">{{ authStatus.userInfo?.name?.charAt(0) || 'U' }}</span>
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
            <!-- Show different options based on authentication status -->
            <template v-if="authStatus.isAuthenticated">
              <!-- User is logged in -->
              <div class="relative">
                <!-- User Avatar and Name - Clickable -->
                <button @click="toggleUserDropdown" class="flex items-center space-x-3 bg-white/95 backdrop-blur-sm rounded-2xl px-4 py-2.5 border border-green-200/60 shadow-lg hover:shadow-xl transition-all duration-300 group">
                  <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-md ring-2 ring-green-100">
                    <span class="text-white font-bold text-sm">{{ authStatus.userInfo?.name?.charAt(0) || 'U' }}</span>
                  </div>
                  <div class="hidden sm:block">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Welcome back</p>
                    <p class="text-sm font-bold text-gray-800">{{ authStatus.userInfo?.name || 'User' }}</p>
                  </div>
                  <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                
                <!-- Dropdown Menu -->
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
            <template v-else>
              <!-- User is not logged in -->
              <div class="flex items-center space-x-4">
                <button @click="showLoginModal = true" class="group flex items-center space-x-2 text-gray-700 hover:text-green-600 font-semibold px-5 py-3 rounded-xl transition-all duration-300 hover:bg-green-50/80 backdrop-blur-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                  </svg>
                  <span class="hidden sm:inline">Login</span>
                </button>
                
                <button @click="showRegisterModal = true" class="group relative overflow-hidden bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white px-8 py-3 rounded-2xl font-bold transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-105">
                  <div class="absolute inset-0 bg-gradient-to-r from-green-600 via-green-700 to-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <div class="relative flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Get Started</span>
                  </div>
                </button>
              </div>
            </template>
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
            <template v-if="!authStatus.isAuthenticated">
              <button @click="() => { showLoginModal = true; showMobileMenu = false; }" class="w-full flex items-center justify-center space-x-2 px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300 mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span>Login</span>
              </button>
              <button @click="() => { showRegisterModal = true; showMobileMenu = false; }" class="w-full bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white px-4 py-3 rounded-xl font-bold transition-all duration-300 shadow-lg">
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
          <div class="flex items-center space-x-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-full px-6 py-3 shadow-lg border border-green-200">
            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-md">
              2
            </div>
            <span class="text-green-700 font-bold text-sm">Checkout</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Customer Information -->
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Customer Information</h2>
              <!-- Show account info indicator for authenticated users -->
              <div v-if="authStatus.isAuthenticated" class="flex items-center space-x-2 bg-green-50 text-green-700 px-3 py-2 rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>From your account</span>
              </div>
            </div>
            
            <!-- Info message for authenticated users -->
            <div v-if="authStatus.isAuthenticated" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
              <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                  <p class="text-sm text-blue-800 font-medium">Your account information has been pre-filled below</p>
                  <p class="text-xs text-blue-600 mt-1">You can edit any field if needed before placing your order</p>
                </div>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                <input
                  v-model="customerInfo.name"
                  type="text"
                  required
                  disabled
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed"
                  placeholder="Enter your full name"
                />
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number *</label>
                <input
                  v-model="customerInfo.mobile"
                  type="tel"
                  required
                  disabled
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed"
                  placeholder="+92 300 1234567"
                />
              </div>
              
            </div>
          </div>

          <!-- Farm Information -->
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Farm Information</h2>
            
            <!-- Farm Selection -->
            <div class="mb-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Select Farm *</label>
              <div class="flex space-x-4">
                <select
                  v-model="selectedFarmId"
                  @change="onFarmSelect"
                  class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">Choose existing farm or add new</option>
                  <option v-for="farm in userFarms" :key="farm.id" :value="farm.id">
                    {{ farm.name }} - {{ farmAddresses[`${farm.latitude},${farm.longitude}`] || 'Loading...' }}
                  </option>
                  <option value="new">+ Add New Farm</option>
                </select>
                <button
                  v-if="selectedFarmId === 'new'"
                  @click="showFarmForm = true"
                  class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold"
                >
                  Add Farm
                </button>
              </div>
            </div>
            
            <!-- New Farm Form (only shown when adding new farm) -->
            <div v-if="showFarmForm" class="border-t border-gray-200 pt-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Farm</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Name *</label>
                  <input
                    v-model="farmInfo.name"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Enter farm name"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Owner Name</label>
                  <input
                    v-model="farmInfo.father_name"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Enter father's name (optional)"
                  />
                </div>
                
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Location *</label>
                  <GoogleMapLocationPicker 
                    v-model="farmLocation"
                    @update:modelValue="onLocationChange"
                    :initial-lat="31.5204"
                    :initial-lng="74.3587"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Cows *</label>
                  <input
                    v-model.number="farmInfo.number_of_cows"
                    type="number"
                    min="0"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Calves *</label>
                  <input
                    v-model.number="farmInfo.number_of_calves"
                    type="number"
                    min="0"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Bulls *</label>
                  <input
                    v-model.number="farmInfo.number_of_bulls"
                    type="number"
                    min="0"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Goats *</label>
                  <input
                    v-model.number="farmInfo.number_of_goats"
                    type="number"
                    min="0"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0"
                  />
                </div>
              </div>
              
              <!-- Farm Form Actions -->
              <div class="flex justify-end space-x-4 mt-6">
                <button
                  @click="cancelFarmForm"
                  class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold"
                >
                  Cancel
                </button>
                <button
                  @click="saveFarm"
                  class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold"
                >
                  Save Farm
                </button>
              </div>
            </div>

            <!-- Selected Farm Info (shown when existing farm is selected) -->
            <div v-else-if="selectedFarmId && selectedFarmId !== 'new'" class="border-t border-gray-200 pt-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Selected Farm</h3>
              <div v-if="selectedFarm" class="bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm font-semibold text-gray-700">Farm Name:</p>
                    <p class="text-gray-900">{{ selectedFarm.name }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-700">Location:</p>
                    <p class="text-gray-900">{{ formattedFarmAddress || 'Loading address...' }}</p>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>

          <!-- Cow Insemination Detail -->
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Cow Insemination Detail</h2>
            
            <div class="space-y-6">
              <div v-for="(item, itemIndex) in cartItems" :key="itemIndex">
                <!-- Show separate forms for each quantity -->
                <div v-for="(cowDetail, cowIndex) in item.cowDetails" :key="cowIndex" class="border border-gray-200 rounded-xl p-4 mb-4">
                  <h3 class="font-semibold text-gray-900 mb-2">{{ item.semen.name }}</h3>
                  <p class="text-sm text-gray-600 mb-4">Cow #{{ cowIndex + 1 }} of {{ item.quantity }}</p>
                  
                  <!-- Heat Start Time - Full Width on Top -->
                  <div class="mb-3 w-1/3">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Heat Start Time *</label>
                    <input
                      v-model="cowDetail.heat_started_at"
                      type="datetime-local"
                      :max="getMaxDateTime()"
                      :min="getMinDateTime()"
                      required
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                      @change="validateHeatStartTime(cowDetail)"
                    />
                  </div>
                  
                  <!-- Cow Type and Cow Age - Two Columns Below -->
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1.5">Cow Type (optional)</label>
                      <select
                        v-model="cowDetail.cow_type"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                      >
                        <option value="">Select cow type</option>
                        <option v-for="cowType in cowTypes" :key="cowType.id" :value="cowType.name">
                          {{ cowType.name }}
                        </option>
                      </select>
                    </div>
                    
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1.5">Cow Age (months)</label>
                      <input
                        v-model.number="cowDetail.cow_age"
                        type="number"
                        min="1"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Enter age"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
              <textarea
                v-model="orderNotes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Any additional notes for the technician..."
              ></textarea>
            </div>
          </div>

          <!-- Technician Selection -->
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Technician</h2>
            
            <div class="mb-6">
              <div class="flex space-x-4">
                <button
                  @click="technicianSelectionMethod = 'nearby'"
                  :class="[
                    'px-4 py-2 rounded-lg font-medium transition-colors',
                    technicianSelectionMethod === 'nearby'
                      ? 'bg-green-600 text-white'
                      : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                  ]"
                >
                  Nearby Technicians (30km)
                </button>
                <button
                  @click="technicianSelectionMethod = 'manual'"
                  :class="[
                    'px-4 py-2 rounded-lg font-medium transition-colors',
                    technicianSelectionMethod === 'manual'
                      ? 'bg-green-600 text-white'
                      : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                  ]"
                >
                  Manual Search
                </button>
              </div>
            </div>

            <!-- Nearby Technicians -->
            <div v-if="technicianSelectionMethod === 'nearby'" class="space-y-4">
              <div v-if="nearbyTechnicians.length === 0 && !loadingNearbyTechnicians" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="mt-2 text-gray-500">No technicians found within 30km radius</p>
                <p class="text-sm text-gray-400">Try manual search instead</p>
              </div>
              
              <div v-if="loadingNearbyTechnicians" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                <p class="mt-2 text-gray-500">Finding nearby technicians...</p>
              </div>

              <div v-if="nearbyTechnicians.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="technician in nearbyTechnicians"
                  :key="technician.id"
                  @click="selectedTechnician = technician.id"
                  :class="[
                    'p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    selectedTechnician === technician.id
                      ? 'border-green-500 bg-green-50'
                      : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900">{{ technician.name }}</h3>
                      <p class="text-sm text-gray-600">{{ technician.mobile_1 }}</p>
                      <p class="text-sm text-gray-500">{{ technician.years_of_experience }} years experience</p>
                      <p class="text-sm text-gray-500">{{ technician.tehsil_city || technician.city }}, {{ technician.district }}, {{ technician.division || '' }}, {{ technician.province }}</p>
                    </div>
                    <div class="text-right">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ technician.distance }}km away
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Manual Search -->
            <div v-if="technicianSelectionMethod === 'manual'" class="space-y-4">
              <!-- Search Filters -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                  <select
                    v-model="searchFilters.province"
                    @change="onProvinceChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">All Provinces</option>
                    <option v-for="province in getProvinces()" :key="province" :value="province">
                      {{ province }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Division</label>
                  <select
                    v-model="searchFilters.division"
                    @change="onDivisionChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">All Divisions</option>
                    <option v-for="division in getDivisionsForProvince(searchFilters.province)" :key="division" :value="division">
                      {{ division }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">District</label>
                  <select
                    v-model="searchFilters.district"
                    @change="onDistrictChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">All Districts</option>
                    <option v-for="district in getDistrictsForDivision(searchFilters.division)" :key="district" :value="district">
                      {{ district }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Tehsil/City</label>
                  <input
                    v-model="searchFilters.tehsil_city"
                    @input="loadTechnicians"
                    type="text"
                    placeholder="Enter tehsil/city"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                </div>
              </div>

              <!-- Search Results -->
              <div v-if="manualTechnicians.length === 0 && !loadingManualTechnicians && hasSearched" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="mt-2 text-gray-500">No technicians found with selected criteria</p>
                <p class="text-sm text-gray-400">Try adjusting your search filters</p>
              </div>
              
              <div v-if="loadingManualTechnicians" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                <p class="mt-2 text-gray-500">Searching technicians...</p>
              </div>

              <div v-if="manualTechnicians.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="technician in manualTechnicians"
                  :key="technician.id"
                  @click="selectedTechnician = technician.id"
                  :class="[
                    'p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    selectedTechnician === technician.id
                      ? 'border-green-500 bg-green-50'
                      : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900">{{ technician.name }}</h3>
                      <p class="text-sm text-gray-600">{{ technician.mobile_1 }}</p>
                      <p class="text-sm text-gray-500">{{ technician.years_of_experience }} years experience</p>
                      <p class="text-sm text-gray-500">{{ technician.tehsil_city || technician.city }}, {{ technician.district }}, {{ technician.division || '' }}, {{ technician.province }}</p>
                    </div>
                    <div class="text-right">
                      <span :class="getAvailabilityBadgeClass(technician.availability)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ technician.availability }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 sticky top-24">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
            
            <!-- Cart Items -->
            <div class="space-y-4 mb-6">
              <div v-for="(item, index) in cartItems" :key="index" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center overflow-hidden">
                  <!-- Dynamic Product Image -->
                  <img 
                    v-if="item.semen.image" 
                    :src="getProductImageUrl(item.semen.image)" 
                    :alt="item.semen.name"
                    class="w-full h-full object-cover"
                  />
                  <!-- Fallback SVG Icon -->
                  <svg v-else class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900 text-sm">{{ item.semen.name }}</h3>
                  <p class="text-xs text-gray-600">{{ item.semen.breed?.name }}</p>
                  <p class="text-sm text-green-600 font-medium">₨{{ item.semen.price }} × {{ item.quantity }}</p>
                </div>
              </div>
            </div>

            <!-- Pricing -->
            <div class="space-y-3 mb-6">
              <div class="flex justify-between">
                <span class="text-gray-600">Semen Total</span>
                <span class="font-semibold">₨{{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Visit Fee</span>
                <span class="font-semibold">₨{{ visitFee.toFixed(2) }}</span>
              </div>
              <div class="border-t pt-3">
                <div class="flex justify-between text-lg font-bold">
                  <span>Total</span>
                  <span class="text-green-600">₨{{ total.toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <!-- Place Order Button -->
            <button
              @click="placeOrder"
              :disabled="!canPlaceOrder || isPlacingOrder"
              :class="[
                'w-full py-4 rounded-xl font-semibold text-lg transition-all duration-200',
                canPlaceOrder && !isPlacingOrder
                  ? 'store-button text-lg hover-lift'
                  : 'bg-gray-300 text-gray-500 cursor-not-allowed'
              ]"
            >
              <span v-if="isPlacingOrder" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Placing Order...
              </span>
              <span v-else>Place Order</span>
            </button>

            <!-- Terms -->
            <p class="text-xs text-gray-500 mt-4 text-center">
              By placing this order, you agree to our terms of service and privacy policy.
            </p>
          </div>
        </div>
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
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import LoginModal from '@/components/LoginModal.vue'
import RegisterModal from '@/components/RegisterModal.vue'
import GoogleMapLocationPicker from '@/components/GoogleMapLocationPicker.vue'

export default {
  name: 'StoreCheckout',
  components: {
    LoginModal,
    RegisterModal,
    GoogleMapLocationPicker
  },
  setup() {
    const router = useRouter()
    const cartItems = ref([])
    const isPlacingOrder = ref(false)
    const visitFee = 50.00
    const showLoginModal = ref(false)
    const showRegisterModal = ref(false)
    const isAuthenticated = ref(false)
    const userFarms = ref([])
    const farmAddresses = ref({}) // Cache for farm addresses
    const selectedFarmId = ref('')
    const selectedFarm = ref(null)
    const formattedFarmAddress = ref('')
    const showFarmForm = ref(false)
    const farmLocation = ref({ lat: null, lng: null })
    const userInfo = ref(null)
    const forceUpdate = ref(0)
    const showUserDropdown = ref(false)
    const showMobileMenu = ref(false)
    const cowTypes = ref([])
    
    // Force reactivity by using a computed property
    const authStatus = computed(() => ({
      isAuthenticated: isAuthenticated.value,
      userInfo: userInfo.value,
      forceUpdate: forceUpdate.value
    }))

    // Customer Information
    const customerInfo = ref({
      name: '',
      mobile: ''
    })

    // Function to populate customer info from user account
    const populateCustomerInfo = () => {
      if (authStatus.value.isAuthenticated && authStatus.value.userInfo) {
        const user = authStatus.value.userInfo
        customerInfo.value = {
          name: user.name || '',
          mobile: user.mobile || ''
        }
        console.log('✅ Customer info populated from user account:', customerInfo.value)
      }
    }

    // Farm Information
    const farmInfo = ref({
      name: '',
      father_name: '',
      latitude: '',
      longitude: '',
      number_of_cows: 0,
      number_of_calves: 0,
      number_of_bulls: 0,
      number_of_goats: 0
    })

    // Order Details
    const orderNotes = ref('')

    // Technician Selection
    const technicianSelectionMethod = ref('nearby')
    const selectedTechnician = ref(null)
    const nearbyTechnicians = ref([])
    const manualTechnicians = ref([])
    const loadingNearbyTechnicians = ref(false)
    const loadingManualTechnicians = ref(false)
    const hasSearched = ref(false)

    // Search filters
    const searchFilters = ref({
      province: '',
      division: '',
      district: '',
      tehsil_city: ''
    })
    
    // Pakistan administrative divisions and districts data
    const pakistanData = {
      'Punjab': {
        divisions: ['Lahore', 'Rawalpindi', 'Faisalabad', 'Multan', 'Sargodha', 'Bahawalpur', 'Dera Ghazi Khan', 'Sahiwal', 'Gujranwala'],
        districts: {
          'Lahore': ['Lahore', 'Kasur', 'Nankana Sahib', 'Sheikhupura'],
          'Rawalpindi': ['Rawalpindi', 'Attock', 'Chakwal', 'Jhelum', 'Talagang'],
          'Faisalabad': ['Faisalabad', 'Chiniot', 'Jhang', 'Toba Tek Singh'],
          'Multan': ['Multan', 'Khanewal', 'Lodhran', 'Vehari'],
          'Sargodha': ['Sargodha', 'Khushab', 'Mianwali', 'Bhakkar'],
          'Bahawalpur': ['Bahawalpur', 'Bahawalnagar', 'Rahim Yar Khan'],
          'Dera Ghazi Khan': ['Dera Ghazi Khan', 'Layyah', 'Muzaffargarh', 'Rajanpur'],
          'Sahiwal': ['Sahiwal', 'Okara', 'Pakpattan'],
          'Gujranwala': ['Gujranwala', 'Gujrat', 'Hafizabad', 'Mandi Bahauddin', 'Narowal', 'Sialkot']
        },
        tehsilCities: {
          'Lahore': ['Lahore', 'Kasur', 'Nankana Sahib', 'Sheikhupura'],
          'Rawalpindi': ['Rawalpindi', 'Attock', 'Chakwal', 'Jhelum', 'Talagang'],
          'Faisalabad': ['Faisalabad', 'Chiniot', 'Jhang', 'Toba Tek Singh'],
          'Multan': ['Multan', 'Khanewal', 'Lodhran', 'Vehari'],
          'Sargodha': ['Sargodha', 'Khushab', 'Mianwali', 'Bhakkar'],
          'Bahawalpur': ['Bahawalpur', 'Bahawalnagar', 'Rahim Yar Khan'],
          'Dera Ghazi Khan': ['Dera Ghazi Khan', 'Layyah', 'Muzaffargarh', 'Rajanpur'],
          'Sahiwal': ['Sahiwal', 'Okara', 'Pakpattan'],
          'Gujranwala': ['Gujranwala', 'Gujrat', 'Hafizabad', 'Mandi Bahauddin', 'Narowal', 'Sialkot']
        }
      },
      'Sindh': {
        divisions: ['Karachi', 'Hyderabad', 'Sukkur', 'Larkana', 'Mirpur Khas', 'Shaheed Benazirabad'],
        districts: {
          'Karachi': ['Karachi East', 'Karachi West', 'Karachi South', 'Karachi Central', 'Malir', 'Korangi'],
          'Hyderabad': ['Hyderabad', 'Tando Allahyar', 'Tando Muhammad Khan', 'Matli', 'Badin', 'Tharparkar', 'Umerkot'],
          'Sukkur': ['Sukkur', 'Ghotki', 'Khairpur', 'Naushahro Feroze', 'Shaheed Benazirabad'],
          'Larkana': ['Larkana', 'Kamber Shahdadkot', 'Shikarpur', 'Jacobabad', 'Kashmore'],
          'Mirpur Khas': ['Mirpur Khas', 'Tharparkar', 'Umerkot', 'Sanghar'],
          'Shaheed Benazirabad': ['Nawabshah', 'Sanghar', 'Naushahro Feroze']
        },
        tehsilCities: {
          'Karachi East': ['Karachi East', 'Karachi West', 'Karachi South', 'Karachi Central', 'Malir', 'Korangi'],
          'Hyderabad': ['Hyderabad', 'Tando Allahyar', 'Tando Muhammad Khan', 'Matli', 'Badin', 'Tharparkar', 'Umerkot'],
          'Sukkur': ['Sukkur', 'Ghotki', 'Khairpur', 'Naushahro Feroze', 'Shaheed Benazirabad'],
          'Larkana': ['Larkana', 'Kamber Shahdadkot', 'Shikarpur', 'Jacobabad', 'Kashmore'],
          'Mirpur Khas': ['Mirpur Khas', 'Tharparkar', 'Umerkot', 'Sanghar'],
          'Nawabshah': ['Nawabshah', 'Sanghar', 'Naushahro Feroze']
        }
      },
      'KPK': {
        divisions: ['Peshawar', 'Mardan', 'Malakand', 'Hazara', 'Kohat', 'Bannu', 'Dera Ismail Khan'],
        districts: {
          'Peshawar': ['Peshawar', 'Charsadda', 'Nowshera'],
          'Mardan': ['Mardan', 'Swabi'],
          'Malakand': ['Malakand', 'Swat', 'Buner', 'Shangla', 'Upper Dir', 'Lower Dir'],
          'Hazara': ['Abbottabad', 'Mansehra', 'Battagram', 'Kohistan', 'Torghar'],
          'Kohat': ['Kohat', 'Hangu', 'Karak', 'Kurram', 'Orakzai'],
          'Bannu': ['Bannu', 'Lakki Marwat', 'North Waziristan', 'South Waziristan'],
          'Dera Ismail Khan': ['Dera Ismail Khan', 'Tank']
        },
        tehsilCities: {
          'Peshawar': ['Peshawar', 'Charsadda', 'Nowshera'],
          'Mardan': ['Mardan', 'Swabi'],
          'Malakand': ['Malakand', 'Swat', 'Buner', 'Shangla', 'Upper Dir', 'Lower Dir'],
          'Abbottabad': ['Abbottabad', 'Mansehra', 'Battagram', 'Kohistan', 'Torghar'],
          'Kohat': ['Kohat', 'Hangu', 'Karak', 'Kurram', 'Orakzai'],
          'Bannu': ['Bannu', 'Lakki Marwat', 'North Waziristan', 'South Waziristan'],
          'Dera Ismail Khan': ['Dera Ismail Khan', 'Tank']
        }
      },
      'Balochistan': {
        divisions: ['Quetta', 'Kalat', 'Makran', 'Sibi', 'Nasirabad', 'Zhob'],
        districts: {
          'Quetta': ['Quetta', 'Pishin', 'Killa Abdullah', 'Chagai'],
          'Kalat': ['Kalat', 'Mastung', 'Khuzdar', 'Lasbela', 'Awaran', 'Washuk'],
          'Makran': ['Kech', 'Gwadar', 'Panjgur'],
          'Sibi': ['Sibi', 'Kohlu', 'Dera Bugti', 'Barkhan'],
          'Nasirabad': ['Nasirabad', 'Jaffarabad', 'Jhal Magsi', 'Kachhi'],
          'Zhob': ['Zhob', 'Sherani', 'Musa Khel', 'Loralai', 'Duki', 'Harnai', 'Ziarat']
        },
        tehsilCities: {
          'Quetta': ['Quetta', 'Pishin', 'Killa Abdullah', 'Chagai'],
          'Kalat': ['Kalat', 'Mastung', 'Khuzdar', 'Lasbela', 'Awaran', 'Washuk'],
          'Kech': ['Kech', 'Gwadar', 'Panjgur'],
          'Sibi': ['Sibi', 'Kohlu', 'Dera Bugti', 'Barkhan'],
          'Nasirabad': ['Nasirabad', 'Jaffarabad', 'Jhal Magsi', 'Kachhi'],
          'Zhob': ['Zhob', 'Sherani', 'Musa Khel', 'Loralai', 'Duki', 'Harnai', 'Ziarat']
        }
      },
      'Islamabad': {
        divisions: ['Islamabad'],
        districts: {
          'Islamabad': ['Islamabad']
        },
        tehsilCities: {
          'Islamabad': ['Islamabad']
        }
      }
    }

    // Computed properties
    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + (item.semen.price * item.quantity)
      }, 0)
    })

    const total = computed(() => subtotal.value + visitFee)

    // Load Google Maps API if not already loaded
    const loadGoogleMapsAPI = () => {
      return new Promise((resolve, reject) => {
        // Check if already loaded
        if (window.google && window.google.maps) {
          resolve()
          return
        }

        // Check if script is already being loaded
        const existing = document.querySelector('script[data-google-maps-loader]')
        if (existing) {
          // Wait for the existing load to complete
          const checkLoaded = setInterval(() => {
            if (window.google && window.google.maps) {
              clearInterval(checkLoaded)
              resolve()
            }
          }, 100)
          return
        }

        const script = document.createElement('script')
        script.src = `https://maps.googleapis.com/maps/api/js?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY}&libraries=places&callback=initGoogleMapsForCheckout`
        script.async = true
        script.defer = true
        script.setAttribute('data-google-maps-loader', 'true')
        
        window.initGoogleMapsForCheckout = () => {
          resolve()
          delete window.initGoogleMapsForCheckout
        }
        
        script.onerror = () => {
          reject(new Error('Failed to load Google Maps API'))
          delete window.initGoogleMapsForCheckout
        }
        
        document.head.appendChild(script)
      })
    }

    // Wait for Google Maps API to be loaded
    const waitForGoogleMaps = async () => {
      try {
        await loadGoogleMapsAPI()
        return true
      } catch (error) {
        console.error('Failed to load Google Maps API:', error)
        return false
      }
    }

    // Format location in user-friendly way using reverse geocoding
    const formatFarmLocation = async (farm) => {
      if (!farm || !farm.latitude || !farm.longitude) return 'Farm Location'
      
      // Check cache first
      const cacheKey = `${farm.latitude},${farm.longitude}`
      if (farmAddresses.value[cacheKey]) {
        return farmAddresses.value[cacheKey]
      }
      
      // Wait for Google Maps to be available
      const googleMapsAvailable = await waitForGoogleMaps()
      if (!googleMapsAvailable) {
        console.warn('Google Maps API not loaded after timeout, using fallback location')
        const fallback = 'Farm Location'
        farmAddresses.value[cacheKey] = fallback
        return fallback
      }
      
      try {
        const geocoder = new google.maps.Geocoder()
        const latlng = { lat: parseFloat(farm.latitude), lng: parseFloat(farm.longitude) }
        
        return new Promise((resolve) => {
          geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === 'OK' && results[0]) {
              // Extract the most relevant address components
              const address = results[0].formatted_address
              // Cache the result
              farmAddresses.value[cacheKey] = address
              resolve(address)
            } else {
              const fallback = 'Farm Location'
              farmAddresses.value[cacheKey] = fallback
              resolve(fallback)
            }
          })
        })
      } catch (error) {
        console.error('Geocoding error:', error)
        const fallback = 'Farm Location'
        farmAddresses.value[cacheKey] = fallback
        return fallback
      }
    }

    const canPlaceOrder = computed(() => {
      if (cartItems.value.length === 0) return false
      if (!selectedFarmId.value) return false
      if (!selectedTechnician.value) return false
      
      return cartItems.value.every(item => 
        item.cowDetails && item.cowDetails.length === item.quantity &&
        item.cowDetails.every(cowDetail => 
          cowDetail.heat_started_at
        )
      )
    })

    // Load cart from localStorage
    const loadCart = () => {
      const savedCart = localStorage.getItem('store_cart')
      if (savedCart) {
        const parsedCart = JSON.parse(savedCart)
        // Initialize cowDetails for each item based on quantity
        cartItems.value = parsedCart.map(item => ({
          ...item,
          cowDetails: item.cowDetails || Array.from({ length: item.quantity }, () => ({
            cow_type: '',
            cow_age: '',
            heat_started_at: ''
          }))
        }))
      }
    }

    // Load nearby technicians
    const loadNearbyTechnicians = async () => {
      if (!farmInfo.value.latitude || !farmInfo.value.longitude) {
        console.log('No farm coordinates available for technician search')
        return
      }
      
      console.log('Searching for technicians near:', farmInfo.value.latitude, farmInfo.value.longitude)
      
      try {
        loadingNearbyTechnicians.value = true
        const response = await window.axios.get('/technicians/nearby', {
          params: {
            latitude: farmInfo.value.latitude,
            longitude: farmInfo.value.longitude,
            radius: 30 // 30km radius
          }
        })
        console.log('Nearby technicians response:', response.data)
        nearbyTechnicians.value = response.data.technicians || []
      } catch (error) {
        console.error('Error loading nearby technicians:', error)
        nearbyTechnicians.value = []
      } finally {
        loadingNearbyTechnicians.value = false
      }
    }

    // Helper functions for location dropdowns
    const getProvinces = () => {
      return Object.keys(pakistanData)
    }

    const getDivisionsForProvince = (province) => {
      if (!province) {
        // If no province selected, show all divisions from all provinces
        const allDivisions = []
        for (const prov in pakistanData) {
          allDivisions.push(...pakistanData[prov].divisions)
        }
        return allDivisions.sort()
      }
      if (!pakistanData[province]) return []
      return pakistanData[province].divisions || []
    }

    const getDistrictsForDivision = (division) => {
      if (!division) {
        // If no division selected, show all districts from all divisions
        const allDistricts = []
        for (const province in pakistanData) {
          for (const div in pakistanData[province].districts) {
            allDistricts.push(...pakistanData[province].districts[div])
          }
        }
        return allDistricts.sort()
      }
      
      // Find the province that contains this division
      for (const province in pakistanData) {
        if (pakistanData[province].divisions.includes(division)) {
          return pakistanData[province].districts[division] || []
        }
      }
      return []
    }


    // Event handlers for cascading dropdowns
    const onProvinceChange = () => {
      searchFilters.value.division = ''
      searchFilters.value.district = ''
      loadTechnicians()
    }

    const onDivisionChange = () => {
      searchFilters.value.district = ''
      loadTechnicians()
    }

    const onDistrictChange = () => {
      loadTechnicians()
    }

    // Load technicians manually
    const loadTechnicians = async () => {
      try {
        loadingManualTechnicians.value = true
        hasSearched.value = true
        
        const params = new URLSearchParams()
        if (searchFilters.value.province) params.append('province', searchFilters.value.province)
        if (searchFilters.value.division) params.append('division', searchFilters.value.division)
        if (searchFilters.value.district) params.append('district', searchFilters.value.district)
        if (searchFilters.value.tehsil_city) params.append('tehsil_city', searchFilters.value.tehsil_city)
        params.append('status', 'active')
        
        const response = await window.axios.get(`/technicians/search?${params}`)
        manualTechnicians.value = response.data.technicians || []
      } catch (error) {
        console.error('Error loading technicians:', error)
        manualTechnicians.value = []
      } finally {
        loadingManualTechnicians.value = false
      }
    }


    // Get availability badge class
    const getAvailabilityBadgeClass = (availability) => {
      const classes = {
        online: 'bg-green-100 text-green-800',
        offline: 'bg-gray-100 text-gray-800',
        on_leave: 'bg-yellow-100 text-yellow-800'
      }
      return classes[availability] || 'bg-gray-100 text-gray-800'
    }

    // Place order
    const placeOrder = async () => {
      if (!canPlaceOrder.value) return

      // Check if farm is selected
      if (!selectedFarmId.value) {
        window.showError('Please select a farm before placing the order.')
        return
      }

      isPlacingOrder.value = true
      try {
        const token = localStorage.getItem('token')
        let farmId = selectedFarmId.value

        // If user is authenticated, use the selected farm
        if (token && selectedFarmId.value !== 'new') {
          const orderData = {
            cattle_farm_id: selectedFarmId.value,
            technician_id: selectedTechnician.value,
            order_items: cartItems.value.flatMap(item => 
              item.cowDetails.map(cowDetail => ({
                bull_semen_id: item.semen.id,
                quantity: 1, // Each cow detail represents 1 quantity
                cow_type: cowDetail.cow_type,
                cow_age: cowDetail.cow_age,
                heat_started_at: cowDetail.heat_started_at
              }))
            ),
            notes: orderNotes.value
          }

          console.log('Placing authenticated order with data:', orderData)

          const orderResponse = await window.axios.post('/orders', orderData, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          })
          
          console.log('Order response:', orderResponse.data)
          
          // Clear cart
          localStorage.removeItem('store_cart')
          
          // Redirect to success page
          router.push(`/order-success/${orderResponse.data.order.id}`)
        } else {
          // Guest order (fallback)
          const orderData = {
            customer_info: {
              name: customerInfo.value.name,
              mobile: customerInfo.value.mobile
            },
            farm_info: {
              name: farmInfo.value.name,
              father_name: farmInfo.value.father_name,
              latitude: farmInfo.value.latitude,
              longitude: farmInfo.value.longitude,
              number_of_cows: farmInfo.value.number_of_cows,
              number_of_calves: farmInfo.value.number_of_calves,
              number_of_bulls: farmInfo.value.number_of_bulls,
              number_of_goats: farmInfo.value.number_of_goats
            },
            technician_id: selectedTechnician.value,
            order_items: cartItems.value.flatMap(item => 
              item.cowDetails.map(cowDetail => ({
                bull_semen_id: item.semen.id,
                quantity: 1, // Each cow detail represents 1 quantity
                cow_type: cowDetail.cow_type,
                cow_age: cowDetail.cow_age,
                heat_started_at: cowDetail.heat_started_at
              }))
            ),
            notes: orderNotes.value
          }

          console.log('Placing guest order with data:', orderData)

          const orderResponse = await window.axios.post('/guest/orders', orderData)
          
          console.log('Order response:', orderResponse.data)
          
          // Clear cart
          localStorage.removeItem('store_cart')
          
          // Redirect to success page
          router.push(`/order-success/${orderResponse.data.order.id}`)
        }
        
      } catch (error) {
        console.error('Error placing order:', error)
        console.error('Error details:', error.response?.data)
        window.showError('Failed to place order. Please try again.')
      } finally {
        isPlacingOrder.value = false
      }
    }

    // Navigation methods
    const goToLogin = () => {
      router.push('/login')
    }

    const goToRegister = () => {
      router.push('/register')
    }

    // Watchers
    watch([farmInfo], () => {
      if (farmInfo.value.latitude && farmInfo.value.longitude && technicianSelectionMethod.value === 'nearby') {
        loadNearbyTechnicians()
      }
    }, { deep: true })

    watch(technicianSelectionMethod, (newMethod) => {
      if (newMethod === 'nearby' && farmInfo.value.latitude && farmInfo.value.longitude) {
        loadNearbyTechnicians()
      } else if (newMethod === 'manual') {
        hasSearched.value = false
        manualTechnicians.value = []
      }
    })

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
          forceUpdate.value++
          console.log('✅ Auth from localStorage - isAuthenticated:', isAuthenticated.value, 'userInfo:', userInfo.value)
        } catch (error) {
          console.error('Error parsing user data:', error)
          userInfo.value = null
          isAuthenticated.value = false
        }
      }

      // Then verify the token by trying to access a protected route
      try {
        // Try auth/me first
        try {
          const meResponse = await window.axios.get('/auth/me', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          })
          
          if (meResponse.data.success && meResponse.data.user) {
            // Token is valid, user is authenticated
            isAuthenticated.value = true
            userInfo.value = meResponse.data.user
            localStorage.setItem('user', JSON.stringify(meResponse.data.user))
            forceUpdate.value++
            console.log('✅ Auth successful - isAuthenticated:', isAuthenticated.value, 'userInfo:', userInfo.value)
            return // Exit early since we got the user info
          }
        } catch (meError) {
          // Fallback to farmer/farms endpoint
          const response = await window.axios.get('/farmer/farms', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          })
          
          // Check if the API call was successful (status 200 and no error)
          if (response.status === 200 && !response.data.error) {
            // Token is valid, user is authenticated
            isAuthenticated.value = true
            forceUpdate.value++
            
            // If we don't have user info, try to get it from auth/me
            if (!userInfo.value) {
              try {
                const meResponse = await window.axios.get('/auth/me', {
                  headers: {
                    'Authorization': `Bearer ${token}`
                  }
                })
                if (meResponse.data.success && meResponse.data.user) {
                  userInfo.value = meResponse.data.user
                  localStorage.setItem('user', JSON.stringify(meResponse.data.user))
                  forceUpdate.value++
                }
              } catch (meError) {
                console.error('Could not get user info:', meError)
              }
            }
          } else {
            isAuthenticated.value = false
            userInfo.value = null
            forceUpdate.value++
            localStorage.removeItem('token')
            localStorage.removeItem('user')
          }
        }
      } catch (error) {
        console.error('❌ Auth check failed:', error)
        isAuthenticated.value = false
        userInfo.value = null
        forceUpdate.value++
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    }

    const handleLoginSuccess = (user) => {
      isAuthenticated.value = true
      userInfo.value = user
      showLoginModal.value = false
      // Populate customer info after successful login
      populateCustomerInfo()
      // User is now logged in, they can proceed with checkout
    }

    const handleRegisterSuccess = (user) => {
      isAuthenticated.value = true
      userInfo.value = user
      showRegisterModal.value = false
      // Populate customer info after successful registration
      populateCustomerInfo()
      // User is now logged in, they can proceed with checkout
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

    // Farm management functions
    const loadUserFarms = async () => {
      try {
        const token = localStorage.getItem('token')
        if (!token) return

        const response = await window.axios.get('/farmer/farms', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })

        if (response.data.success) {
          userFarms.value = response.data.farms || []
          
          // Pre-fetch addresses for all farms
          for (const farm of userFarms.value) {
            if (farm.latitude && farm.longitude) {
              await formatFarmLocation(farm)
            }
          }
        }
      } catch (error) {
        console.error('Error loading user farms:', error)
      }
    }

    const onFarmSelect = async () => {
      if (selectedFarmId.value === 'new') {
        showFarmForm.value = true
        selectedFarm.value = null
        formattedFarmAddress.value = ''
        // Reset farmInfo when adding new farm
        farmInfo.value.latitude = ''
        farmInfo.value.longitude = ''
      } else if (selectedFarmId.value) {
        selectedFarm.value = userFarms.value.find(farm => farm.id == selectedFarmId.value)
        showFarmForm.value = false
        
        // Populate farmInfo with selected farm's coordinates for technician search
        if (selectedFarm.value) {
          farmInfo.value.latitude = selectedFarm.value.latitude
          farmInfo.value.longitude = selectedFarm.value.longitude
          formattedFarmAddress.value = await formatFarmLocation(selectedFarm.value)
          
          // Trigger nearby technicians search if we're in nearby mode
          if (technicianSelectionMethod.value === 'nearby') {
            loadNearbyTechnicians()
          }
        }
      } else {
        selectedFarm.value = null
        formattedFarmAddress.value = ''
        showFarmForm.value = false
        // Reset farmInfo when no farm selected
        farmInfo.value.latitude = ''
        farmInfo.value.longitude = ''
      }
    }

    const saveFarm = async () => {
      try {
        const token = localStorage.getItem('token')
        if (!token) {
          window.showError('Please login to save farm')
          return
        }

        const farmData = {
          name: farmInfo.value.name,
          father_name: farmInfo.value.father_name,
          latitude: farmInfo.value.latitude,
          longitude: farmInfo.value.longitude,
          number_of_cows: farmInfo.value.number_of_cows,
          number_of_calves: farmInfo.value.number_of_calves,
          number_of_bulls: farmInfo.value.number_of_bulls,
          number_of_goats: farmInfo.value.number_of_goats
        }

        const response = await window.axios.post('/farmer/farms', farmData, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })

        if (response.data.success) {
          // Add new farm to the list
          const newFarm = response.data.farm
          userFarms.value.push(newFarm)
          selectedFarmId.value = newFarm.id
          selectedFarm.value = newFarm
          showFarmForm.value = false
          
          // Populate farmInfo with new farm's coordinates for technician search
          farmInfo.value.latitude = newFarm.latitude
          farmInfo.value.longitude = newFarm.longitude
          
          // Trigger nearby technicians search if we're in nearby mode
          if (technicianSelectionMethod.value === 'nearby') {
            loadNearbyTechnicians()
          }
          
          // Reset form
          resetFarmForm()
          
          window.showSuccess('Farm saved successfully!')
        }
      } catch (error) {
        console.error('Error saving farm:', error)
        window.showError('Failed to save farm. Please try again.')
      }
    }

    const cancelFarmForm = () => {
      showFarmForm.value = false
      selectedFarmId.value = ''
      resetFarmForm()
    }

    const resetFarmForm = () => {
      farmInfo.value = {
        name: '',
        father_name: '',
        latitude: '',
        longitude: '',
        number_of_cows: 0,
        number_of_calves: 0,
        number_of_bulls: 0,
        number_of_goats: 0
      }
      farmLocation.value = { lat: null, lng: null }
    }

    const onLocationChange = (location) => {
      farmLocation.value = location
      farmInfo.value.latitude = location.lat
      farmInfo.value.longitude = location.lng
    }

    // Date/Time validation functions for Heat Start Time
    const getMinDateTime = () => {
      const now = new Date()
      const minTime = new Date(now.getTime() - (48 * 60 * 60 * 1000)) // 48 hours ago
      return minTime.toISOString().slice(0, 16) // Format: YYYY-MM-DDTHH:MM
    }

    const getMaxDateTime = () => {
      const now = new Date()
      return now.toISOString().slice(0, 16) // Format: YYYY-MM-DDTHH:MM
    }

    const validateHeatStartTime = (cowDetail) => {
      if (!cowDetail.heat_started_at) return

      const selectedTime = new Date(cowDetail.heat_started_at)
      const now = new Date()
      const minTime = new Date(now.getTime() - (48 * 60 * 60 * 1000)) // 48 hours ago

      if (selectedTime > now) {
        window.showError('Heat start time cannot be in the future. Please select a time within the last 48 hours.')
        cowDetail.heat_started_at = ''
        return
      }

      if (selectedTime < minTime) {
        window.showError('Heat start time cannot be older than 48 hours. Please select a time within the last 48 hours.')
        cowDetail.heat_started_at = ''
        return
      }
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
        userFarms.value = []
        selectedFarmId.value = ''
        selectedFarm.value = null
        
        // Show success message
        window.showSuccess('Logged out successfully!')
        
        // Redirect to home page
        router.push('/')
      }
    }

    const toggleUserDropdown = () => {
      showUserDropdown.value = !showUserDropdown.value
    }

    // Mobile menu functions
    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value
    }

    const scrollToSection = (sectionId, event) => {
      event.preventDefault()
      const element = document.getElementById(sectionId)
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' })
      }
    }

    const goToProducts = () => {
      router.push('/')
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

    // Helper function to get product image URL
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

    // Watch for quantity changes and update cowDetails accordingly
    watch(cartItems, (newCartItems) => {
      newCartItems.forEach((item, itemIndex) => {
        const currentCowDetailsLength = item.cowDetails?.length || 0
        const expectedCowDetailsLength = item.quantity
        
        if (currentCowDetailsLength !== expectedCowDetailsLength) {
          // Update cowDetails array to match quantity
          const newCowDetails = Array.from({ length: expectedCowDetailsLength }, (_, index) => {
            // Preserve existing cow details if available
            if (index < currentCowDetailsLength && item.cowDetails[index]) {
              return { ...item.cowDetails[index] }
            }
            // Create new empty cow detail
            return {
              cow_type: '',
              cow_age: '',
              heat_started_at: ''
            }
          })
          
          // Update the cart item with new cowDetails
          cartItems.value[itemIndex].cowDetails = newCowDetails
        }
      })
    }, { deep: true })

    const loadCowTypes = async () => {
      try {
        const response = await window.axios.get('/public/cow-types')
        if (response.data.success) {
          cowTypes.value = response.data.data
        }
      } catch (error) {
        console.error('Error loading cow types:', error)
        // Fallback to default cow types if API fails
        cowTypes.value = [
          { id: 1, name: 'Heifer', description: 'A young female cow that has not yet given birth' },
          { id: 2, name: 'Cow', description: 'A mature female cow that has given birth' }
        ]
      }
    }

    onMounted(async () => {
      console.log('🚀 Checkout page mounted')
      loadCart()
      await loadCowTypes()
      await checkAuthStatus()
      if (isAuthenticated.value) {
        loadUserFarms()
        // Populate customer info after auth check
        populateCustomerInfo()
      }
      
      // Redirect if cart is empty
      if (cartItems.value.length === 0) {
        router.push('/')
      }

      // Add visibility change listener to check auth when user returns to page
      const handleVisibilityChange = () => {
        if (!document.hidden) {
          console.log('👁️ Page became visible, checking auth status...')
          checkAuthStatus()
        }
      }

      document.addEventListener('visibilitychange', handleVisibilityChange)

      // Cleanup listener on unmount
      onUnmounted(() => {
        document.removeEventListener('visibilitychange', handleVisibilityChange)
      })
    })

    // Watch for route changes to check auth status
    watch(() => router.currentRoute.value.path, (newPath) => {
      if (newPath === '/checkout') {
        console.log('🔄 Route changed to checkout, checking auth status...')
        checkAuthStatus()
      }
    })

    // Watch for authentication status changes to populate customer info
    watch(() => authStatus.value.isAuthenticated, (isAuth) => {
      if (isAuth) {
        console.log('🔐 User authenticated, populating customer info...')
        populateCustomerInfo()
      } else {
        // Clear customer info when user logs out
        customerInfo.value = {
          name: '',
          mobile: '',
          email: '',
          date_of_birth: ''
        }
      }
    })


    return {
      cartItems,
      isPlacingOrder,
      visitFee,
      customerInfo,
      farmInfo,
      orderNotes,
      technicianSelectionMethod,
      selectedTechnician,
      nearbyTechnicians,
      manualTechnicians,
      loadingNearbyTechnicians,
      loadingManualTechnicians,
      hasSearched,
      searchFilters,
      pakistanData,
      getProvinces,
      getDivisionsForProvince,
      getDistrictsForDivision,
      onProvinceChange,
      onDivisionChange,
      onDistrictChange,
      subtotal,
      total,
      loadGoogleMapsAPI,
      waitForGoogleMaps,
      formatFarmLocation,
      canPlaceOrder,
      loadNearbyTechnicians,
      loadTechnicians,
      getAvailabilityBadgeClass,
      placeOrder,
      goToLogin,
      goToRegister,
      showLoginModal,
      showRegisterModal,
      handleLoginSuccess,
      handleRegisterSuccess,
      switchToRegister,
      switchToLogin,
      closeModals,
      userFarms,
      farmAddresses,
      selectedFarmId,
      selectedFarm,
      formattedFarmAddress,
      showFarmForm,
      onFarmSelect,
      saveFarm,
      cancelFarmForm,
      farmLocation,
      onLocationChange,
      getMinDateTime,
      getMaxDateTime,
      validateHeatStartTime,
      userInfo,
      goToDashboard,
      logout,
      authStatus,
      showUserDropdown,
      toggleUserDropdown,
      showMobileMenu,
      toggleMobileMenu,
      scrollToSection,
      goToProducts,
      navigateToHome,
      navigateToProducts,
      navigateToAbout,
      navigateToContact,
      getProductImageUrl,
      cowTypes,
      loadCowTypes
    }
  }
}
</script>
