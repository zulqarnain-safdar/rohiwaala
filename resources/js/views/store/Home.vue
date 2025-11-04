<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-400/20 to-emerald-600/20 rounded-full blur-3xl animate-float"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-blue-400/20 to-cyan-600/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-green-300/10 to-emerald-400/10 rounded-full blur-3xl animate-pulse"></div>
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
              <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">RohiWala.pk</h1>
              <p class="text-sm font-semibold text-green-600 tracking-wide">Semen Distribution System</p>
            </div>
          </div>

          <!-- Navigation Links - Desktop -->
          <div class="hidden lg:flex items-center space-x-8">
            <a href="#home" @click="scrollToSection('home', $event)" class="relative group text-gray-700 hover:text-green-600 font-semibold transition-all duration-300 px-4 py-2 rounded-xl hover:bg-green-50/80 backdrop-blur-sm">
              <span class="relative z-10 flex items-center space-x-2">
                <span>Home</span>
              </span>
              <div class="absolute -bottom-1 left-4 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-600 group-hover:w-[calc(100%-2rem)] transition-all duration-300"></div>
            </a>
            <a href="#products" @click="scrollToSection('products', $event)" class="relative group text-gray-700 hover:text-green-600 font-semibold transition-all duration-300 px-4 py-2 rounded-xl hover:bg-green-50/80 backdrop-blur-sm">
              <span class="relative z-10 flex items-center space-x-2">
               
                <span>Products</span>
              </span>
              <div class="absolute -bottom-1 left-4 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-600 group-hover:w-[calc(100%-2rem)] transition-all duration-300"></div>
            </a>
            <a href="#about" @click="scrollToSection('about', $event)" class="relative group text-gray-700 hover:text-green-600 font-semibold transition-all duration-300 px-4 py-2 rounded-xl hover:bg-green-50/80 backdrop-blur-sm">
              <span class="relative z-10 flex items-center space-x-2">
                
                <span>About</span>
              </span>
              <div class="absolute -bottom-1 left-4 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-600 group-hover:w-[calc(100%-2rem)] transition-all duration-300"></div>
            </a>
            <a href="#contact" @click="scrollToSection('contact', $event)" class="relative group text-gray-700 hover:text-green-600 font-semibold transition-all duration-300 px-4 py-2 rounded-xl hover:bg-green-50/80 backdrop-blur-sm">
              <span class="relative z-10 flex items-center space-x-2">
                
                <span>Contact</span>
              </span>
              <div class="absolute -bottom-1 left-4 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-600 group-hover:w-[calc(100%-2rem)] transition-all duration-300"></div>
            </a>
          </div>

          <!-- Mobile Menu Button -->
          <div class="lg:hidden">
            <button @click="toggleMobileMenu" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg v-if="!showMobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- CTA Buttons -->
          <div class="hidden lg:flex items-center space-x-4">
            <!-- Show different options based on authentication status -->
            <template v-if="isAuthenticated">
              <!-- User is logged in -->
              <div class="relative">
                <!-- User Avatar and Name - Clickable -->
                <button @click="toggleUserDropdown" class="flex items-center space-x-3 bg-white/95 backdrop-blur-sm rounded-2xl px-4 py-2.5 border border-green-200/60 shadow-lg hover:shadow-xl transition-all duration-300 group">
                  <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-md ring-2 ring-green-100">
                    <span class="text-white font-bold text-sm">{{ userInfo?.name?.charAt(0) || 'U' }}</span>
                  </div>
                  <div class="hidden sm:block">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Welcome back</p>
                    <p class="text-sm font-bold text-gray-800">{{ userInfo?.name || 'User' }}</p>
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

              <!-- Cart Button (after dropdown) -->
              <button @click="goToCart" class="relative group bg-white/90 backdrop-blur-sm border-2 border-green-200 p-4 rounded-2xl hover:bg-green-50 hover:border-green-300 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <span v-if="cartItems.length > 0" class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold shadow-lg animate-pulse">
                  {{ cartItems.length }}
                </span>
              </button>
            </template>
            <template v-else>
              <!-- User is not logged in -->
              <div class="flex items-center space-x-4">
                <button @click="goToLogin" class="group flex items-center space-x-2 text-gray-700 hover:text-green-600 font-semibold px-5 py-3 rounded-xl transition-all duration-300 hover:bg-green-50/80 backdrop-blur-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                  </svg>
                  <span class="hidden sm:inline">Login</span>
                </button>
                
                <button @click="goToRegister" class="group relative overflow-hidden bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white px-8 py-3 rounded-2xl font-bold transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-105">
                  <div class="absolute inset-0 bg-gradient-to-r from-green-600 via-green-700 to-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <div class="relative flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Get Started</span>
                  </div>
                </button>

                <!-- Cart Button (after auth buttons) -->
                <button @click="goToCart" class="relative group bg-white/90 backdrop-blur-sm border-2 border-green-200 p-4 rounded-2xl hover:bg-green-50 hover:border-green-300 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                  <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                  </svg>
                  <span v-if="cartItems.length > 0" class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold shadow-lg animate-pulse">
                    {{ cartItems.length }}
                  </span>
                </button>
              </div>
            </template>
          </div>

          <!-- Mobile CTA Buttons -->
          <div class="lg:hidden flex items-center space-x-2">
            <!-- Cart Button -->
            <button @click="goToCart" class="relative p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
              </svg>
              <span v-if="cartItems.length > 0" class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg animate-pulse">
                {{ cartItems.length }}
              </span>
            </button>
            
            <!-- User Menu Button (Mobile) -->
            <template v-if="isAuthenticated">
              <button @click="toggleUserDropdown" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                  <span class="text-white font-bold text-sm">{{ userInfo?.name?.charAt(0) || 'U' }}</span>
                </div>
              </button>
            </template>
            <template v-else>
              <button @click="goToLogin" class="p-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
              </button>
            </template>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Dropdown -->
      <div v-if="showMobileMenu" class="lg:hidden bg-white/95 backdrop-blur-xl border-t border-white/30 shadow-lg">
        <div class="px-4 py-4 space-y-2">
          <a href="#home" @click="scrollToSection('home', $event); showMobileMenu = false" class="block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            Home
          </a>
          <a href="#products" @click="scrollToSection('products', $event); showMobileMenu = false" class="block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            Products
          </a>
          <a href="#about" @click="scrollToSection('about', $event); showMobileMenu = false" class="block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            About
          </a>
          <a href="#contact" @click="scrollToSection('contact', $event); showMobileMenu = false" class="block px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl font-semibold transition-all duration-300">
            Contact
          </a>
          
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

    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden py-20 lg:py-28">
      <div class="absolute inset-0 bg-gradient-to-br from-green-700/5 via-emerald-500/10 to-teal-600/5"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <!-- Left: Copy -->
          <div class="lg:col-span-7">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/70 backdrop-blur border border-white/60 shadow-sm mb-6 animate-fade-in-up">
              <span class="h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
              <span class="text-sm font-semibold text-gray-700">Trusted by 1,000+ farmers nationwide</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl xl:text-6xl font-extrabold tracking-tight text-gray-900 animate-fade-in-up" style="animation-delay: .05s;">
              Elevate Your Herd with
              <span class="gradient-text">Premium Genetics</span>
            </h1>
            <p class="mt-6 text-base sm:text-lg md:text-xl text-gray-600 leading-relaxed max-w-2xl animate-fade-in-up" style="animation-delay: .1s;">
              Access rigorously screened bull semen from top breeds. Consistent quality,
              transparent provenance, and expert support—built for serious cattle farmers.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4 animate-fade-in-up" style="animation-delay: .15s;">
              <button @click="goToProducts" class="group inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-600 shadow-xl hover:shadow-2xl transition-all text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Browse Products
              </button>
              <button @click="scrollToSection('about', $event)" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 rounded-xl border-2 border-emerald-600 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all text-sm sm:text-base">
                Learn More
              </button>
            </div>

            <!-- Compact stats -->
            <div class="mt-10 grid grid-cols-3 gap-4 sm:gap-6 max-w-md animate-fade-in-up" style="animation-delay: .2s;">
              <div>
                <div class="text-xl sm:text-2xl font-bold text-gray-900">12+</div>
                <div class="text-xs sm:text-sm text-gray-500">Premium breeds</div>
              </div>
              <div>
                <div class="text-xl sm:text-2xl font-bold text-gray-900">1,000+</div>
                <div class="text-xs sm:text-sm text-gray-500">Happy farmers</div>
              </div>
              <div>
                <div class="text-xl sm:text-2xl font-bold text-gray-900">99%</div>
                <div class="text-xs sm:text-sm text-gray-500">Success rate</div>
              </div>
            </div>
          </div>

          <!-- Right: Visual -->
          <div class="lg:col-span-5">
            <div class="relative">
              <div class="absolute -inset-6 bg-gradient-to-tr from-emerald-200/40 to-teal-200/30 rounded-3xl blur-xl"></div>
              <div class="relative bg-white/80 backdrop-blur rounded-3xl border border-white/60 shadow-2xl p-6 animate-scale-in">
                <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center">
                  <svg class="w-24 h-24 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="mt-4 flex items-center justify-between">
                  <div>
                    <div class="text-sm text-gray-500">Featured Collection</div>
                    <div class="font-semibold text-gray-900">Top Genetics 2024</div>
                  </div>
                  <button @click="goToProducts" class="store-button px-4 py-2">Explore</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Trust strip -->
        <div class="mt-14">
          <div class="bg-white/70 backdrop-blur border border-white/60 rounded-2xl p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 items-center text-gray-500">
              <div class="flex items-center space-x-3">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs sm:text-sm">Cold-chain maintained</span>
              </div>
              <div class="flex items-center space-x-3">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs sm:text-sm">Lab-certified batches</span>
              </div>
              <div class="flex items-center space-x-3">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs sm:text-sm">Nationwide technicians</span>
              </div>
              <div class="flex items-center space-x-3">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs sm:text-sm">Dedicated support</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-20 bg-white/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Our Products</h2>
          <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">
            Discover our premium collection of bull semen from top breeds, carefully selected for superior genetics and performance.
          </p>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div>
          <p class="mt-4 text-gray-600">Loading products...</p>
        </div>

        <!-- Product Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">
          <div v-for="(product, index) in products" :key="product.id" 
               class="group store-card animate-fade-in-up h-full flex flex-col"
               :style="`animation-delay: ${index * 0.1}s;`">
            <!-- Product Image -->
            <div class="aspect-square bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center relative overflow-hidden">
              <!-- Dynamic Product Image -->
              <img 
                v-if="product.image" 
                :src="getProductImageUrl(product.image)" 
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <!-- Fallback SVG Icon -->
              <svg 
                v-else
                class="w-24 h-24 text-green-600 group-hover:scale-110 transition-transform duration-300" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              <!-- Stock Badge -->
              <div class="absolute top-4 right-4">
                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                  In Stock
                </span>
              </div>
            </div>

            <!-- Product Info -->
            <div class="p-6 flex flex-col flex-1">
              <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ product.name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ product.breed?.name }}</p>
                <p class="text-sm text-gray-500 line-clamp-2">{{ product.description }}</p>
              </div>

              <div class="flex items-center justify-between mb-4">
                <div class="text-2xl font-bold text-green-600">₨{{ product.price }}</div>
                <div class="text-sm text-gray-500">per straw</div>
              </div>

              <button @click="addToCart(product)" 
                      class="w-full store-button py-3 group-hover:shadow-lg mt-auto">
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col items-center mt-12 space-y-4 relative z-[9999] bg-white/80 backdrop-blur-sm rounded-lg p-4">
          <!-- Debug Info -->
          <div class="text-sm text-gray-500">
            Showing page {{ currentPage }} of {{ totalPages }} ({{ products.length }} products)
          </div>
          
         
          
          <div class="flex space-x-2 relative z-[10000]">
            <button
              @click="() => { console.log('Previous clicked'); goToPage(currentPage - 1); }"
              :disabled="currentPage <= 1"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors relative z-[10000] cursor-pointer"
            >
              Previous
            </button>
            
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="() => { console.log('Page', page, 'clicked'); goToPage(page); }"
              :class="[
                'px-4 py-2 rounded-lg font-semibold transition-colors relative z-[10000] cursor-pointer',
                page === currentPage
                  ? 'bg-green-600 text-white'
                  : 'border border-gray-300 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
            
            <button
              @click="() => { console.log('Next clicked'); goToPage(currentPage + 1); }"
              :disabled="currentPage >= totalPages"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors relative z-[10000] cursor-pointer"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-r from-green-50 to-emerald-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
          <!-- About Content -->
          <div class="space-y-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Why Choose RohiWala.pk?</h2>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
              We provide comprehensive artificial insemination services with expert technicians, premium quality semen, and professional support throughout the entire process.
            </p>

            <div class="space-y-4">
              <div class="flex items-start space-x-3 sm:space-x-4">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Premium Quality Semen</h3>
                  <p class="text-gray-600 text-sm sm:text-base">Carefully selected from top-performing bulls with superior genetics</p>
                </div>
              </div>

              <div class="flex items-start space-x-3 sm:space-x-4">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Expert Technicians</h3>
                  <p class="text-gray-600 text-sm sm:text-base">Professional AI technicians with years of experience and training</p>
                </div>
              </div>

              <div class="flex items-start space-x-3 sm:space-x-4">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Nationwide Coverage</h3>
                  <p class="text-gray-600 text-sm sm:text-base">Services available across Pakistan with local technician network</p>
                </div>
              </div>
            </div>
          </div>

          <!-- About Image -->
          <div class="relative">
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-8">
              <div class="aspect-video bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center">
                <svg class="w-24 h-24 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 bg-gradient-to-r from-green-600 to-emerald-600">
      <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">Ready to Improve Your Cattle Genetics?</h2>
        <p class="text-lg sm:text-xl text-green-100 mb-8">
          Join hundreds of farmers who trust RohiWala.pk for their artificial insemination needs.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button @click="goToRegister" 
                  class="bg-white text-green-600 px-6 sm:px-8 py-3 sm:py-4 rounded-xl hover:bg-gray-100 transition-all duration-200 font-semibold text-base sm:text-lg shadow-lg">
            Get Started Today
          </button>
          <button @click="goToProducts" 
                  class="border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl hover:bg-white hover:text-green-600 transition-all duration-200 font-semibold text-base sm:text-lg">
            Browse Products
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
          <div>
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-8 h-8 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <span class="text-xl font-bold">RohiWala.pk</span>
            </div>
            <p class="text-gray-400 text-sm">
              Premium artificial insemination services for cattle farmers across Pakistan.
            </p>
          </div>

          <div>
            <h3 class="font-semibold mb-4">Products</h3>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a @click="scrollToSection('products', $event)" href="#products" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">Bull Semen</a></li>
              <li><a @click="goToProducts" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">All Products</a></li>
              <li><a @click="scrollToSection('about', $event)" href="#about" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">Our Services</a></li>
            </ul>
          </div>

          <div>
            <h3 class="font-semibold mb-4">Support</h3>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="mailto:info@rohiwala.pk" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">Help Center</a></li>
              <li><a @click="scrollToSection('contact', $event)" href="#contact" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">Contact Us</a></li>
              <li><a href="tel:+923001234567" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">Call Support</a></li>
            </ul>
          </div>

          <div>
            <h3 class="font-semibold mb-4">Contact</h3>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="tel:+923001234567" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">+92 300 1234567</a></li>
              <li><a href="mailto:info@rohiwala.pk" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">info@rohiwala.pk</a></li>
              <li><a href="https://maps.google.com/?q=Lahore,Pakistan" target="_blank" class="hover:text-white transition-colors cursor-pointer" style="pointer-events: auto;">Lahore, Pakistan</a></li>
            </ul>
          </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8">
          <!-- Social Media Links -->
          <div class="flex justify-center space-x-6 mb-6">
            <a href="https://facebook.com/rohiwala" target="_blank" class="text-gray-400 hover:text-white transition-colors" style="pointer-events: auto;">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a href="https://twitter.com/rohiwala" target="_blank" class="text-gray-400 hover:text-white transition-colors" style="pointer-events: auto;">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
              </svg>
            </a>
            <a href="https://instagram.com/rohiwala" target="_blank" class="text-gray-400 hover:text-white transition-colors" style="pointer-events: auto;">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.281h-1.598v1.598h1.598V7.707zm-1.598 10.674H8.449c-2.326 0-4.21-1.884-4.21-4.21s1.884-4.21 4.21-4.21h5.232c2.326 0 4.21 1.884 4.21 4.21s-1.884 4.21-4.21 4.21z"/>
              </svg>
            </a>
            <a href="https://linkedin.com/company/rohiwala" target="_blank" class="text-gray-400 hover:text-white transition-colors" style="pointer-events: auto;">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
            </a>
          </div>
          
          <!-- Copyright -->
          <div class="text-center text-sm text-gray-400">
            <p>&copy; 2024 RohiWala.pk. All rights reserved.</p>
            <p class="mt-2">
              <a href="/terms" class="hover:text-white transition-colors mr-4" style="pointer-events: auto;">Terms of Service</a>
              <a href="/privacy" class="hover:text-white transition-colors" style="pointer-events: auto;">Privacy Policy</a>
            </p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Auth Modals -->
    <LoginModal 
      :show="showLoginModal" 
      @close="closeModals"
      @login-success="handleLoginSuccess"
      @switch-to-register="switchToRegister"
    />

    <RegisterModal 
      :show="showRegisterModal" 
      @close="closeModals"
      @register-success="handleRegisterSuccess"
      @switch-to-login="switchToLogin"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import LoginModal from '@/components/LoginModal.vue'
import RegisterModal from '@/components/RegisterModal.vue'

export default {
  name: 'StoreHome',
  components: {
    LoginModal,
    RegisterModal
  },
  setup() {
    const router = useRouter()
    const products = ref([])
    const cartItems = ref([])
    const isAuthenticated = ref(false)
    const userInfo = ref(null)
    const showUserDropdown = ref(false)
    const showMobileMenu = ref(false)
    const showLoginModal = ref(false)
    const showRegisterModal = ref(false)
    const redirectAfterLogin = ref('/cart')
    const currentPage = ref(1)
    const perPage = ref(6)
    const totalPages = ref(1)
    const loading = ref(false)

    const loadProducts = async () => {
      loading.value = true
      try {
        console.log('Loading products for page:', currentPage.value)
        const params = new URLSearchParams()
        params.append('active_only', 'true')
        params.append('per_page', perPage.value.toString())
        params.append('page', currentPage.value.toString())
        
        const url = `/public/products/semen?${params}`
        console.log('API URL:', url)
        
        const response = await window.axios.get(url)
        console.log('Products response:', response.data)
        
        if (response.data.success && response.data.semen) {
          products.value = response.data.semen.data || []
          totalPages.value = response.data.semen.last_page || 1
          console.log('Products loaded:', products.value.length, 'Total pages:', totalPages.value)
          console.log('Pagination info:', {
            current_page: response.data.semen.current_page,
            last_page: response.data.semen.last_page,
            per_page: response.data.semen.per_page,
            total: response.data.semen.total
          })
        } else {
          console.error('Invalid API response structure:', response.data)
          products.value = []
        }
      } catch (error) {
        console.error('Error loading products:', error)
        console.error('Error details:', error.response?.data)
        products.value = []
      } finally {
        loading.value = false
      }
    }

    const goToLogin = () => {
      router.push('/login')
    }

    const goToRegister = () => {
      router.push('/register')
    }

    const goToProducts = () => {
      console.log('goToProducts clicked - scrolling to products section')
      scrollToSection('products')
    }

    const goToPage = (page) => {
      console.log('Attempting to go to page:', page)
      console.log('Current page:', currentPage.value)
      console.log('Total pages:', totalPages.value)
      
      if (page >= 1 && page <= totalPages.value && page !== currentPage.value) {
        currentPage.value = page
        console.log('Page changed to:', currentPage.value)
        loadProducts()
        // Scroll to products section after loading
        setTimeout(() => {
          scrollToSection('products')
        }, 100)
      } else {
        console.log('Page change not allowed:', page)
      }
    }

    const testPage2 = () => {
      console.log('Test: Going to page 2')
      currentPage.value = 2
      loadProducts()
    }

    const testPage1 = () => {
      console.log('Test: Going to page 1')
      currentPage.value = 1
      loadProducts()
    }

    const visiblePages = computed(() => {
      const pages = []
      const start = Math.max(1, currentPage.value - 2)
      const end = Math.min(totalPages.value, currentPage.value + 2)
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      return pages
    })

    const scrollToSection = (sectionId, event) => {
      console.log('scrollToSection called with:', sectionId)
      if (event) {
        event.preventDefault()
        console.log('Event prevented')
      }
      const element = document.getElementById(sectionId)
      if (element) {
        console.log('Element found, scrolling to:', sectionId)
        element.scrollIntoView({ behavior: 'smooth', block: 'start' })
      } else {
        console.error('Element not found:', sectionId)
      }
    }

    const addToCart = (product) => {
      // Add to localStorage cart
      const cart = JSON.parse(localStorage.getItem('store_cart') || '[]')
      
      // Check if item already exists in cart
      const existingItem = cart.find(item => item.semen.id === product.id)
      
      if (existingItem) {
        existingItem.quantity += 1
        // Initialize cowDetails for new quantity
        existingItem.cowDetails.push({
          cow_type: '',
          cow_age: '',
          heat_started_at: ''
        })
      } else {
        cart.push({
          semen: product,
          quantity: 1,
          cowDetails: [{
            cow_type: '',
            cow_age: '',
            heat_started_at: ''
          }]
        })
      }
      
      localStorage.setItem('store_cart', JSON.stringify(cart))
      // Update reactive cart items
      cartItems.value = [...cart]
      
      // Show success message
      window.showSuccess(`${product.name} added to cart!`)
    }

    const loadCart = () => {
      const savedCart = localStorage.getItem('store_cart')
      if (savedCart) {
        try {
          cartItems.value = JSON.parse(savedCart)
        } catch (e) {
          cartItems.value = []
        }
      } else {
        cartItems.value = []
      }
    }

    const goToCart = async () => {
      await checkAuthStatus()
      if (isAuthenticated.value) {
        router.push('/cart')
      } else {
        redirectAfterLogin.value = '/cart'
        showLoginModal.value = true
      }
    }

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
        } catch (error) {
          console.error('Error parsing user data:', error)
          userInfo.value = null
          isAuthenticated.value = false
        }
      }

      // Then verify the token by trying to access a protected route
      try {
        // Use the farmer/farms endpoint to verify authentication
        const response = await window.axios.get('/farmer/farms', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
        
        if (response.data.success !== false) {
          // Token is valid, user is authenticated
          isAuthenticated.value = true
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
              }
            } catch (meError) {
              console.error('Could not get user info:', meError)
            }
          }
        } else {
          isAuthenticated.value = false
          userInfo.value = null
          localStorage.removeItem('token')
          localStorage.removeItem('user')
        }
      } catch (error) {
        console.error('Auth check failed:', error)
        isAuthenticated.value = false
        userInfo.value = null
        localStorage.removeItem('token')
        localStorage.removeItem('user')
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

    const handleLoginSuccess = (user) => {
      isAuthenticated.value = true
      userInfo.value = user
      showLoginModal.value = false
      router.push(redirectAfterLogin.value)
    }

    const handleRegisterSuccess = (user) => {
      isAuthenticated.value = true
      userInfo.value = user
      showRegisterModal.value = false
      router.push(redirectAfterLogin.value)
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

    const toggleUserDropdown = () => {
      showUserDropdown.value = !showUserDropdown.value
    }

    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value
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

    onMounted(async () => {
      loadProducts()
      loadCart()
      await checkAuthStatus()
      window.addEventListener('storage', loadCart)
    })

    return {
      products,
      cartItems,
      currentPage,
      totalPages,
      loading,
      visiblePages,
      goToLogin,
      goToRegister,
      goToProducts,
      goToPage,
      testPage2,
      testPage1,
      scrollToSection,
      addToCart,
      goToCart,
      isAuthenticated,
      userInfo,
      goToDashboard,
      logout,
      showUserDropdown,
      toggleUserDropdown,
      showMobileMenu,
      toggleMobileMenu,
      // Auth modals
      showLoginModal,
      showRegisterModal,
      redirectAfterLogin,
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

/* Ensure footer links are clickable */
footer a {
  position: relative;
  z-index: 10;
  pointer-events: auto;
  display: inline-block;
}

footer a:hover {
  text-decoration: none;
}

/* Ensure no overlay is blocking clicks */
footer {
  position: relative;
  z-index: 1;
}

/* Make sure links have proper cursor */
footer a[href] {
  cursor: pointer;
}
</style>
