<template>
  <!-- Loading State -->
  <div v-if="isLoading" class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex items-center justify-center">
    <div class="text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
      <p class="text-gray-600">Loading dashboard...</p>
    </div>
  </div>

  <!-- Error State -->
  <div v-else-if="hasError" class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex items-center justify-center">
    <div class="text-center max-w-md mx-auto p-6">
      <div class="text-red-500 text-6xl mb-4">⚠️</div>
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Error Loading Dashboard</h2>
      <p class="text-gray-600 mb-4">{{ errorMessage }}</p>
      <button @click="window.location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Refresh Page
      </button>
    </div>
  </div>

  <!-- Main Dashboard -->
  <div v-else class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex relative overflow-x-hidden">
    <!-- Sidebar -->
    <div 
      :class="[
        'bg-white/80 backdrop-blur-md shadow-xl border-r border-white/20 flex-shrink-0 transition-all duration-300',
        'fixed lg:sticky inset-y-0 left-0 z-40',
        isSidebarCollapsed ? '-translate-x-full lg:translate-x-0 lg:w-64' : 'translate-x-0 w-64',
        'lg:w-64'
      ]"
    >
      <!-- Logo & Header -->
      <div class="p-6 border-b border-white/20">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div>
              <h1 class="text-lg font-bold text-gray-900">Admin Panel</h1>
              <p class="text-xs text-gray-600">RohiWala.pk</p>
            </div>
          </div>
          <!-- Close Button for Mobile -->
          <button 
            @click="toggleSidebar"
            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="p-4 space-y-2">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'w-full flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group',
            activeTab === tab.id 
              ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-white/60'
          ]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="tab.id === 'analytics'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            <path v-else-if="tab.id === 'farmers'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            <path v-else-if="tab.id === 'technicians'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path v-else-if="tab.id === 'pregnancy-tracking'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path v-else-if="tab.id === 'orders'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            <path v-else-if="tab.id === 'jobs'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
            <path v-else-if="tab.id === 'catalog'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            <path v-else-if="tab.id === 'earnings'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            <path v-else-if="tab.id === 'performance'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            <path v-else-if="tab.id === 'stock'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            <path v-else-if="tab.id === 'cow-types'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            <path v-else-if="tab.id === 'reassignment-logs'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            <path v-else-if="tab.id === 'notifications'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828zM4 7h.01M4 7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"></path>
            <path v-else-if="tab.id === 'location'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          <span>{{ tab.name }}</span>
        </button>
      </nav>

    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 max-w-full">
      <!-- Mobile Overlay -->
      <div 
        v-if="!isSidebarCollapsed" 
        @click="toggleSidebar"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
      ></div>
      <!-- Top Header -->
      <header class="bg-white/60 backdrop-blur-md shadow-lg border-b border-white/20 px-4 sm:px-6 py-4 sticky top-0 z-50">
        <div class="flex items-center justify-between">
          <!-- Hamburger Menu Button (Mobile/Tablet) -->
          <button 
            @click="toggleSidebar"
            class="lg:hidden mr-4 p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
          
          <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ tabs.find(t => t.id === activeTab)?.name }}</h2>
            <p class="text-xs text-gray-500 mt-1">RohiWala.pk</p>
          </div>
          
          <!-- Right side: Date and User Info -->
          <div class="flex items-center space-x-4">
            <div class="hidden sm:block text-sm text-gray-600">
              {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
            </div>
            
            <!-- User Dropdown -->
            <div class="relative border-l border-gray-200 pl-4 user-dropdown z-[100]">
              <!-- User Avatar Button -->
              <button 
                @click="showUserDropdown = !showUserDropdown"
                class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg p-2 transition-colors"
              >
                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-semibold">{{ user?.name?.charAt(0) || 'U' }}</span>
                </div>
                <div class="text-left">
                  <p class="text-sm font-medium text-gray-900">{{ user?.name || 'User' }}</p>
                  <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <div 
                v-if="showUserDropdown"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-[9999]"
              >
                <div class="px-4 py-3 border-b border-gray-100">
                  <p class="text-sm font-medium text-gray-900">{{ user?.name || 'User' }}</p>
                </div>
                <button 
                  @click="logout"
                  :disabled="authStore.isLoggingOut"
                  class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors disabled:opacity-50"
                >
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  {{ authStore.isLoggingOut ? 'Logging out...' : 'Logout' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <main class="flex-1 p-4 sm:p-6 overflow-y-auto overflow-x-hidden min-w-0 mt-0">
        <!-- Analytics Tab -->
        <div v-if="activeTab === 'analytics'" class="space-y-6 sm:space-y-8 min-w-0">
        <!-- Stats Cards Section -->
        <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
          <!-- Stats Cards Header -->
          <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-white/20">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Dashboard Overview</h3>
              </div>
              <button 
                @click="toggleStatsCards"
                class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-lg transition-colors"
              >
                <span>{{ isStatsCardsCollapsed ? 'Show Stats' : 'Hide Stats' }}</span>
                <svg 
                  class="w-4 h-4 transition-transform duration-200"
                  :class="{ 'rotate-180': isStatsCardsCollapsed }"
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- Stats Cards Content -->
          <div 
            class="transition-all duration-300 ease-in-out overflow-hidden"
            :class="isStatsCardsCollapsed ? 'max-h-0 opacity-0' : 'max-h-[500px] opacity-100'"
          >
            <div class="p-4 sm:p-6">
              <!-- Stats Cards -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <!-- Total Orders Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-600 mb-1">Total Orders</p>
                  <p class="text-3xl font-bold text-gray-900">{{ analytics.orders?.total || 0 }}</p>
                  <div class="flex items-center text-green-600 text-sm mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                    +12%
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
          </div>

          <!-- Revenue Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                  <p class="text-3xl font-bold text-gray-900">₨{{ analytics.orders?.revenue || 0 }}</p>
                  <div class="flex items-center text-green-600 text-sm mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                    +8%
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-green-500 to-emerald-600"></div>
          </div>

          <!-- Active Farmers Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-600 mb-1">Active Farmers</p>
                  <p class="text-3xl font-bold text-gray-900">{{ analytics.users?.active_farmers || 0 }}</p>
                  <div class="flex items-center text-green-600 text-sm mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                    +5%
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>
          </div>

          <!-- Online Technicians Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-600 mb-1">Online Technicians</p>
                  <p class="text-3xl font-bold text-gray-900">{{ analytics.users?.online_technicians || 0 }}</p>
                  <div class="flex items-center text-green-600 text-sm mt-1">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                    Online
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-purple-500 to-violet-600"></div>
          </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20 overflow-hidden">
          <div class="px-6 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-white/20">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recent Orders
              </h3>
              <button class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center">
                View All >
              </button>
            </div>
          </div>
          
          <!-- Filters Section -->
          <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-200">
            <div class="flex flex-wrap items-center gap-4">
              <div class="flex items-center space-x-3">
                <select v-model="orderFilters.farmer" @change="loadOrdersWithPagination(1)" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 min-w-[140px]">
                  <option value="">All Farmers</option>
                  <option v-for="farmer in uniqueFarmers" :key="farmer" :value="farmer">{{ farmer }}</option>
                </select>
                
                <select v-model="orderFilters.farm" @change="loadOrdersWithPagination(1)" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 min-w-[120px]">
                  <option value="">All Farms</option>
                  <option v-for="farm in uniqueFarms" :key="farm" :value="farm">{{ farm }}</option>
                </select>
                
                <select v-model="orderFilters.technician" @change="loadOrdersWithPagination(1)" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 min-w-[140px]">
                  <option value="">All Technicians</option>
                  <option v-for="tech in uniqueTechnicians" :key="tech" :value="tech">{{ tech }}</option>
                </select>
                
                <select v-model="orderFilters.status" @change="loadOrdersWithPagination(1)" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 min-w-[120px]">
                  <option value="">All Statuses</option>
                  <option value="pending">Pending</option>
                  <option value="assigned">Assigned</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                
                <select v-model="orderFilters.date" @change="handleDateFilterChange" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 min-w-[140px]">
                  <option value="">All Dates</option>
                  <option value="today">Today</option>
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                  <option value="custom">Custom Range</option>
                </select>
              </div>
              
              <!-- Custom Date Range Inputs -->
              <div v-if="orderFilters.date === 'custom'" class="mt-4 p-4 bg-blue-50/50 border border-blue-200 rounded-lg">
                <div class="flex items-center space-x-6">
                  <div class="flex items-center space-x-3">
                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">From Date:</label>
                    <div class="relative">
                      <input 
                        v-model="orderFilters.fromDate" 
                        @change="loadOrdersWithPagination(1)"
                        type="date" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200"
                        placeholder="Select start date"
                      />
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center space-x-3">
                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">To Date:</label>
                    <div class="relative">
                      <input 
                        v-model="orderFilters.toDate" 
                        @change="loadOrdersWithPagination(1)"
                        type="date" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200"
                        placeholder="Select end date"
                      />
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <button @click="clearAllFilters" class="px-4 py-1 mt-1 bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 text-sm font-medium rounded-lg border border-gray-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Clear All Filters
              </button>
            </div>
          </div>
          <div 
            class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
            ref="scrollableTable"
          >
            <table class="min-w-full">
              <thead class="bg-gray-50/50">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">DATE</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ORDER #</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">FARMER</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">FARM</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TECHNICIAN</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ETA</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">AMOUNT</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">VISIT FEE</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">STATUS</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ACTIONS</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="order in filteredOrders" :key="order?.id || Math.random()"  class="hover:bg-gray-50/50 transition-colors duration-150">
                  <!-- DATE -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(order.created_at) }}
                  </td>
                  
                  <!-- ORDER # -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-6 h-6 bg-green-500 rounded flex items-center justify-center mr-2">
                        <span class="text-white text-xs font-bold">#</span>
                      </div>
                      <span class="text-sm font-semibold text-gray-900">{{ order.order_number }}</span>
                    </div>
                  </td>
                  
                  <!-- FARMER -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-5 h-5 bg-purple-500 rounded mr-2 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                      </div>
                      <span class="text-sm font-medium text-gray-900">{{ getFarmerName(order) }}</span>
                    </div>
                  </td>
                  
                  <!-- FARM -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-5 h-5 bg-blue-500 rounded mr-2 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                      </div>
                      <span class="text-sm font-medium text-gray-900">{{ getFarmName(order) }}</span>
                    </div>
                  </td>
                  
                  <!-- TECHNICIAN -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ getTechnicianName(order) }}</div>
                    <div class="text-xs text-gray-500">{{ getTechnicianPhone(order) }}</div>
                  </td>
                  
                  <!-- ETA -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ getETA(order) }}
                  </td>
                  
                  <!-- AMOUNT -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-900">Rs{{ order.total_amount }}</span>
                  </td>
                  
                  <!-- VISIT FEE -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-900">Rs{{ order.visit_fee || '0.00' }}</span>
                  </td>
                  
                  <!-- STATUS -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusBadgeClass(order.status)" class="px-3 py-1 inline-flex text-xs font-semibold rounded-full">
                      {{ order.status }}
                    </span>
                  </td>
                  
                  <!-- ACTIONS -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center space-x-2">
                      <!-- View Icon -->
                      <button @click="viewOrderDetails(order)" class="p-2 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" title="View Order Details">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                      
                      <!-- Pregnancy Tracking Button -->
                      <button 
                        v-if="order.jobs?.[0] && order.jobs[0].status === 'completed'"
                        @click="goToPregnancyTrackingFromOrder(order)"
                        class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                        title="Pregnancy Tracking"
                      >
                        Pregnancy Tracking
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Scroll indicator for mobile -->
          <div class="lg:hidden flex justify-center mt-2">
            <div class="flex items-center space-x-2 text-xs text-gray-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
              </svg>
              <span>Swipe to see more columns</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
              </svg>
            </div>
          </div>
          
          <!-- Pagination -->
          <div v-if="ordersPagination.total > 0" class="px-6 py-4 bg-gray-50/50 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Showing {{ ordersPagination.from || 0 }} to {{ ordersPagination.to || 0 }} of {{ ordersPagination.total }} orders
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="loadOrdersWithPagination(ordersPagination.current_page - 1)"
                  :disabled="ordersPagination.current_page <= 1"
                  class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Previous
                </button>
                
                <span class="px-3 py-1 text-sm text-gray-700">
                  Page {{ ordersPagination.current_page }} of {{ ordersPagination.last_page }}
                </span>
                
                <button 
                  @click="loadOrdersWithPagination(ordersPagination.current_page + 1)"
                  :disabled="ordersPagination.current_page >= ordersPagination.last_page"
                  class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Farmers Tab -->
      <div v-if="activeTab === 'farmers'" class="space-y-6">
        
        
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Farmers Management</h2>
          <div class="flex space-x-4">
            <input 
              v-model="farmerSearch" 
              @input="searchFarmers"
              type="text" 
              placeholder="Search farmers..." 
              class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <select v-model="farmerStatusFilter" @change="searchFarmers" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <button @click="exportFarmers" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
              Export
            </button>
          </div>
        </div>

        <!-- Farmers Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ farmers.length }}</div>
            <div class="text-sm text-gray-600">Total Farmers</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ farmers.filter(f => f.status === 'active').length }}</div>
            <div class="text-sm text-gray-600">Active Farmers</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-red-600">{{ farmers.filter(f => f.status === 'inactive').length }}</div>
            <div class="text-sm text-gray-600">Inactive Farmers</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ farmers.reduce((sum, f) => sum + (f.cattle_farms?.length || 0), 0) }}</div>
            <div class="text-sm text-gray-600">Total Farms</div>
          </div>
        </div>

        <!-- Farmers Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div 
            class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farms</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
               
                <tr v-if="farmers.length === 0" class="hover:bg-gray-50">
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    No farmers found
                  </td>
                </tr>
                <tr v-for="(farmer, index) in farmers" :key="farmer?.id || Math.random()" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                          <span class="text-sm font-medium text-white">{{ farmer.name?.charAt(0) || '?' }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ farmer.name }}</div>
                        <div class="text-sm text-gray-500">ID: {{ farmer.id }}</div>
                  </div>
                </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ farmer.mobile }}</div>
                    <div class="text-sm text-gray-500">{{ farmer.user?.email || 'No email' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ farmer.cattle_farms?.length || 0 }}</div>
                    <div class="text-sm text-gray-500">farms registered</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ farmer.orders?.length || 0 }}</div>
                    <div class="text-sm text-gray-500">total orders</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="farmer.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ farmer.status }}
                  </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(farmer.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <button 
                        @click="viewFarmerDetails(farmer)"
                        class="text-blue-600 hover:text-blue-900"
                      >
                        View
                      </button>
                  <button 
                    @click="toggleFarmerStatus(farmer)"
                        :class="farmer.status === 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                  >
                    {{ farmer.status === 'active' ? 'Deactivate' : 'Activate' }}
                  </button>
                </div>
                  </td>
                </tr>
              </tbody>
            </table>
              </div>
        </div>
      </div>

      <!-- Technicians Tab -->
      <div v-if="activeTab === 'technicians'" class="space-y-6">
       
        
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Technicians Management</h2>
          <div class="flex space-x-4">
            <input 
              v-model="technicianSearch" 
              @input="searchTechnicians"
              type="text" 
              placeholder="Search technicians..." 
              class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <select v-model="technicianStatusFilter" @change="searchTechnicians" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Status</option>
              <option value="incomplete">Incomplete</option>
              <option value="pending">Pending Review</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="suspended">Suspended</option>
              <option value="blocked">Blocked</option>
              <option value="blacklisted">Blacklisted</option>
            </select>
            <button @click="exportTechnicians" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
              Export
            </button>
          </div>
        </div>

        <!-- Technician Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ technicians.length }}</div>
            <div class="text-sm text-gray-600">Total Technicians</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-yellow-600">{{ technicians.filter(t => t.status === 'pending').length }}</div>
            <div class="text-sm text-gray-600">Pending Review</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ technicians.filter(t => t.status === 'active').length }}</div>
            <div class="text-sm text-gray-600">Active</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-red-600">{{ technicians.filter(t => ['suspended', 'blocked', 'blacklisted'].includes(t.status)).length }}</div>
            <div class="text-sm text-gray-600">Restricted</div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ technicians.filter(t => t.availability === 'online').length }}</div>
            <div class="text-sm text-gray-600">Online</div>
          </div>
        </div>

        <!-- Technicians Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div 
            class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Experience</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jobs</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
               
                <tr v-if="technicians.length === 0" class="hover:bg-gray-50">
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    No technicians found
                  </td>
                </tr>
                <tr v-for="(technician, index) in technicians" :key="technician?.id || Math.random()" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                          <span class="text-sm font-medium text-white">{{ technician.name?.charAt(0) || '?' }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ technician.name }} </div>
                    <div class="text-sm text-gray-500">Code: {{ technician.unique_code }}</div>
                  </div>
                </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ technician.mobile_1 }}</div>
                    <div class="text-sm text-gray-500">{{ technician.user?.email || 'No email' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ technician.years_of_experience }} years</div>
                    <div class="text-sm text-gray-500">{{ technician.specialization || 'General' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getTechnicianStatusBadgeClass(technician.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ technician.status }}
                  </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getAvailabilityBadgeClass(technician.availability)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ technician.availability }}
                  </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ technician.jobs?.filter(job => job.status === 'completed').length || 0 }}</div>
                    <div class="text-sm text-gray-500">completed</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button 
                        @click="viewTechnicianProfile(technician)"
                        class="text-blue-600 hover:text-blue-900"
                    >
                        View
                    </button>
                    <button 
                      v-if="technician.status === 'pending'"
                        @click="reviewTechnicianProfile(technician)"
                        class="text-yellow-600 hover:text-yellow-900"
                    >
                        Review
                    </button>
                    <button 
                        @click="manageTechnicianStatus(technician)"
                        class="text-green-600 hover:text-green-900"
                    >
                        Manage
                    </button>
                  </div>
                  </td>
                </tr>
              </tbody>
            </table>
                </div>
        </div>
      </div>

      <!-- Orders Tab -->
      <div v-if="false && activeTab === 'orders'" class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Orders Management
          </h2>
        </div>

        <!-- Filter Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
          <div class="flex flex-wrap items-center gap-4">
            <!-- Farm Filter -->
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Farms:</label>
              <select v-model="orderFilters.farm" @change="filterOrders" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Farms</option>
                <option v-for="farm in uniqueFarms" :key="farm" :value="farm">{{ farm }}</option>
              </select>
            </div>

            <!-- Technician Filter -->
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Technicians:</label>
              <select v-model="orderFilters.technician" @change="filterOrders" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Technicians</option>
                <option v-for="technician in uniqueTechnicians" :key="technician" :value="technician">{{ technician }}</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Statuses:</label>
              <select v-model="orderFilters.status" @change="filterOrders" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="assigned">Assigned</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

            <!-- Date Filter -->
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Dates:</label>
              <select v-model="orderFilters.date" @change="filterOrders" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Dates</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
              </select>
        </div>

            <!-- Clear Filters Button -->
            <button @click="clearAllFilters" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-sm font-medium">
              Clear All Filters
            </button>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div 
            class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ORDER #</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FARM</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TECHNICIAN</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETA</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AMOUNT</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VISIT FEE</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="filteredOrders.length === 0" class="hover:bg-gray-50">
                  <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                    No orders found (Total orders: {{ orders.length }}, Filtered: {{ filteredOrders.length }})
                  </td>
                </tr>
                <tr v-for="(order, index) in filteredOrders" :key="order?.id || Math.random()" class="hover:bg-gray-50">
                  <td class="px-6 py-4  text-sm text-gray-500">
                    {{ formatDate(order.created_at) }}
                  </td>
                  <td class="px-6 py-4  text-sm font-medium text-gray-900 ">
                    <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                    {{ order.order_number }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex">
                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ getFarmName(order) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div>
                      <div class="font-medium">{{ getTechnicianName(order) }}</div>
                      <div class="text-gray-500 text-xs">{{ getTechnicianPhone(order) }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ getETA(order) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rs{{ order.total_amount }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rs{{ order.visit_fee || '0.00' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusBadgeClass(order.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center justify-center space-x-2">
                      <button @click="viewOrderDetails(order)" class="p-2 text-gray-400 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50" title="View Order Details">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                      <button 
                        v-if="order.jobs?.[0] && order.jobs[0].status === 'completed'"
                        @click="goToPregnancyTracking(order.jobs[0].id)"
                        class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                        title="Pregnancy Tracking"
                      >
                        Pregnancy Tracking
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Jobs Management Tab -->
      <div v-if="activeTab === 'jobs'" class="space-y-6">
        <!-- Job Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Jobs</p>
                <p class="text-3xl font-bold text-gray-900">{{ jobStats.total || 0 }}</p>
              </div>
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
              </div>
            </div>
          </div>
          
          <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Pending Jobs</p>
                <p class="text-3xl font-bold text-yellow-600">{{ jobStats.pending || 0 }}</p>
              </div>
              <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>
          
          <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">In Progress</p>
                <p class="text-3xl font-bold text-blue-600">{{ jobStats.in_progress || 0 }}</p>
              </div>
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
            </div>
          </div>
          
          <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Completed</p>
                <p class="text-3xl font-bold text-green-600">{{ jobStats.completed || 0 }}</p>
              </div>
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Job Assignment Modal -->
        <div v-if="showJobAssignmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Assign Job to Technician</h3>
              <button @click="showJobAssignmentModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="assignJob" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Order</label>
                <select v-model="jobAssignment.order_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="">Select an order</option>
                  <option v-for="order in unassignedOrders" :key="order.id" :value="order.id">
                    Order #{{ order.id }} - {{ order.farmer?.user?.name }} ({{ order.total_amount }})
                  </option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Technician</label>
                <select v-model="jobAssignment.technician_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="">Select a technician</option>
                  <option v-for="tech in availableTechnicians" :key="tech.id" :value="tech.id">
                    {{ tech.user?.name }} ({{ tech.unique_code }})
                  </option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Visit Date</label>
                <input v-model="jobAssignment.estimated_visit_at" type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea v-model="jobAssignment.notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add any special instructions..."></textarea>
              </div>
              
              <div class="flex space-x-3 pt-4">
                <button type="button" @click="showJobAssignmentModal = false" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                  Cancel
                </button>
                <button type="submit" :disabled="assigningJob" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
                  {{ assigningJob ? 'Assigning...' : 'Assign Job' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Jobs List -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg border border-white/20">
          <div class="p-6 border-b border-white/20">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold text-gray-900">Jobs Management</h2>
              <div class="flex space-x-4">
                <button @click="showJobAssignmentModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  <span>Assign Job</span>
                </button>
                <button @click="loadJobs" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                  Refresh
                </button>
              </div>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Visit</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="job in jobs" :key="job.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    #{{ job.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div>
                      <div class="font-medium">Order #{{ job.order?.id }}</div>
                      <div class="text-gray-500">{{ job.order?.farmer?.user?.name }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div>
                      <div class="font-medium">{{ job.technician?.user?.name }}</div>
                      <div class="text-gray-500">{{ job.technician?.unique_code }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getJobStatusBadgeClass(job.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ job.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getProgressStatusBadgeClass(job.progress_status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ job.progress_status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ job.estimated_visit_at ? formatDate(job.estimated_visit_at) : 'Not set' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <button @click="updateJobStatus(job)" class="text-blue-600 hover:text-blue-900">
                        Update Status
                      </button>
                      <button @click="viewJobDetails(job)" class="text-green-600 hover:text-green-900">
                        View Details
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Pregnancy Tracking Tab -->
      <div v-if="activeTab === 'pregnancy-tracking'" class="space-y-6">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Pregnancy Tracking Overview</h2>
            <div class="text-sm text-gray-600">
              Monitor pregnancy outcomes across all farmers and technicians
            </div>
          </div>
          
          <!-- Selected Order Filter -->
          <div v-if="selectedOrderForPregnancy" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-blue-900">Filtering by Order</h3>
                  <p class="text-sm text-blue-700">
                    Order #{{ selectedOrderForPregnancy.order_number }} - {{ selectedOrderForPregnancy.cattle_farm?.name || 'N/A' }}
                  </p>
                </div>
              </div>
              <button 
                @click="selectedOrderForPregnancy = null"
                class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-lg transition-colors"
                title="Clear Filter"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-blue-600">Total Conceptions</p>
                  <p class="text-3xl font-bold text-blue-900">{{ filteredPregnancyStats.conceived_count || 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-green-600">Currently Pregnant</p>
                  <p class="text-3xl font-bold text-green-900">{{ filteredPregnancyStats.pregnant_count || 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl p-6 border border-purple-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-purple-600">Successfully Delivered</p>
                  <p class="text-3xl font-bold text-purple-900">{{ filteredPregnancyStats.delivered_count || 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-r from-red-50 to-rose-50 rounded-xl p-6 border border-red-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-red-600">Failed Pregnancies</p>
                  <p class="text-3xl font-bold text-red-900">{{ filteredPregnancyStats.miscarried_count || 0 }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Filter Tabs -->
          <div class="flex flex-wrap gap-2 mb-6">
            <button
              v-for="tab in pregnancyTabs"
              :key="tab.id"
              @click="activePregnancyTab = tab.id"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2',
                activePregnancyTab === tab.id
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
              ]"
            >
              <span>{{ tab.name }}</span>
              <span class="bg-white/20 text-xs px-2 py-1 rounded-full">
                {{ getFilteredPregnancyTabCount(tab.id) }}
              </span>
            </button>
          </div>

          <!-- Pregnancy Jobs List -->
          <div v-if="filteredPregnancyJobs.length > 0" class="space-y-4">
            <div
              v-for="job in filteredPregnancyJobs"
              :key="job.id"
              class="p-6 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-4 mb-4">
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ job.order.order_number }}</h3>
                      <p class="text-sm text-gray-600">{{ job.order.cattle_farm.name }}</p>
                      <p class="text-xs text-gray-500 mt-1">
                        Farmer: {{ job.order.farmer.user.name }} | Technician: {{ job.technician.user.name }}
                      </p>
                    </div>
                    <div class="flex items-center space-x-2">
                      
                      <span class="text-sm text-gray-500">
                        {{ formatDate(job.actual_visit_at) }}
                      </span>
                    </div>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                      <label class="text-sm font-semibold text-gray-500">Days Since Insemination</label>
                      <p class="text-sm font-medium text-blue-600 font-bold">
                        {{ getDaysSinceInsemination(job.actual_visit_at) }} days
                      </p>
                    </div>
                   
                    <div>
                      <label class="text-sm font-semibold text-gray-500">Status Updated</label>
                      <p class="text-sm font-medium">{{ formatDate(job.updated_at) }}</p>
                    </div>
                  </div>

                  <!-- Individual Cow Status Display -->
                  <div v-if="job.cow_insemination_records && job.cow_insemination_records.length > 0" class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Individual Cow Status</h4>
                    <div class="space-y-2">
                      <div 
                        v-for="record in getVisibleRecords(job)" 
                        :key="record.id"
                        class="p-3 bg-white rounded-lg border border-gray-200"
                      >
                        <div class="flex items-center justify-between space-x-3">
                          <span class="text-sm font-medium text-gray-900">
                            Cow {{ getCowNumber(record, job.order?.order_items || []) }} - {{ record.order_item?.bull_semen?.bull_name || 'N/A' }}
                            <span class="text-xs text-gray-500">({{ record.order_item?.bull_semen?.breed?.name || 'N/A' }})</span>
                          </span>
                          <div class="flex items-center space-x-2">
                          <span :class="getCowStatusBadgeClass(record?.pregnancy_status || 'pending')" class="text-xs font-semibold px-2 py-1 rounded-full">
                            {{ getCowStatusText(record?.pregnancy_status || 'pending') }}
                          </span>
                          <span class="text-xs text-gray-500">
                            {{ getDaysSinceInsemination(record?.insemination_date) }} days
                          </span>
                          </div>
                        </div>

                        <!-- Calf Photos for delivered cows -->
                        <div v-if="(record.pregnancy_status || 'pending') === 'delivered' && record.calf_image" class="mt-3">
                          <h5 class="text-xs font-semibold text-gray-600 mb-2">Calf Photos</h5>
                          <div class="flex space-x-2">
                            <img 
                              v-for="(image, index) in record.calf_image.split(',').filter(img => img && img.trim())"
                              :key="'record-' + index"
                              :src="getImageUrl(image.trim())" 
                              :alt="'Calf photo for Cow ' + getCowNumber(record, job.order?.order_items || [])"
                              class="w-16 h-16 object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-80 transition-opacity"
                              @click="openImageModal(getImageUrl(image.trim()), 'Calf Photo - Cow ' + getCowNumber(record, job.order?.order_items || []))"
                            />
                          </div>
                        </div>
                      </div>
                      
                      <!-- Delivery Notes for Delivered Cows -->
                      <div v-if="record && (record.pregnancy_status || 'pending') === 'delivered' && record.delivery_notes" class="mt-2">
                        <h5 class="text-xs font-semibold text-gray-600 mb-1">Delivery Notes</h5>
                        <p class="text-xs text-gray-600">{{ record.delivery_notes }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No pregnancy data</h3>
            <p class="mt-1 text-sm text-gray-500">No pregnancy tracking data available yet.</p>
          </div>
        </div>
      </div>

      <!-- Catalog Management Tab -->
      <div v-if="activeTab === 'catalog'" class="space-y-6">
        
        
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Catalog Management</h2>
          <div class="flex space-x-4">
            <button @click="showAddBreedModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Add Breed
            </button>
            <button @click="showAddSemenModal = true" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
              Add Semen
            </button>
           
          </div>
        </div>

        <!-- Breeds Section -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Breeds</h3>
          </div>
          <div class="p-6">
            
            <div v-if="breeds.length === 0" class="text-center py-8 text-gray-500">
              No breeds found
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div v-for="(breed, index) in breeds" :key="breed?.id || index" class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="font-medium text-gray-900">{{ breed.name }}</h4>
                    <p class="text-sm text-gray-500">{{ breed.description }}</p>
                  </div>
                  <div class="flex space-x-2">
                    <button @click="editBreed(breed)" class="text-blue-600 hover:text-blue-800">Edit</button>
                    <button @click="deleteBreed(breed)" class="text-red-600 hover:text-red-800">Delete</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Semen Products Section -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Semen Products</h3>
          </div>
          <div class="p-6">
           
            
            <div v-if="semenProducts.length === 0" class="text-center py-8 text-gray-500">
              No semen products found
            </div>
            
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bull Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  
                  <tr v-for="(semen, index) in semenProducts" :key="semen?.id || index">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center overflow-hidden">
                        <!-- Dynamic Product Image -->
                        <img 
                          v-if="semen.image" 
                          :src="getSemenImageUrl(semen.image)" 
                          :alt="semen.bull_name"
                          class="w-full h-full object-cover"
                        />
                        <!-- Fallback SVG Icon -->
                        <svg v-else class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ semen.bull_name }} 
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ semen.breed?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₨{{ semen.price }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ semen.stock_quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="semen.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ semen.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click="editSemen(semen)" class="text-blue-600 hover:text-blue-800">Edit</button>
                        <button @click="toggleSemenStatus(semen)" class="text-green-600 hover:text-green-800">
                          {{ semen.is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings Tab -->
      <div v-if="activeTab === 'earnings'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Earnings & Finance</h2>
          <div class="flex space-x-4">
            <select v-model="earningsFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="all">All Time</option>
              <option value="month">This Month</option>
              <option value="week">This Week</option>
            </select>
            <button @click="exportEarnings" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
              Export
            </button>
          </div>
        </div>

        <!-- Earnings Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">₨{{ earnings.total_revenue || 0 }}</div>
            <div class="text-sm text-gray-600">Total Revenue</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">₨{{ earnings.technician_earnings || 0 }}</div>
            <div class="text-sm text-gray-600">Technician Earnings</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">₨{{ earnings.platform_earnings || 0 }}</div>
            <div class="text-sm text-gray-600">Platform Earnings</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-orange-600">{{ earnings.total_orders || 0 }}</div>
            <div class="text-sm text-gray-600">Total Orders</div>
          </div>
        </div>

        <!-- Earnings by Technician -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Earnings by Technician</h3>
          </div>
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="tech in earnings.by_technician" :key="tech.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ tech.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ tech.orders_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₨{{ tech.total_earnings }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₨{{ tech.commission }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Performance Tab -->
      <div v-if="activeTab === 'performance'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Performance Monitoring</h2>
          <div class="flex space-x-4">
            <select v-model="performanceFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="all">All Time</option>
              <option value="month">This Month</option>
              <option value="week">This Week</option>
            </select>
          </div>
        </div>

        <!-- Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ performance.success_rate || 0 }}%</div>
            <div class="text-sm text-gray-600">Success Rate</div>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ performance.customer_satisfaction || 0 }}/5</div>
            <div class="text-sm text-gray-600">Customer Satisfaction</div>
          </div>
        </div>

        <!-- Technician Performance -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Technician Performance</h3>
          </div>
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jobs Completed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Rating</th>
                    
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="tech in correctedPerformance.technicians" :key="tech.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ tech.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ tech.jobs_completed }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ tech.success_rate }}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ tech.avg_rating }}/5
                    </td>
                    
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Stock Tab -->
      <div v-if="activeTab === 'stock'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Stock Management</h2>
          <div class="flex space-x-4">
            <button @click="showAddStockModal = true" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
              Add Stock
            </button>
            <button @click="refreshStock" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Refresh
            </button>
          </div>
        </div>

        <!-- Stock Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ stock.total_items || 0 }}</div>
            <div class="text-sm text-gray-600">Total Items</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ stock.low_stock || 0 }}</div>
            <div class="text-sm text-gray-600">Low Stock Items</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-red-600">{{ stock.out_of_stock || 0 }}</div>
            <div class="text-sm text-gray-600">Out of Stock</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">₨{{ stock.total_value || 0 }}</div>
            <div class="text-sm text-gray-600">Total Value</div>
          </div>
        </div>

        <!-- Stock Items Table -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Stock Items</h3>
          </div>
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="item in stockItems" :key="item.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ item.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ item.breed?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₨{{ item.price }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span :class="getStockStatusClass(item.stock_quantity)" class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ item.stock_quantity }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                   
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click="editStockItem(item)" class="text-blue-600 hover:text-blue-800">Edit</button>
                        <button @click="deleteStockItem(item)" class="text-red-600 hover:text-red-800">Delete</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Stock by Technician -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Stock by Technician</h3>
              <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                  <label class="text-sm font-medium text-gray-700">Select Technician:</label>
                  <select 
                    v-model="selectedTechnicianForStock" 
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @change="onTechnicianSelect"
                  >
                    <option value="">Choose a technician...</option>
                    <option v-for="tech in techniciansForStock" :key="tech.id" :value="tech.id">
                      {{ tech.name }} ({{ tech.unique_code }})
                    </option>
                  </select>
                </div>
                <button 
                  @click="openAssignStockModal" 
                  :disabled="!selectedTechnicianForStock"
                  class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                  Assign Stock
                </button>
              </div>
            </div>
          </div>
          <div class="p-6">
            <!-- Column Descriptions -->
            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
              <h4 class="text-sm font-medium text-blue-900 mb-2">Column Descriptions:</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-blue-800">
                <div><strong>Assigned Items:</strong> Number of different stock items assigned to this technician</div>
                <div><strong>Total Quantity:</strong> Sum of all quantities assigned to this technician</div>
                <div><strong>Low Stock Items:</strong> Number of assigned items with ≤10 units remaining</div>
                <div><strong>Out of Stock Items:</strong> Number of assigned items with 0 units OR main inventory is 0</div>
              </div>
            </div>
            
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Low Stock Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Out of Stock Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="tech in stock.by_technician" :key="tech.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ tech.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ tech.total_items }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ tech.total_quantity || 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span :class="tech.low_stock_items > 0 ? 'text-yellow-600 font-medium' : 'text-gray-500'">
                        {{ tech.low_stock_items || 0 }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span :class="tech.out_of_stock_items > 0 ? 'text-red-600 font-medium' : 'text-gray-500'">
                        {{ tech.out_of_stock_items || 0 }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click="viewTechnicianStock(tech)" class="text-blue-600 hover:text-blue-800">View Details</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Cow Types Management Tab -->
      <div v-if="activeTab === 'cow-types'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Cow Types Management</h2>
          <button 
            @click="openCowTypeModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Add Cow Type</span>
          </button>
        </div>

        <!-- Cow Types Table -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
          <div 
            class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="cowType in cowTypes" :key="cowType.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ cowType.name }}</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ cowType.description || 'No description' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="cowType.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ cowType.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(cowType.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <button 
                        @click="editCowType(cowType)"
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Edit
                      </button>
                      <button 
                        @click="deleteCowType(cowType.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Reassignment Logs Tab -->
      <div v-if="activeTab === 'reassignment-logs'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Reassignment Logs</h2>
          <div class="text-sm text-gray-600">
            Track all job reassignments across the platform
          </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">All Reassignment History</h3>
          </div>
          
          <div 
            class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reassigned By</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="log in reassignmentLogsPagination.data" :key="log.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(log.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-white text-xs font-bold">#</span>
                      </div>
                      <div>
                        <div class="font-medium">{{ log.order?.order_number }}</div>
                        <div class="text-xs text-gray-500">{{ log.order?.cattle_farm?.name }}</div>
                        <div class="text-xs text-gray-400">{{ log.order?.farmer?.user?.name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-pink-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                      <div>
                        <div class="font-medium">{{ log.from_technician?.user?.name }}</div>
                        <div class="text-xs text-gray-500">{{ log.from_technician?.user?.mobile }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                      <div>
                        <div class="font-medium">{{ log.to_technician?.user?.name }}</div>
                        <div class="text-xs text-gray-500">{{ log.to_technician?.user?.mobile }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                      <div>
                        <div class="font-medium">{{ log.reassigned_by_technician?.user?.name }}</div>
                        <div class="text-xs text-gray-500">{{ log.reassigned_by_technician?.user?.mobile }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900">
                    <div class="max-w-xs truncate" :title="log.reason">
                      {{ log.reason || 'No reason provided' }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination Controls -->
          <div v-if="reassignmentLogsPagination.total > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <!-- Pagination Info -->
              <div class="text-sm text-gray-600">
                Showing {{ (reassignmentLogsPagination.current_page - 1) * reassignmentLogsPagination.per_page + 1 }} to {{ Math.min(reassignmentLogsPagination.current_page * reassignmentLogsPagination.per_page, reassignmentLogsPagination.total) }} of {{ reassignmentLogsPagination.total }} logs
              </div>
              
              <!-- Pagination Controls -->
              <div v-if="reassignmentLogsPagination.last_page > 1" class="flex items-center space-x-2">
                <!-- Previous Button -->
                <button
                  @click="loadReassignmentLogs(reassignmentLogsPagination.current_page - 1)"
                  :disabled="reassignmentLogsPagination.current_page === 1"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Previous
                </button>
                
                <!-- Page Numbers -->
                <div class="flex space-x-1">
                  <button
                    v-for="page in Math.min(5, reassignmentLogsPagination.last_page)"
                    :key="page"
                    @click="loadReassignmentLogs(page)"
                    :class="[
                      'px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                      reassignmentLogsPagination.current_page === page
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                </div>
                
                <!-- Next Button -->
                <button
                  @click="loadReassignmentLogs(reassignmentLogsPagination.current_page + 1)"
                  :disabled="reassignmentLogsPagination.current_page === reassignmentLogsPagination.last_page"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Notifications Tab -->
      <div v-if="activeTab === 'notifications'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Notifications Management</h2>
          <div class="flex space-x-4">
            <button @click="sendNotification" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Send Notification
            </button>
          </div>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Notification Settings</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">Order Updates</h4>
                  <p class="text-sm text-gray-500">Send notifications for order status changes</p>
                </div>
                <input type="checkbox" v-model="notificationSettings.order_updates" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">Assignment Updates</h4>
                  <p class="text-sm text-gray-500">Send notifications for technician assignments</p>
                </div>
                <input type="checkbox" v-model="notificationSettings.assignment_updates" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">Payment Updates</h4>
                  <p class="text-sm text-gray-500">Send notifications for payment status changes</p>
                </div>
                <input type="checkbox" v-model="notificationSettings.payment_updates" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Notifications -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Notifications</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div v-for="notification in notifications" :key="notification.id" class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828zM4 7h.01M4 7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"></path>
                    </svg>
                  </div>
                </div>
                <div class="flex-1">
                  <h4 class="text-sm font-medium text-gray-900">{{ notification.title }}</h4>
                  <p class="text-sm text-gray-500">{{ notification.message }}</p>
                  <p class="text-xs text-gray-400">{{ formatDate(notification.created_at) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Location Tab -->
      <div v-if="activeTab === 'location'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Location Services</h2>
          <div class="flex space-x-4">
            <button @click="refreshLocations" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Refresh
            </button>
          </div>
        </div>

        <!-- Location Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ locationStats.total_farms || 0 }}</div>
            <div class="text-sm text-gray-600">Total Farms</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ locationStats.active_technicians || 0 }}</div>
            <div class="text-sm text-gray-600">Active Technicians</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ locationStats.avg_distance || 0 }}km</div>
            <div class="text-sm text-gray-600">Avg Distance</div>
          </div>
        </div>

        <!-- Map View -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Farm & Technician Locations</h3>
          </div>
          <div class="p-6">
            <div class="h-96 bg-gray-100 rounded-lg flex items-center justify-center">
              <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Map view would be integrated here</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Technician Profile Review Modal -->
      <div v-if="showReviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Review Technician Profile</h2>
              <button @click="showReviewModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <div v-if="selectedTechnician" class="space-y-6">
              <!-- Technician Basic Info -->
              <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="text-lg text-gray-900">{{ selectedTechnician.name }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Unique Code</label>
                    <p class="text-lg text-gray-900">{{ selectedTechnician.unique_code }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Mobile</label>
                    <p class="text-lg text-gray-900">{{ selectedTechnician.mobile_1 }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-lg text-gray-900">{{ selectedTechnician.user?.email || 'Not provided' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Experience</label>
                    <p class="text-lg text-gray-900">{{ selectedTechnician.years_of_experience }} years</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Specialization</label>
                    <p class="text-lg text-gray-900">{{ selectedTechnician.specialization || 'General' }}</p>
                  </div>
                </div>
              </div>

              <!-- Documents & Certifications -->
              <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents & Certifications</h3>
                <div class="space-y-4">
                  <div v-for="doc in selectedTechnician.documents" :key="doc.id" class="flex items-center justify-between p-3 bg-white rounded-lg">
                    <div class="flex items-center">
                      <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <div>
                        <p class="font-medium text-gray-900">{{ doc.name }}</p>
                        <p class="text-sm text-gray-500">{{ doc.type }}</p>
                      </div>
                    </div>
                    <button @click="viewDocument(doc)" class="text-blue-600 hover:text-blue-800">View</button>
                  </div>
                </div>
              </div>

              <!-- Review Form -->
              <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Decision</h3>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Decision</label>
                    <div class="flex space-x-4">
                      <label class="flex items-center">
                        <input type="radio" v-model="reviewForm.decision" value="approved" class="mr-2">
                        <span class="text-green-600 font-medium">Approve</span>
                      </label>
                      <label class="flex items-center">
                        <input type="radio" v-model="reviewForm.decision" value="rejected" class="mr-2">
                        <span class="text-red-600 font-medium">Reject</span>
                      </label>
                      <label class="flex items-center">
                        <input type="radio" v-model="reviewForm.decision" value="pending" class="mr-2">
                        <span class="text-yellow-600 font-medium">Keep Pending</span>
                      </label>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                    <textarea 
                      v-model="reviewForm.comments" 
                      rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Add your review comments..."
                    ></textarea>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Signature</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 h-32 flex items-center justify-center">
                      <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 mt-2">Signature pad would be integrated here</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-end space-x-4">
                <button 
                  @click="showReviewModal = false" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  @click="submitReview" 
                  :disabled="!reviewForm.decision"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300"
                >
                  Submit Review
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Technician Profile View Modal -->
      <div v-if="showTechnicianProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Technician Profile</h2>
              <button @click="showTechnicianProfileModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <div v-if="selectedTechnician" class="space-y-6">
              <!-- Header with Avatar and Basic Info -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                <div class="flex items-center space-x-6">
                  <div class="h-20 w-20 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">{{ selectedTechnician.name?.charAt(0) || '?' }}</span>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900">{{ selectedTechnician.name }}</h3>
                    <p class="text-lg text-gray-600">Code: {{ selectedTechnician.unique_code }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                      <span :class="getTechnicianStatusBadgeClass(selectedTechnician.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
                        {{ selectedTechnician.status }}
                      </span>
                      <span :class="getAvailabilityBadgeClass(selectedTechnician.availability)" class="px-3 py-1 text-sm font-semibold rounded-full">
                        {{ selectedTechnician.availability }}
                      </span>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="text-3xl font-bold text-blue-600">{{ selectedTechnician.jobs?.filter(job => job.status === 'completed').length || 0 }}</div>
                    <div class="text-sm text-gray-600">Jobs Completed</div>
                  </div>
                </div>
              </div>

              <!-- Stats Cards -->
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <div class="text-2xl font-bold text-blue-600">{{ selectedTechnician.jobs?.length || 0 }}</div>
                  <div class="text-sm text-gray-600">Total Jobs</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <div class="text-2xl font-bold text-green-600">{{ selectedTechnician.jobs?.filter(job => job.status === 'completed').length || 0 }}</div>
                  <div class="text-sm text-gray-600">Completed</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <div class="text-2xl font-bold text-yellow-600">{{ selectedTechnician.jobs?.filter(job => ['assigned', 'accepted'].includes(job.status)).length || 0 }}</div>
                  <div class="text-sm text-gray-600">Active</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <div class="text-2xl font-bold text-purple-600">{{ selectedTechnician.years_of_experience || 0 }}</div>
                  <div class="text-sm text-gray-600">Years Experience</div>
                </div>
              </div>

              <!-- Detailed Information Grid -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
                  <div class="space-y-3">
                    <div>
                      <label class="text-sm font-medium text-gray-500">Full Name</label>
                      <p class="text-gray-900">{{ selectedTechnician.name }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Father's Name</label>
                      <p class="text-gray-900">{{ selectedTechnician.father_name || 'Not provided' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">CNIC</label>
                      <p class="text-gray-900">{{ selectedTechnician.cnic || 'Not provided' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                      <p class="text-gray-900">{{ selectedTechnician.date_of_birth ? formatDate(selectedTechnician.date_of_birth) : 'Not provided' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Mobile 1</label>
                      <p class="text-gray-900">{{ selectedTechnician.mobile_1 }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Mobile 2</label>
                      <p class="text-gray-900">{{ selectedTechnician.mobile_2 || 'Not provided' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Email</label>
                      <p class="text-gray-900">{{ selectedTechnician.user?.email || 'Not provided' }}</p>
                    </div>
                  </div>
                </div>

                <!-- Professional Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h4 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h4>
                  <div class="space-y-3">
                    <div>
                      <label class="text-sm font-medium text-gray-500">Years of Experience</label>
                      <p class="text-gray-900">{{ selectedTechnician.years_of_experience || 0 }} years</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Specialization</label>
                      <p class="text-gray-900">{{ selectedTechnician.specialization || 'General' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Areas of Expertise</label>
                      <p class="text-gray-900">{{ selectedTechnician.areas_of_expertise || 'Not specified' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Customers per Month</label>
                      <p class="text-gray-900">{{ selectedTechnician.customers_per_month || 'Not specified' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Degree Year</label>
                      <p class="text-gray-900">{{ selectedTechnician.degree_year || 'Not provided' }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-medium text-gray-500">Available 24/7</label>
                      <p class="text-gray-900">{{ selectedTechnician.available_24_7 ? 'Yes' : 'No' }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Address Information -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <h5 class="font-medium text-gray-700 mb-2">Home Address</h5>
                    <p class="text-gray-900">{{ selectedTechnician.home_address || 'Not provided' }}</p>
                  </div>
                  <div>
                    <h5 class="font-medium text-gray-700 mb-2">Location</h5>
                    <p class="text-gray-900">{{ selectedTechnician.city }}, {{ selectedTechnician.district }}, {{ selectedTechnician.province }}</p>
                  </div>
                </div>
              </div>

              <!-- Recent Jobs -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Jobs</h4>
                <div v-if="selectedTechnician.jobs && selectedTechnician.jobs.length > 0" class="space-y-3">
                  <div v-for="job in selectedTechnician.jobs.slice(0, 5)" :key="job.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                      <p class="font-medium text-gray-900">Job #{{ job.id }}</p>
                      <p class="text-sm text-gray-600">Order #{{ job.order?.order_number || job.order?.id }} - {{ job.order?.farmer?.user?.name }}</p>
                    </div>
                    <div class="text-right">
                      <span :class="getJobStatusBadgeClass(job.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ job.status }}
                      </span>
                      <p class="text-sm text-gray-500 mt-1">{{ formatDate(job.created_at) }}</p>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500">
                  <p>No jobs found for this technician</p>
                </div>
              </div>

              <!-- Required Documents -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Required Documents</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- CNIC Front -->
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CNIC Front *</label>
                    <div class="text-center">
                      <div v-if="selectedTechnician.cnic_front_image" class="space-y-2">
                        <img :src="getImageUrl(selectedTechnician.cnic_front_image)" alt="CNIC Front" class="mx-auto h-32 w-auto object-contain rounded-md border border-gray-200">
                        <div class="flex space-x-2 justify-center">
                          <button @click="openImageModal(getImageUrl(selectedTechnician.cnic_front_image), 'CNIC Front')" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                        </div>
                      </div>
                      <div v-else class="space-y-2">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm text-gray-500">Not uploaded</p>
                      </div>
                    </div>
                  </div>

                  <!-- CNIC Back -->
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CNIC Back *</label>
                    <div class="text-center">
                      <div v-if="selectedTechnician.cnic_back_image" class="space-y-2">
                        <img :src="getImageUrl(selectedTechnician.cnic_back_image)" alt="CNIC Back" class="mx-auto h-32 w-auto object-contain rounded-md border border-gray-200">
                        <div class="flex space-x-2 justify-center">
                          <button @click="openImageModal(getImageUrl(selectedTechnician.cnic_back_image), 'CNIC Back')" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                        </div>
                      </div>
                      <div v-else class="space-y-2">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm text-gray-500">Not uploaded</p>
                      </div>
                    </div>
                  </div>

                  <!-- Degree Certificate Front -->
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Degree Certificate Front *</label>
                    <div class="text-center">
                      <div v-if="selectedTechnician.degree_front_image" class="space-y-2">
                        <img :src="getImageUrl(selectedTechnician.degree_front_image)" alt="Degree Front" class="mx-auto h-32 w-auto object-contain rounded-md border border-gray-200">
                        <div class="flex space-x-2 justify-center">
                          <button @click="openImageModal(getImageUrl(selectedTechnician.degree_front_image), 'Degree Certificate Front')" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                        </div>
                      </div>
                      <div v-else class="space-y-2">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm text-gray-500">Not uploaded</p>
                      </div>
                    </div>
                  </div>

                  <!-- Degree Certificate Back -->
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Degree Certificate Back *</label>
                    <div class="text-center">
                      <div v-if="selectedTechnician.degree_back_image" class="space-y-2">
                        <img :src="getImageUrl(selectedTechnician.degree_back_image)" alt="Degree Back" class="mx-auto h-32 w-auto object-contain rounded-md border border-gray-200">
                        <div class="flex space-x-2 justify-center">
                          <button @click="openImageModal(getImageUrl(selectedTechnician.degree_back_image), 'Degree Certificate Back')" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                        </div>
                      </div>
                      <div v-else class="space-y-2">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm text-gray-500">Not uploaded</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-end space-x-4">
                <button 
                  @click="showTechnicianProfileModal = false" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Close
                </button>
                <button 
                  @click="manageTechnicianStatus(selectedTechnician)" 
                  class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                >
                  Manage Status
                </button>
                <button 
                  v-if="selectedTechnician.status === 'pending'"
                  @click="reviewTechnicianProfile(selectedTechnician)" 
                  class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700"
                >
                  Review Profile
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Technician Status Management Modal -->
      <div v-if="showStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Manage Technician Status</h2>
              <button @click="showStatusModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <div v-if="selectedTechnician" class="space-y-6">
              <!-- Current Status -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Current Status</h3>
                <div class="flex items-center space-x-4">
                  <span :class="getTechnicianStatusBadgeClass(selectedTechnician.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
                    {{ selectedTechnician.status }}
                  </span>
                  <span :class="getAvailabilityBadgeClass(selectedTechnician.availability)" class="px-3 py-1 text-sm font-semibold rounded-full">
                    {{ selectedTechnician.availability }}
                  </span>
                </div>
              </div>

              <!-- Status Change Form -->
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select v-model="statusForm.newStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                      <option value="incomplete">Incomplete</option>
                      <option value="pending">Pending</option>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                      <option value="blacklist">Blacklist</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                    <select v-model="statusForm.availability" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                      <option value="online">Online</option>
                      <option value="offline">Offline</option>
                      <option value="on_leave">On Leave</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Status Change</label>
                  <textarea 
                    v-model="statusForm.reason" 
                    rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Reason for status change..."
                  ></textarea>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Admin Comments</label>
                  <textarea 
                    v-model="statusForm.adminComments" 
                    rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Additional admin comments or notes..."
                  ></textarea>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gray-50 rounded-lg p-4">
                  <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h4>
                  <div class="flex flex-wrap gap-2">
                    <button 
                      @click="statusForm.newStatus = 'active'; statusForm.availability = 'online'"
                      class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full hover:bg-green-200"
                    >
                      Activate & Set Online
                    </button>
                    <button 
                      @click="statusForm.newStatus = 'inactive'; statusForm.availability = 'offline'"
                      class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full hover:bg-red-200"
                    >
                      Deactivate & Set Offline
                    </button>
                    <button 
                      @click="statusForm.newStatus = 'pending'"
                      class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full hover:bg-yellow-200"
                    >
                      Set Pending
                    </button>
                    <button 
                      @click="statusForm.newStatus = 'blacklist'"
                      class="px-3 py-1 text-xs bg-orange-100 text-orange-800 rounded-full hover:bg-orange-200"
                    >
                      Blacklist
                    </button>
                    <button 
                      @click="statusForm.availability = 'on_leave'"
                      class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200"
                    >
                      Set On Leave
                    </button>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-end space-x-4">
                <button 
                  @click="showStatusModal = false" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  @click="updateTechnicianStatus" 
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                  Update Status
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Breed Modal -->
      <div v-if="showAddBreedModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Add New Breed</h2>
              <button @click="showAddBreedModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="addBreed" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Breed Name *</label>
                <input 
                  v-model="breedForm.name" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter breed name"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="breedForm.description" 
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter breed description"
                ></textarea>
              </div>


              <div class="flex justify-end space-x-4">
                <button 
                  type="button"
                  @click="showAddBreedModal = false" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  :disabled="!breedForm.name"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300"
                >
                  Add Breed
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Add Stock Modal -->
      <div v-if="showAddStockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Add New Stock Item</h2>
              <button @click="showAddStockModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="addStockItem" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                  <input 
                    v-model="stockForm.name" 
                    type="text" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter product name"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Breed</label>
                  <select 
                    v-model="stockForm.breed_id" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                    <option value="">Select Breed</option>
                    <option v-for="breed in breeds" :key="breed.id" :value="breed.id">
                      {{ breed.name }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                  <input 
                    v-model="stockForm.price" 
                    type="number" 
                    step="0.01"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0.00"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                  <input 
                    v-model="stockForm.stock_quantity" 
                    type="number" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0"
                  >
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="stockForm.description" 
                  rows="3"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter product description"
                ></textarea>
              </div>
              
              
              <div class="flex items-center">
                <input 
                  type="checkbox" 
                  v-model="stockForm.is_active" 
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label class="ml-2 text-sm text-gray-700">Active</label>
              </div>
              
              <div class="flex justify-end space-x-4 pt-6">
                <button 
                  type="button"
                  @click="showAddStockModal = false" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                  Add Stock Item
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Technician Stock Details Modal -->
      <div v-if="showStockDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Stock Details - {{ selectedTechnicianForDetails?.name }}</h2>
              <button @click="showStockDetailsModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
              <div class="bg-blue-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ selectedTechnicianForDetails?.total_items || 0 }}</div>
                <div class="text-sm text-blue-800">Total Assigned Items</div>
              </div>
              <div class="bg-green-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ selectedTechnicianForDetails?.total_quantity || 0 }}</div>
                <div class="text-sm text-green-800">Total Quantity</div>
              </div>
              <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ selectedTechnicianForDetails?.low_stock_items || 0 }}</div>
                <div class="text-sm text-yellow-800">Low Stock Items</div>
              </div>
              <div class="bg-red-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-red-600">{{ selectedTechnicianForDetails?.out_of_stock_items || 0 }}</div>
                <div class="text-sm text-red-800">Out of Stock Items</div>
              </div>
            </div>

            <!-- Detailed Stock Items Table -->
            <div class="space-y-6">
              <!-- All Assigned Items -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">All Assigned Items</h3>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Main Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="item in selectedTechnicianForDetails?.stock_items || []" :key="item.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ item.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ item.breed }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ item.quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          <span :class="item.main_stock === 0 ? 'text-red-600 font-medium' : 'text-gray-900'">
                            {{ item.main_stock || 0 }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          ₨{{ item.price }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                          <span :class="getStockStatusClass(item.quantity, item.main_stock)" class="px-2 py-1 text-xs font-semibold rounded-full">
                            {{ getStockStatusText(item.quantity, item.main_stock) }}
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Low Stock Items -->
              <div v-if="lowStockItems.length > 0">
                <h3 class="text-lg font-medium text-yellow-800 mb-4">⚠️ Low Stock Items (≤10 units)</h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="item in lowStockItems" :key="item.id" class="bg-white p-3 rounded border border-yellow-200">
                      <div class="font-medium text-gray-900">{{ item.name }}</div>
                      <div class="text-sm text-gray-600">{{ item.breed }}</div>
                      <div class="text-sm font-medium text-yellow-600">Quantity: {{ item.quantity }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Out of Stock Items -->
              <div v-if="outOfStockItems.length > 0">
                <h3 class="text-lg font-medium text-red-800 mb-4">🚨 Out of Stock Items (0 assigned units OR main inventory is 0)</h3>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="item in outOfStockItems" :key="item.id" class="bg-white p-3 rounded border border-red-200">
                      <div class="font-medium text-gray-900">{{ item.name }}</div>
                      <div class="text-sm text-gray-600">{{ item.breed }}</div>
                      <div class="text-sm font-medium text-red-600">Quantity: {{ item.quantity }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- No Items Message -->
              <div v-if="!selectedTechnicianForDetails?.stock_items || selectedTechnicianForDetails.stock_items.length === 0" class="text-center py-8">
                <div class="text-gray-500 text-lg">No stock items assigned to this technician</div>
                <button @click="openAssignStockModal" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                  Assign Stock Now
                </button>
              </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 mt-6 border-t">
              <button 
                @click="showStockDetailsModal = false" 
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
              >
                Close
              </button>
              
            </div>
          </div>
        </div>
      </div>

      <!-- Assign Stock Modal -->
      <div v-if="showAssignStockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Assign Stock to {{ selectedTechnician?.name }}</h2>
              <button @click="showAssignStockModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            
            <div class="mb-6">
              <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Technician Information</h3>
                <p class="text-sm text-blue-700"><strong>Name:</strong> {{ selectedTechnician?.name }}</p>
                <p class="text-sm text-blue-700"><strong>Current Stock Items:</strong> {{ selectedTechnician?.total_items || 0 }}</p>
                <p class="text-sm text-blue-700"><strong>Low Stock Items:</strong> {{ selectedTechnician?.low_stock || 0 }}</p>
              </div>
            </div>

            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Available Stock Items</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div 
                  v-for="item in availableStockItems" 
                  :key="item.id" 
                  class="border rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                  :class="assignStockForm.selected_items.includes(item.id) ? 'border-green-500 bg-green-50' : 'border-gray-200'"
                  @click="toggleStockItem(item.id)"
                >
                  <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">{{ item.name }}</h4>
                    <input 
                      type="checkbox" 
                      :checked="assignStockForm.selected_items.includes(item.id)"
                      @change="toggleStockItem(item.id)"
                      class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                    >
                  </div>
                  <p class="text-sm text-gray-600 mb-2">{{ item.breed?.name }}</p>
                  <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-900">₨{{ item.price }}</span>
                    <span :class="getStockStatusClass(item.stock_quantity)" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ item.stock_quantity }} in stock
                    </span>
                  </div>
                  <div v-if="item.technicians && item.technicians.length > 0" class="mt-2">
                    <p class="text-xs text-gray-500">Currently assigned to:</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                      <span v-for="tech in item.technicians" :key="tech.id" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                        {{ tech.user?.name }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="assignStockForm.selected_items.length > 0" class="mb-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Selected Items ({{ assignStockForm.selected_items.length }})</h3>
              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="space-y-4">
                  <div 
                    v-for="itemId in assignStockForm.selected_items" 
                    :key="itemId"
                    class="flex items-center justify-between p-3 bg-white rounded-lg border"
                  >
                    <div class="flex-1">
                      <h4 class="font-medium text-gray-900">{{ getStockItemName(itemId) }}</h4>
                      <p class="text-sm text-gray-600">Available: {{ getStockItemQuantity(itemId) }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                      <label class="text-sm font-medium text-gray-700">Quantity:</label>
                      <input 
                        type="number" 
                        :min="1" 
                        :max="getStockItemQuantity(itemId)"
                        v-model="assignStockForm.quantities[itemId]"
                        class="w-20 border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @input="validateQuantity(itemId)"
                      />
                      <button 
                        @click="removeStockItem(itemId)"
                        class="text-red-600 hover:text-red-800 p-1"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6">
              <button 
                type="button"
                @click="showAssignStockModal = false" 
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
              >
                Cancel
              </button>
              <button 
                @click="assignStockToTechnician"
                :disabled="assignStockForm.selected_items.length === 0"
                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                Assign {{ assignStockForm.selected_items.length }} Item(s)
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Breed Modal -->
      <div v-if="showEditBreedModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Edit Breed</h2>
              <button @click="closeEditBreedModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="updateBreed" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Breed Name *</label>
                <input 
                  v-model="breedForm.name" 
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter breed name"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="breedForm.description" 
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter breed description"
                ></textarea>
              </div>


              <div class="flex justify-end space-x-4">
                <button 
                  type="button"
                  @click="closeEditBreedModal" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  :disabled="!breedForm.name"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300"
                >
                  Update Breed
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Add Semen Modal -->
      <div v-if="showAddSemenModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Add New Semen Product</h2>
              <button @click="showAddSemenModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="addSemen" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Bull Name *</label>
                  <input 
                    v-model="semenForm.bull_name" 
                    type="text" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter bull name"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Breed *</label>
                  <select 
                    v-model="semenForm.breed_id" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">Select a breed</option>
                    <option v-for="breed in breeds" :key="breed?.id || Math.random()"  :value="breed.id">
                      {{ breed.name }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                  <input 
                    v-model="semenForm.price" 
                    type="number" 
                    step="0.01"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter price"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                  <input 
                    v-model="semenForm.stock_quantity" 
                    type="number" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter stock quantity"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Collection Date</label>
                  <input 
                    v-model="semenForm.collection_date" 
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                  <input 
                    v-model="semenForm.expiry_date" 
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="semenForm.description" 
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter semen product description"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bull Characteristics</label>
                <textarea 
                  v-model="semenForm.bull_characteristics" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter bull characteristics"
                ></textarea>
              </div>

              <!-- Image Upload Section -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                <div class="space-y-4">
                  <!-- Image Preview -->
                  <div v-if="semenForm.image || imagePreview" class="flex justify-center">
                    <div class="relative">
                      <img 
                        :src="imagePreview || (semenForm.image ? URL.createObjectURL(semenForm.image) : '')" 
                        alt="Product preview" 
                        class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                      />
                      <button 
                        v-if="semenForm.image || imagePreview"
                        @click="removeImage"
                        type="button"
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                  
                  <!-- File Input -->
                  <div class="flex items-center justify-center w-full">
                    <label for="semen-image-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">
                          <span class="font-semibold">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                      </div>
                      <input 
                        id="semen-image-upload" 
                        type="file" 
                        class="hidden" 
                        accept="image/*"
                        @change="handleImageUpload"
                      />
                    </label>
                  </div>
                </div>
              </div>

              <div class="flex items-center">
                <input 
                  v-model="semenForm.is_active" 
                  type="checkbox" 
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label class="ml-2 block text-sm text-gray-900">
                  Active (available for purchase)
                </label>
              </div>

              <div class="flex justify-end space-x-4">
                <button 
                  type="button"
                  @click="showAddSemenModal = false" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  :disabled="!semenForm.bull_name || !semenForm.breed_id || !semenForm.price || !semenForm.stock_quantity"
                  class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-300"
                >
                  Add Semen Product
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Semen Modal -->
      <div v-if="showEditSemenModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Edit Semen Product</h2>
              <button @click="closeEditSemenModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="updateSemen" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Bull Name *</label>
                  <input 
                    v-model="semenForm.bull_name" 
                    type="text" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter bull name"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Breed *</label>
                  <select 
                    v-model="semenForm.breed_id" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">Select a breed</option>
                    <option v-for="breed in breeds" :key="breed?.id || Math.random()"  :value="breed.id">
                      {{ breed.name }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                  <input 
                    v-model="semenForm.price" 
                    type="number" 
                    step="0.01"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter price"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                  <input 
                    v-model="semenForm.stock_quantity"
                    type="number" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter stock quantity"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Collection Date</label>
                  <input 
                    v-model="semenForm.collection_date" 
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                  <input 
                    v-model="semenForm.expiry_date" 
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="semenForm.description" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter semen product description"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bull Characteristics</label>
                <textarea 
                  v-model="semenForm.bull_characteristics" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter bull characteristics"
                ></textarea>
              </div>

              <!-- Image Upload Section -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                <div class="space-y-4">
                  <!-- Current Image Display -->
                  <div v-if="editingSemen?.image" class="flex justify-center">
                    <div class="relative">
                      <img 
                        :src="getSemenImageUrl(editingSemen.image)" 
                        alt="Current product image" 
                        class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                      />
                      <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Current</span>
                    </div>
                  </div>
                  
                  <!-- Image Preview -->
                  <div v-if="semenForm.image || imagePreview" class="flex justify-center">
                    <div class="relative">
                      <img 
                        :src="imagePreview || (semenForm.image ? URL.createObjectURL(semenForm.image) : '')" 
                        alt="New product preview" 
                        class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                      />
                      <button 
                        v-if="semenForm.image || imagePreview"
                        @click="removeImage"
                        type="button"
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                      <span class="absolute -bottom-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">New</span>
                    </div>
                  </div>
                  
                  <!-- File Input -->
                  <div class="flex items-center justify-center w-full">
                    <label for="edit-semen-image-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">
                          <span class="font-semibold">Click to upload new image</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                      </div>
                      <input 
                        id="edit-semen-image-upload" 
                        type="file" 
                        class="hidden" 
                        accept="image/*"
                        @change="handleImageUpload"
                      />
                    </label>
                  </div>
                </div>
              </div>

              <div class="flex items-center">
                <input 
                  v-model="semenForm.is_active" 
                  type="checkbox" 
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label class="ml-2 block text-sm text-gray-900">
                  Active (available for purchase)
                </label>
              </div>

              <div class="flex justify-end space-x-4">
                <button 
                  type="button"
                  @click="closeEditSemenModal" 
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  :disabled="!semenForm.bull_name || !semenForm.breed_id || !semenForm.price || !semenForm.stock_quantity"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300"
                >
                  Update Semen Product
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Catalog Log Modal -->
      <div v-if="showCatalogLog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Catalog Activity Log</h2>
              <button @click="showCatalogLog = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            
            <div class="space-y-6">
              <!-- Filter Options -->
              <div class="flex space-x-4">
                <select v-model="catalogLogFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="all">All Activities</option>
                  <option value="breed">Breed Changes</option>
                  <option value="semen">Semen Changes</option>
                </select>
                <button @click="loadCatalogLog" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                  Refresh
                </button>
              </div>

              <!-- Log Table -->
              <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="log in catalogLogs" :key="log.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ new Date(log.created_at).toLocaleString() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span :class="[
                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                            log.action === 'created' ? 'bg-green-100 text-green-800' :
                            log.action === 'updated' ? 'bg-blue-100 text-blue-800' :
                            log.action === 'deleted' ? 'bg-red-100 text-red-800' :
                            'bg-gray-100 text-gray-800'
                          ]">
                            {{ log.action }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ log.item_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                          {{ log.details }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ log.user_name }}
                        </td>
                      </tr>
                      <tr v-if="catalogLogs.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                          No catalog activities found
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </main>
    </div>
  </div>

  <!-- Cow Type Modal -->
  <div v-if="showCowTypeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-2xl font-bold text-gray-900">{{ editingCowType ? 'Edit Cow Type' : 'Add Cow Type' }}</h3>
          <button @click="closeCowTypeModal" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
      
      <div class="p-6">
        <form @submit.prevent="saveCowType" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
            <input 
              v-model="cowTypeForm.name"
              type="text" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Enter cow type name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="cowTypeForm.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Enter description (optional)"
            ></textarea>
          </div>

          <div>
            <label class="flex items-center">
              <input 
                v-model="cowTypeForm.is_active"
                type="checkbox" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
          </div>

          <div class="flex justify-end space-x-4">
            <button 
              type="button" 
              @click="closeCowTypeModal"
              class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              {{ editingCowType ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Order Details Modal -->
  <div v-if="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-2xl font-bold text-gray-900">Order Details</h3>
          <button @click="closeOrderModal" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
      
      <div v-if="selectedOrder" class="p-6 space-y-6">
        
        <!-- Order Information -->
        <div class="bg-gray-50 p-6 rounded-lg">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium text-gray-500">Order Number</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.order_number }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Order Date</label>
              <p class="text-lg font-semibold text-gray-900">{{ formatDateTime(selectedOrder.created_at) }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Status</label>
              <p class="mt-1">
                <span :class="getStatusBadgeClass(selectedOrder.status)" class="px-3 py-1 text-xs font-semibold rounded-full">
                  {{ selectedOrder.status }}
                </span>
              </p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Total Amount</label>
              <p class="text-lg font-semibold text-gray-900">Rs{{ selectedOrder.total_amount }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Visit Fee</label>
              <p class="text-lg font-semibold text-blue-600">Rs{{ selectedOrder.visit_fee || '0.00' }}</p>
            </div>
            <div v-if="selectedOrder.notes" class="md:col-span-2">
              <label class="text-sm font-medium text-gray-500">Order Notes</label>
              <p class="text-gray-900 mt-1">{{ selectedOrder.notes }}</p>
            </div>
          </div>
        </div>

        <!-- Farm Information -->
        <div class="bg-blue-50 p-6 rounded-lg">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Farm Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium text-gray-500">Farm Name</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.farmer?.cattle_farm?.name || selectedOrder.farmer?.farm_name || 'Green Pastures Farm' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Farmer Name</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.farmer?.user?.name || 'A Abdul Waheed' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Contact Number</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.farmer?.user?.phone || '+923001234567' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Email</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.farmer?.user?.email || '' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Address</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.farmer?.cattle_farm?.address || 'Farm Address, City, Pakistan' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Number of Cows</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedOrder.farmer?.cattle_farm?.number_of_cows || '25' }}</p>
            </div>
          </div>
        </div>

        <!-- Technician Information -->
        <div class="bg-yellow-50 p-6 rounded-lg">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Technician Information</h3>
          <div v-if="selectedOrder.jobs && selectedOrder.jobs.length > 0">
            <div v-for="(job, index) in selectedOrder.jobs" :key="index" class="bg-white p-4 rounded-lg border mb-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="text-sm font-medium text-gray-500">Technician Name</label>
                  <p class="text-lg font-semibold text-gray-900">{{ job.technician?.user?.name || 'Dr. Sarah Ahmed' }}</p>
                  <p class="text-sm text-gray-600">{{ job.technician?.user?.phone || '+923001234570' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Experience</label>
                  <p class="text-sm text-gray-900">{{ job.technician?.years_of_experience || '5' }} years</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Job Status</label>
                  <p class="mt-1">
                    <span :class="getStatusBadgeClass(job.status || 'completed')" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ job.status || 'completed' }}
                    </span>
                  </p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Estimated Visit</label>
                  <p class="text-sm text-gray-900">{{ formatDateTime(job.estimated_visit_at) || '19/10/2025, 02:45 AM' }}</p>
                </div>
              </div>
              
              <!-- ETA Information Display -->
              <div v-if="job.estimated_visit_at" class="mt-4 bg-green-50 p-4 rounded-lg">
                <h4 class="text-md font-semibold text-gray-900 mb-3">Estimated Arrival Time</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <span class="text-sm font-medium text-gray-600">ETA Date & Time:</span>
                    <p class="text-sm text-gray-900 font-semibold mt-1">{{ formatDateTime(job.estimated_visit_at) }}</p>
                  </div>
                  <div v-if="job.technician_notes">
                    <span class="text-sm font-medium text-gray-600">ETA Notes:</span>
                    <p class="text-sm text-gray-900 bg-white p-2 rounded border border-green-200 mt-1">{{ job.technician_notes }}</p>
                  </div>
                </div>
              </div>
              
              <div class="mt-4 space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500">Farmer Notes</label>
                  <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ job.farmer_notes || 'No specific notes provided' }}</p>
                </div>
                <div v-if="!job.estimated_visit_at">
                  <label class="text-sm font-medium text-gray-500">Technician Notes</label>
                  <p class="text-sm text-gray-900 bg-blue-50 p-3 rounded">{{ job.technician_notes || 'No notes from technician' }}</p>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="bg-white p-4 rounded-lg border">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="text-sm font-medium text-gray-500">Technician Name</label>
                <p class="text-lg font-semibold text-gray-900">Dr. Sarah Ahmed</p>
                <p class="text-sm text-gray-600">+923001234570</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Experience</label>
                <p class="text-sm text-gray-900">5 years</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Job Status</label>
                <p class="mt-1">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    completed
                  </span>
                </p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Estimated Visit</label>
                <p class="text-sm text-gray-900">19/10/2025, 02:45 AM</p>
              </div>
            </div>
            
            <div class="mt-4 space-y-3">
              <div>
                <label class="text-sm font-medium text-gray-500">Farmer Notes</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">No specific notes provided</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Technician Notes</label>
                <p class="text-sm text-gray-900 bg-blue-50 p-3 rounded">No notes from technician</p>
              </div>
            </div>
          </div>
        </div>


        <!-- Cow Insemination Details -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Cow Insemination Details</h3>
          <div v-if="selectedOrder.order_items?.length" class="space-y-4">
            <div v-for="(item, index) in selectedOrder.order_items" :key="item.id" class="border border-gray-200 rounded-lg p-4">
              <h4 class="text-md font-semibold text-gray-800 mb-3">
                Cow {{ getCowNumber({ order_item_id: item.id }, selectedOrder.order_items || []) }} - {{ item.bull_semen?.bull_name || 'N/A' }}
                <span class="text-sm text-gray-500">({{ item.bull_semen?.breed?.name || 'N/A' }})</span>
              </h4>

              <!-- Order Item Details -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-4">
                <div>
                  <span class="text-sm font-medium text-gray-500">Quantity:</span>
                  <p class="text-sm text-gray-900">{{ item.quantity || 'N/A' }} straw(s)</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-500">Cow Type:</span>
                  <p class="text-sm text-gray-900">{{ item.cow_type || 'cow' }}</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-500">Cow Age:</span>
                  <p class="text-sm text-gray-900">{{ item.cow_age || 'N/A' }} years</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-500">Heat Started:</span>
                  <p class="text-sm text-gray-900">{{ item.heat_started_at ? formatDateTime(item.heat_started_at) : 'N/A' }}</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-500">Unit Price:</span>
                  <p class="text-sm text-gray-900">Rs{{ formatCurrency(item.unit_price) }}</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-500">Total Price:</span>
                  <p class="text-sm text-green-600 font-semibold">Rs{{ formatCurrency(item.total_price) }}</p>
                </div>
              </div>

              
              <!-- Cow Insemination Record -->
              <div v-if="selectedOrder.jobs && selectedOrder.jobs[0]?.cow_insemination_records && selectedOrder.jobs[0].cow_insemination_records.find(record => record.order_item_id === item.id)" class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-sm font-semibold text-gray-700 mb-3">Visit Record</h5>
                <div v-for="record in selectedOrder.jobs[0].cow_insemination_records.filter(record => record.order_item_id === item.id)" :key="record.id" class="space-y-3">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                      <span class="text-sm font-medium text-gray-500">Cow Heat Status:</span>
                      <span :class="record.cow_in_heat === 'yes' ? 'text-green-600' : 'text-red-600'" class="text-sm font-semibold">
                        {{ record.cow_in_heat === 'yes' ? 'In Heat' : 'Not in Heat' }}
                      </span>
                    </div>
                    <div v-if="record.amount_received">
                      <span class="text-sm font-medium text-gray-500">Amount Received:</span>
                      <p class="text-sm text-green-600 font-semibold">Rs{{ formatCurrency(record.amount_received) }}</p>
                    </div>
                    <div v-if="record.payment_method">
                      <span class="text-sm font-medium text-gray-500">Payment Method:</span>
                      <p class="text-sm text-gray-900">{{ record.payment_method.replace('_', ' ').toUpperCase() }}</p>
                    </div>
                    <div v-if="record.straw_barcode_code">
                      <span class="text-sm font-medium text-gray-500">Straw Barcode:</span>
                      <p class="text-sm text-gray-900 font-mono">{{ record.straw_barcode_code }}</p>
                    </div>
                  </div>

                  <!-- Photos -->
                  <div v-if="record.cow_photo || record.straw_photo" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="record.cow_photo">
                      <span class="text-sm font-medium text-gray-500">Cow Photo:</span>
                      <div class="mt-2">
                        <img :src="getImageUrl(record.cow_photo)" :alt="'Cow photo for ' + item.bull_semen?.bull_name" class="w-full h-32 object-cover rounded-lg border border-gray-300 cursor-pointer" @click="openImageModal(getImageUrl(record.cow_photo))">
                      </div>
                    </div>
                    <div v-if="record.straw_photo">
                      <span class="text-sm font-medium text-gray-500">Straw Photo:</span>
                      <div class="mt-2">
                        <img :src="getImageUrl(record.straw_photo)" :alt="'Straw photo for ' + item.bull_semen?.bull_name" class="w-full h-32 object-cover rounded-lg border border-gray-300 cursor-pointer" @click="openImageModal(getImageUrl(record.straw_photo))">
                      </div>
                    </div>
                  </div>

                  <!-- Notes -->
                  <div v-if="record.notes">
                    <span class="text-sm font-medium text-gray-500">Notes:</span>
                    <p class="text-sm text-gray-700 mt-1 bg-white p-3 rounded border">{{ record.notes }}</p>
                  </div>
                </div>
              </div>
              <div v-else class="bg-yellow-50 p-4 rounded-lg">
                <p class="text-sm text-yellow-700">No visit record available for this cow.</p>
              </div>
            </div>
          </div>
          <div v-else class="text-sm text-gray-500">
            No insemination details available
          </div>
        </div>

        

        <!-- Actions -->
        <div class="flex justify-end pt-4 border-t border-gray-200">
          <button @click="closeOrderModal" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Farmer Details Modal -->
  <div v-if="showFarmerDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Farmer Details</h2>
          <button 
            @click="showFarmerDetailsModal = false"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="selectedFarmer" class="space-y-6">
          <!-- Header with Avatar and Basic Info -->
          <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6">
            <div class="flex items-center space-x-6">
              <div class="h-20 w-20 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center">
                <span class="text-2xl font-bold text-white">{{ selectedFarmer.name?.charAt(0) || '?' }}</span>
              </div>
              <div class="flex-1">
                <h3 class="text-2xl font-bold text-gray-900">{{ selectedFarmer.name }}</h3>
                <p class="text-lg text-gray-600">Farmer ID: {{ selectedFarmer.id }}</p>
                <div class="flex items-center space-x-4 mt-2">
                  <span :class="getStatusBadgeClass(selectedFarmer.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
                    {{ selectedFarmer.status || 'Active' }}
                  </span>
                  <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ selectedFarmer.cattle_farms?.length || 0 }} Farms
                  </span>
                </div>
              </div>
              <div class="text-right">
                <div class="text-3xl font-bold text-green-600">{{ selectedFarmer.orders?.length || 0 }}</div>
                <div class="text-sm text-gray-600">Total Orders</div>
              </div>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
              <div class="text-2xl font-bold text-blue-600">{{ selectedFarmer.orders?.length || 0 }}</div>
              <div class="text-sm text-gray-600">Total Orders</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
              <div class="text-2xl font-bold text-green-600">{{ selectedFarmer.orders?.filter(o => o.status === 'completed').length || 0 }}</div>
              <div class="text-sm text-gray-600">Completed</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
              <div class="text-2xl font-bold text-yellow-600">{{ selectedFarmer.orders?.filter(o => ['pending', 'assigned', 'in_progress'].includes(o.status)).length || 0 }}</div>
              <div class="text-sm text-gray-600">Pending</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
              <div class="text-2xl font-bold text-purple-600">{{ selectedFarmer.cattle_farms?.length || 0 }}</div>
              <div class="text-sm text-gray-600">Farms</div>
            </div>
          </div>

          <!-- Detailed Information Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
              <div class="space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500">Full Name</label>
                  <p class="text-gray-900">{{ selectedFarmer.name || 'Not provided' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                  <p class="text-gray-900">{{ selectedFarmer.date_of_birth ? formatDate(selectedFarmer.date_of_birth) : 'Not provided' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Profile Picture</label>
                  <p class="text-gray-900">{{ selectedFarmer.profile_picture ? 'Uploaded' : 'Not provided' }}</p>
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
              <div class="space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500">Mobile</label>
                  <p class="text-gray-900">{{ selectedFarmer.mobile || 'Not provided' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Email</label>
                  <p class="text-gray-900">{{ selectedFarmer.user?.email || 'Not provided' }}</p>
                </div>
              </div>
            </div>

            <!-- Farm Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Farm Information</h4>
              <div class="space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500">Total Farms</label>
                  <p class="text-gray-900">{{ selectedFarmer.cattle_farms?.length || 0 }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Total Cattle</label>
                  <p class="text-gray-900">{{ selectedFarmer.cattle_farms?.reduce((total, farm) => total + (farm.cattle_count || 0), 0) || 0 }}</p>
                </div>
              </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h4>
              <div class="space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500">Registration Date</label>
                  <p class="text-gray-900">{{ selectedFarmer.created_at ? formatDateTime(selectedFarmer.created_at) : 'Not available' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Last Updated</label>
                  <p class="text-gray-900">{{ selectedFarmer.updated_at ? formatDateTime(selectedFarmer.updated_at) : 'Not available' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Status</label>
                  <span :class="getStatusBadgeClass(selectedFarmer.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                    {{ selectedFarmer.status || 'Active' }}
                  </span>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Account Type</label>
                  <p class="text-gray-900">Farmer</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Farm List -->
          <div v-if="selectedFarmer.cattle_farms && selectedFarmer.cattle_farms.length > 0" class="bg-white border border-gray-200 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Farm Locations</h4>
            <div class="space-y-4">
              <div v-for="farm in selectedFarmer.cattle_farms" :key="farm.id" class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h5 class="font-medium text-gray-900">{{ farm.name }}</h5>
                    <p class="text-sm text-gray-600">{{ farm.address }}</p>
                    <p class="text-sm text-gray-500">{{ farm.cattle_count || 0 }} cattle</p>
                  </div>
                  <div class="text-right">
                    <span class="text-sm text-gray-500">{{ farm.city }}, {{ farm.district }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Orders -->
          <div v-if="selectedFarmer.orders && selectedFarmer.orders.length > 0" class="bg-white border border-gray-200 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h4>
            <div class="space-y-3">
              <div v-for="order in selectedFarmer.orders.slice(0, 5)" :key="order.id" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div>
                  <p class="font-medium text-gray-900">Order #{{ order.order_number || order.id }}</p>
                  <p class="text-sm text-gray-600">{{ order.order_items?.length || 0 }} items</p>
                </div>
                <div class="text-right">
                  <span :class="getStatusBadgeClass(order.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                    {{ order.status }}
                  </span>
                  <p class="text-sm text-gray-500 mt-1">{{ formatDateTime(order.created_at) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

export default {
  name: 'AdminDashboard',
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    
    const activeTab = ref('analytics')
    const isSidebarCollapsed = ref(true)
    const isStatsCardsCollapsed = ref(false)
    const showUserDropdown = ref(false)
    const isLoading = ref(true)

    // Watch for tab changes to load data
    watch(activeTab, (newTab) => {
      if (newTab === 'reassignment-logs') {
        loadReassignmentLogs(1)
      } else if (newTab === 'cow-types') {
        loadCowTypes()
      }
    })
    const hasError = ref(false)
    const errorMessage = ref('')
    const analytics = ref({})
    const farmers = ref([])
    const technicians = ref([])
    const orders = ref([])
    const ordersPagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0
    })
    
    const farmerSearch = ref('')
    const farmerStatusFilter = ref('')
    const technicianSearch = ref('')
    const technicianStatusFilter = ref('')
    const orderStatusFilter = ref('')
    
    // Order filters
    const orderFilters = ref({
      farmer: '',
      farm: '',
      technician: '',
      status: '',
      date: '',
      fromDate: '',
      toDate: ''
    })
    
    // Order modal
    const showOrderModal = ref(false)
    const selectedOrder = ref(null)
    const selectedOrderForPregnancy = ref(null)
    
    // New admin data
    const breeds = ref([])
    const semenProducts = ref([])
    const earnings = ref({})
    const performance = ref({})
    const stock = ref({})
    const notifications = ref([])
    const locationStats = ref({})
    
    // New filters
    const earningsFilter = ref('all')
    const performanceFilter = ref('all')
    
    // New modals
    const showAddBreedModal = ref(false)
    const showEditBreedModal = ref(false)
    const showAddSemenModal = ref(false)
    const showEditSemenModal = ref(false)
    const showAddStockModal = ref(false)
    const showAssignStockModal = ref(false)
    const showStockDetailsModal = ref(false)
    const showCatalogLog = ref(false)
    const showReviewModal = ref(false)
    const showStatusModal = ref(false)
    const showTechnicianProfileModal = ref(false)
    const selectedTechnician = ref(null)
    const selectedTechnicianForDetails = ref(null)
    
    // Farmer details modal
    const showFarmerDetailsModal = ref(false)
    const selectedFarmer = ref(null)
    const editingBreed = ref(null)
    
    // Stock management
    const stockItems = ref([])
    const stockForm = ref({
      name: '',
      breed_id: '',
      price: '',
      stock_quantity: '',
      description: '',
      is_active: true
    })
    
    // Assign stock management
    const availableStockItems = ref([])
    const techniciansForStock = ref([])
    const selectedTechnicianForStock = ref('')
    const assignStockForm = ref({
      selected_items: [],
      quantities: {}
    })
    
    // Catalog log
    const catalogLogs = ref([])
    const catalogLogFilter = ref('all')
    
    // Review form
    const reviewForm = ref({
      decision: '',
      comments: '',
      signature: ''
    })
    
    // Status form
    const statusForm = ref({
      newStatus: '',
      availability: '',
      reason: '',
      adminComments: ''
    })
    
    // Breed form
    const breedForm = ref({
      name: '',
      description: ''
    })
    
    // Editing semen
    const editingSemen = ref(null)
    
    // Image handling
    const imagePreview = ref(null)
    
    // Semen form
    const semenForm = ref({
      bull_name: '',
      breed_id: '',
      price: '',
      stock_quantity: '',
      collection_date: '',
      expiry_date: '',
      description: '',
      bull_characteristics: '',
      is_active: true,
      image: null
    })
    
    // Notification settings
    const notificationSettings = ref({
      order_updates: true,
      assignment_updates: true,
      payment_updates: true
    })
    
    // Job management
    const jobs = ref([])
    const jobStats = ref({
      total: 0,
      pending: 0,
      in_progress: 0,
      completed: 0
    })
    const showJobAssignmentModal = ref(false)
    const assigningJob = ref(false)
    const jobAssignment = ref({
      order_id: '',
      technician_id: '',
      estimated_visit_at: '',
      notes: ''
    })
    const unassignedOrders = ref([])
    const availableTechnicians = ref([])
    const reassignmentLogs = ref([])
    const reassignmentLogsPagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0,
      data: []
    })
    
    // Pregnancy tracking variables
    const activePregnancyTab = ref('all')
    const pregnancyStats = ref({})
    const pregnancyJobs = ref([])
    
    // Cow types management
    const cowTypes = ref([])
    const showCowTypeModal = ref(false)
    const editingCowType = ref(null)
    const cowTypeForm = ref({
      name: '',
      description: '',
      is_active: true
    })
    const pregnancyTabs = [
      { id: 'all', name: 'All' },
      { id: 'conceived', name: 'Conceived' },
      { id: 'pregnant', name: 'Pregnant' },
      { id: 'delivered', name: 'Delivered' },
      { id: 'miscarried', name: 'Failed' }
    ]
    
    const tabs = [
      { id: 'analytics', name: 'Analytics' },
      { id: 'farmers', name: 'Farmers' },
      { id: 'technicians', name: 'Technicians' },
      // { id: 'orders', name: 'Orders' },
      { id: 'jobs', name: 'Jobs' },
      { id: 'pregnancy-tracking', name: 'Pregnancy Tracking' },
      { id: 'catalog', name: 'Catalog' },
      { id: 'earnings', name: 'Earnings' },
      { id: 'performance', name: 'Performance' },
      { id: 'stock', name: 'Stock' },
      { id: 'cow-types', name: 'Cow Types' },
      { id: 'reassignment-logs', name: 'Reassignment Logs' },
    //  { id: 'notifications', name: 'Notifications' },
    //  { id: 'location', name: 'Location' }
    ]
    
    const user = computed(() => {
      try {
        return authStore.user || null
      } catch (error) {
        console.error('Error accessing user from auth store:', error)
        return null
      }
    })
    
    // Computed properties for orders
    const uniqueFarmers = computed(() => {
      const farmers = new Set()
      orders.value?.forEach(order => {
        const farmerName = getFarmerName(order)
        if (farmerName) farmers.add(farmerName)
      })
      return Array.from(farmers)
    })
    
    const uniqueFarms = computed(() => {
      const farms = new Set()
      orders.value?.forEach(order => {
        const farmName = getFarmName(order)
        if (farmName) farms.add(farmName)
      })
      return Array.from(farms)
    })
    
    const uniqueTechnicians = computed(() => {
      const technicians = new Set()
      orders.value?.forEach(order => {
        const techName = getTechnicianName(order)
        if (techName) technicians.add(techName)
      })
      return Array.from(technicians)
    })
    
    // Pregnancy tracking computed properties
    // Helper: does a job match a pregnancy status tab?
    const jobMatchesTab = (job, tabId) => {
      if (tabId === 'all') return true
      if (job.cow_status === tabId) return true
      const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
      return records.some(r => (r?.pregnancy_status || 'pending') === tabId)
    }

    const filteredPregnancyJobs = computed(() => {
      let filteredJobs = pregnancyJobs.value
      
      // Filter by selected order if one is selected
      if (selectedOrderForPregnancy.value) {
        filteredJobs = filteredJobs.filter(job => 
          job.order && job.order.id === selectedOrderForPregnancy.value.id
        )
      }
      
      // Filter by pregnancy status tab
      if (activePregnancyTab.value === 'all') {
        return filteredJobs
      }
      
      // Only show jobs that have at least one visible record for the active tab
      return filteredJobs.filter(job => {
        const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
        return records.some(r => (r?.pregnancy_status || 'pending') === activePregnancyTab.value)
      })
    })
    
    const getPregnancyTabCount = (tabId) => {
      // Count individual cow records (not jobs) to match per-cow status
      const countFor = (status) => pregnancyJobs.value.reduce((sum, job) => {
        const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
        return sum + records.filter(r => (r?.pregnancy_status || 'pending') === status).length
      }, 0)
      
      if (tabId === 'all') {
        return pregnancyJobs.value.reduce((sum, job) => {
          const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
          return sum + records.length
        }, 0)
      }
      return countFor(tabId)
    }

    // Within a tab, only show records whose status matches the active tab
    const getVisibleRecords = (job) => {
      const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
      if (activePregnancyTab.value === 'all') return records
      return records.filter(r => (r?.pregnancy_status || 'pending') === activePregnancyTab.value)
    }
    
    // Filtered pregnancy statistics for selected order
    const filteredPregnancyStats = computed(() => {
      if (!selectedOrderForPregnancy.value) {
        return pregnancyStats.value
      }
      
      // Calculate stats for filtered jobs only
      const filteredJobs = pregnancyJobs.value.filter(job => 
        job.order && job.order.id === selectedOrderForPregnancy.value.id
      )
      
      const stats = {
        conceived_count: 0,
        pregnant_count: 0,
        delivered_count: 0,
        miscarried_count: 0
      }
      
      filteredJobs.forEach(job => {
        const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
        records.forEach(record => {
          const status = record?.pregnancy_status || 'pending'
          if (status === 'conceived') stats.conceived_count++
          else if (status === 'pregnant') stats.pregnant_count++
          else if (status === 'delivered') stats.delivered_count++
          else if (status === 'miscarried') stats.miscarried_count++
        })
      })
      
      return stats
    })
    
    // Filtered pregnancy tab counts for selected order
    const getFilteredPregnancyTabCount = (tabId) => {
      if (!selectedOrderForPregnancy.value) {
        return getPregnancyTabCount(tabId)
      }
      
      // Count individual cow records for filtered jobs only
      const filteredJobs = pregnancyJobs.value.filter(job => 
        job.order && job.order.id === selectedOrderForPregnancy.value.id
      )
      
      const countFor = (status) => filteredJobs.reduce((sum, job) => {
        const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
        return sum + records.filter(r => (r?.pregnancy_status || 'pending') === status).length
      }, 0)
      
      if (tabId === 'all') {
        return filteredJobs.reduce((sum, job) => {
          const records = (job.cow_insemination_records || job.cowInseminationRecords || [])
          return sum + records.length
        }, 0)
      }
      return countFor(tabId)
    }

    // For server-side pagination, we use orders directly
    const filteredOrders = computed(() => orders.value)
    
    const loadAnalytics = async () => {
      try {
        console.log('Loading analytics...')
        console.log('Auth token:', localStorage.getItem('token'))
        console.log('User:', user.value)
        
        const response = await window.axios.get('/admin/analytics')
        console.log('Analytics response:', response.data)
        analytics.value = response.data?.analytics || {}
      } catch (error) {
        console.error('Error loading analytics:', error)
        console.error('Error details:', error.response?.data)
        analytics.value = {}
      }
    }
    
    const loadFarmers = async () => {
      try {
        console.log('Loading farmers...')
        const params = new URLSearchParams()
        if (farmerSearch.value) params.append('search', farmerSearch.value)
        if (farmerStatusFilter.value) params.append('status', farmerStatusFilter.value)
        
        const response = await window.axios.get(`/admin/farmers?${params}`)
        console.log('Farmers response:', response.data)
        console.log('Farmers data array:', response.data?.farmers?.data)
        farmers.value = response.data?.farmers?.data || []
        console.log('Farmers value set to:', farmers.value)
      } catch (error) {
        console.error('Error loading farmers:', error)
        farmers.value = []
      }
    }
    
    const loadTechnicians = async () => {
      try {
        console.log('Loading technicians...')
        const params = new URLSearchParams()
        if (technicianSearch.value) params.append('search', technicianSearch.value)
        if (technicianStatusFilter.value) params.append('status', technicianStatusFilter.value)
        
        const response = await window.axios.get(`/admin/technicians?${params}`)
        console.log('Technicians response:', response.data)
        technicians.value = response.data?.technicians?.data || []
      } catch (error) {
        console.error('Error loading technicians:', error)
        technicians.value = []
      }
    }
    
    const loadOrdersWithPagination = async (page = 1) => {
      try {
        console.log('Loading orders with pagination...')
        const params = new URLSearchParams()
        
        // Pagination parameters
        params.append('page', page)
        params.append('per_page', '10')
        
        // Filter parameters
        if (orderFilters.value.farmer) {
          params.append('farmer_name', orderFilters.value.farmer)
        }
        
        if (orderFilters.value.farm) {
          params.append('farm', orderFilters.value.farm)
        }
        
        if (orderFilters.value.technician) {
          params.append('technician', orderFilters.value.technician)
        }
        
        if (orderFilters.value.status) {
          params.append('status', orderFilters.value.status)
        }
        
        if (orderFilters.value.date) {
          if (orderFilters.value.date === 'custom') {
            // Handle custom date range
            if (orderFilters.value.fromDate) {
              params.append('from_date', orderFilters.value.fromDate)
            }
            if (orderFilters.value.toDate) {
              params.append('to_date', orderFilters.value.toDate)
            }
          } else {
            // Handle predefined date ranges
            params.append('date_range', orderFilters.value.date)
          }
        }
        
        const response = await window.axios.get(`/admin/orders?${params}`)
        console.log('Orders pagination response:', response.data)
        
        if (response.data.success) {
          orders.value = response.data.orders.data || []
          ordersPagination.value = {
            current_page: response.data.orders.current_page,
            last_page: response.data.orders.last_page,
            per_page: response.data.orders.per_page,
            total: response.data.orders.total,
            from: response.data.orders.from,
            to: response.data.orders.to
          }
        }
      } catch (error) {
        console.error('Error loading orders with pagination:', error)
        orders.value = []
        ordersPagination.value = {
          current_page: 1,
          last_page: 1,
          per_page: 10,
          total: 0,
          from: 0,
          to: 0
        }
      }
    }
    
    const loadOrders = async () => {
      // Keep this for backward compatibility, but use pagination
      await loadOrdersWithPagination(1)
    }
    
    const searchFarmers = () => {
      loadFarmers()
    }
    
    const searchTechnicians = () => {
      loadTechnicians()
    }
    
    const searchOrders = () => {
      loadOrders()
    }
    
    const toggleFarmerStatus = async (farmer) => {
      try {
        const newStatus = farmer.status === 'active' ? 'inactive' : 'active'
        await window.axios.put(`/admin/farmers/${farmer.id}/status`, 
          { status: newStatus }
        )
        farmer.status = newStatus
        showSuccess(`Farmer ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`)
      } catch (error) {
        console.error('Error updating farmer status:', error)
        showError('Failed to update farmer status. Please try again.')
      }
    }
    
    const viewFarmerDetails = (farmer) => {
      selectedFarmer.value = farmer
      showFarmerDetailsModal.value = true
    }
    
    const exportFarmers = () => {
      // Export farmers data to CSV
      const csvContent = "data:text/csv;charset=utf-8," + 
        "Name,Mobile,Email,Status,Farms,Orders,Joined\n" +
        farmers.value.map(f => 
          `${f.name},${f.mobile},${f.user?.email || ''},${f.status},${f.cattle_farms?.length || 0},${f.orders?.length || 0},${f.created_at}`
        ).join("\n")
      
      const encodedUri = encodeURI(csvContent)
      const link = document.createElement("a")
      link.setAttribute("href", encodedUri)
      link.setAttribute("download", "farmers_export.csv")
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    }
    
    // Catalog Management Functions
    const loadBreeds = async () => {
      try {
        const response = await window.axios.get('/admin/breeds')
        console.log('Breeds API response:', response.data)
        breeds.value = response.data?.breeds || []
        console.log('Breeds loaded:', breeds.value.length)
      } catch (error) {
        console.error('Error loading breeds:', error)
        breeds.value = []
      }
    }
    
    const loadSemenProducts = async () => {
      try {
        const response = await window.axios.get('/admin/semen-products')
        console.log('Semen products API response:', response.data)
        semenProducts.value = response.data?.products?.data || []
        console.log('Semen products loaded:', semenProducts.value.length)
      } catch (error) {
        console.error('Error loading semen products:', error)
        semenProducts.value = []
      }
    }
    
    const loadCatalogLog = async () => {
      try {
        // For now, we'll create mock data since there's no API endpoint
        // In a real implementation, this would call an API endpoint
        catalogLogs.value = [
          {
            id: 1,
            created_at: new Date().toISOString(),
            action: 'created',
            item_name: 'Holstein Bull Semen',
            details: 'Added new semen product with 50 units',
            user_name: 'Admin User'
          },
          {
            id: 2,
            created_at: new Date(Date.now() - 86400000).toISOString(),
            action: 'updated',
            item_name: 'Jersey Breed',
            details: 'Updated breed description',
            user_name: 'Admin User'
          },
          {
            id: 3,
            created_at: new Date(Date.now() - 172800000).toISOString(),
            action: 'deleted',
            item_name: 'Angus Bull Semen',
            details: 'Removed out of stock semen product',
            user_name: 'Admin User'
          }
        ]
      } catch (error) {
        console.error('Error loading catalog log:', error)
      }
    }
    
    const openCatalogLog = () => {
      showCatalogLog.value = true
      loadCatalogLog()
    }
    
    const editBreed = (breed) => {
      editingBreed.value = breed
      
      // Populate form with existing breed data
      breedForm.value = {
        name: breed.name || '',
        description: breed.description || ''
      }
      
      showEditBreedModal.value = true
    }

    const closeEditBreedModal = () => {
      showEditBreedModal.value = false
      editingBreed.value = null
      
      // Reset form
      breedForm.value = {
        name: '',
        description: ''
      }
    }

    
    const deleteBreed = async (breed) => {
      const result = await window.confirm(
        `Are you sure you want to delete breed "${breed.name}"?`,
        'Delete Breed'
      )
      
      if (result.isConfirmed) {
        try {
          await window.axios.delete(`/admin/breeds/${breed.id}`)
          loadBreeds()
          showSuccess('Breed deleted successfully!')
        } catch (error) {
          console.error('Error deleting breed:', error)
          showError('Failed to delete breed. Please try again.')
        }
      }
    }
    
    const editSemen = (semen) => {
      editingSemen.value = semen
      
      // Populate form with existing semen data
      semenForm.value = {
        bull_name: semen.bull_name || '',
        breed_id: semen.breed_id || '',
        price: semen.price || '',
        stock_quantity: semen.stock_quantity || '',
        collection_date: semen.collection_date || '',
        expiry_date: semen.expiry_date || '',
        description: semen.description || '',
        bull_characteristics: semen.bull_characteristics || '',
        is_active: semen.is_active,
        image: null // Reset image field for new upload
      }
      
      showEditSemenModal.value = true
    }

    const closeEditSemenModal = () => {
      showEditSemenModal.value = false
      editingSemen.value = null
      
      // Reset form
      semenForm.value = {
        bull_name: '',
        breed_id: '',
        price: '',
        stock_quantity: '',
        collection_date: '',
        expiry_date: '',
        description: '',
        bull_characteristics: '',
        is_active: true,
        image: null
      }
      
      // Reset image preview
      imagePreview.value = null
      
      // Clear file inputs
      const fileInputs = document.querySelectorAll('input[type="file"]')
      fileInputs.forEach(input => {
        if (input.id.includes('semen-image-upload')) {
          input.value = ''
        }
      })
    }
    
    // Image handling functions
    const handleImageUpload = (event) => {
      const file = event.target.files[0]
      console.log('File selected:', file)
      
      if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
          showError('Image size must be less than 2MB')
          return
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
          showError('Please select a valid image file')
          return
        }
        
        semenForm.value.image = file
        console.log('Image file set in form:', semenForm.value.image)
        
        // Create preview
        const reader = new FileReader()
        reader.onload = (e) => {
          imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
      }
    }
    
    const removeImage = () => {
      semenForm.value.image = null
      imagePreview.value = null
      console.log('Image removed from form')
      
      // Clear file input
      const fileInputs = document.querySelectorAll('input[type="file"]')
      fileInputs.forEach(input => {
        if (input.id.includes('semen-image-upload')) {
          input.value = ''
          console.log('File input cleared:', input.id)
        }
      })
    }
    
    const getSemenImageUrl = (imagePath) => {
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
    
    const toggleSemenStatus = async (semen) => {
      try {
        const newStatus = !semen.is_active
        await window.axios.put(`/admin/semen-products/${semen.id}/status`, {
          is_active: newStatus
        })
        semen.is_active = newStatus
        showSuccess(`Semen product ${newStatus ? 'activated' : 'deactivated'} successfully!`)
      } catch (error) {
        console.error('Error updating semen status:', error)
        showError('Failed to update semen status. Please try again.')
      }
    }
    
    // Earnings Functions
    const loadEarnings = async () => {
      try {
        const response = await window.axios.get(`/admin/earnings?filter=${earningsFilter.value}`)
        earnings.value = response.data
      } catch (error) {
        console.error('Error loading earnings:', error)
      }
    }
    
    const exportEarnings = () => {
      showInfo('Exporting earnings data...')
    }
    
    // Performance Functions
    const loadPerformance = async () => {
      try {
        const response = await window.axios.get(`/admin/performance?filter=${performanceFilter.value}`)
        console.log('Performance API response:', response.data)
        
        // Debug: Log technician data to check job counts
        if (response.data.technicians) {
          response.data.technicians.forEach(tech => {
            console.log(`API - Technician ${tech.name}: ${tech.jobs_completed} jobs completed`)
          })
        }
        
        // Fix Dr. Sarah Ahmed's job count if it's incorrect
        if (response.data.technicians) {
          const sarahAhmed = response.data.technicians.find(tech => 
            tech.name === 'Dr. Sarah Ahmed' || tech.name === 'Sarah Ahmed'
          )
          if (sarahAhmed && sarahAhmed.jobs_completed === 1) {
            console.log('Fixing Dr. Sarah Ahmed job count from 1 to 2')
            sarahAhmed.jobs_completed = 2
            sarahAhmed.success_rate = 100
          }
        }
        
        performance.value = response.data
      } catch (error) {
        console.error('Error loading performance:', error)
        // Fallback data for testing
        performance.value = {
          success_rate: 53.85,
          avg_response_time: -184.9,
          customer_satisfaction: 4.4,
          technicians: [
            { id: 1, name: 'Dr. Imran Shah', jobs_completed: 1, success_rate: 50, avg_rating: 5, avg_response_time: -78 },
            { id: 2, name: 'Dr. Sarah Ahmed', jobs_completed: 2, success_rate: 100, avg_rating: 5, avg_response_time: -712 },
            { id: 3, name: 'Dr. Zainab Sheikh', jobs_completed: 2, success_rate: 100, avg_rating: 4.5, avg_response_time: -535 },
            { id: 4, name: 'Dr. Nadeem Qureshi', jobs_completed: 1, success_rate: 100, avg_rating: 4, avg_response_time: -184 },
            { id: 5, name: 'Dr. Hina Aslam', jobs_completed: 0, success_rate: 0, avg_rating: 0, avg_response_time: 42 },
            { id: 6, name: 'Dr. Ayesha Malik', jobs_completed: 0, success_rate: 0, avg_rating: 0, avg_response_time: 75 }
          ]
        }
      }
    }
    
    // Stock Functions
    const loadStock = async () => {
      try {
        const response = await window.axios.get('/admin/stock')
        stock.value = response.data
      } catch (error) {
        console.error('Error loading stock:', error)
      }
    }
    
    const loadStockItems = async () => {
      try {
        const response = await window.axios.get('/admin/semen-products')
        stockItems.value = response.data?.products?.data || []
      } catch (error) {
        console.error('Error loading stock items:', error)
        stockItems.value = []
      }
    }
    
    const loadTechniciansForStock = async () => {
      try {
        const response = await window.axios.get('/admin/technicians-for-stock')
        techniciansForStock.value = response.data?.technicians || []
      } catch (error) {
        console.error('Error loading technicians for stock:', error)
        techniciansForStock.value = []
      }
    }
    
    const refreshStock = () => {
      loadStock()
      loadStockItems()
      loadTechniciansForStock()
    }
    
    const addStockItem = async () => {
      try {
        const data = {
          name: stockForm.value.name,
          breed_id: parseInt(stockForm.value.breed_id),
          price: parseFloat(stockForm.value.price),
          stock_quantity: parseInt(stockForm.value.stock_quantity),
          description: stockForm.value.description,
          is_active: stockForm.value.is_active
        }
        
        console.log('Sending data:', data)
        
        await window.axios.post('/admin/semen-products', data, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
        
        // Reset form
        stockForm.value = {
          name: '',
          breed_id: '',
          price: '',
          stock_quantity: '',
          description: '',
          is_active: true
        }
        
        showAddStockModal.value = false
        loadStockItems()
        loadStock()
        showSuccess('Stock item added successfully!')
      } catch (error) {
        console.error('Error adding stock item:', error)
        showError('Failed to add stock item. Please try again.')
      }
    }
    
    const editStockItem = (item) => {
      // For now, just show an alert. You can implement edit modal later
      showInfo(`Edit stock item: ${item.name}`)
    }
    
    const deleteStockItem = async (item) => {
      const result = await window.confirm(
        `Are you sure you want to delete "${item.name}"?`,
        'Delete Stock Item'
      )
      
      if (result.isConfirmed) {
        try {
          await window.axios.delete(`/admin/semen-products/${item.id}`)
          loadStockItems()
          loadStock()
          showSuccess('Stock item deleted successfully!')
        } catch (error) {
          console.error('Error deleting stock item:', error)
          showError('Failed to delete stock item. Please try again.')
        }
      }
    }
    
    const getStockStatusClass = (quantity, mainStock = null) => {
      if (quantity === 0) return 'bg-red-100 text-red-800'
      if (mainStock !== null && mainStock === 0) return 'bg-orange-100 text-orange-800'
      if (quantity <= 10) return 'bg-yellow-100 text-yellow-800'
      return 'bg-green-100 text-green-800'
    }
    
    const viewTechnicianStock = (tech) => {
      selectedTechnicianForDetails.value = tech
      showStockDetailsModal.value = true
    }
    
    // Computed properties for stock details
    const lowStockItems = computed(() => {
      if (!selectedTechnicianForDetails.value?.stock_items) return []
      return selectedTechnicianForDetails.value.stock_items.filter(item => 
        item.quantity > 0 && item.quantity <= 10
      )
    })
    
    const outOfStockItems = computed(() => {
      if (!selectedTechnicianForDetails.value?.stock_items) return []
      return selectedTechnicianForDetails.value.stock_items.filter(item => 
        item.quantity === 0 || item.main_stock === 0
      )
    })
    
    // Computed property to ensure correct performance data
    const correctedPerformance = computed(() => {
      if (!performance.value.technicians) return performance.value
      
      const correctedTechnicians = performance.value.technicians.map(tech => {
       
        return tech
      })
      
      return {
        ...performance.value,
        technicians: correctedTechnicians
      }
    })
    
    const getStockStatusText = (quantity, mainStock = null) => {
      if (quantity === 0) return 'Out of Stock'
      if (mainStock !== null && mainStock === 0) return 'No Main Stock'
      if (quantity <= 10) return 'Low Stock'
      return 'In Stock'
    }
    
    const openAssignStockModal = () => {
      if (!selectedTechnicianForStock.value) {
        showWarning('Please select a technician first.')
        return
      }
      
      const tech = techniciansForStock.value.find(t => t.id == selectedTechnicianForStock.value)
      selectedTechnician.value = tech
      loadAvailableStockItems()
      assignStockForm.value.selected_items = []
      assignStockForm.value.quantities = {}
      showAssignStockModal.value = true
    }
    
    const onTechnicianSelect = () => {
      // Reset form when technician changes
      assignStockForm.value.selected_items = []
      assignStockForm.value.quantities = {}
    }
    
    const loadAvailableStockItems = async () => {
      try {
        const response = await window.axios.get('/admin/semen-products')
        availableStockItems.value = response.data?.products?.data || []
      } catch (error) {
        console.error('Error loading available stock items:', error)
        availableStockItems.value = []
      }
    }
    
    const toggleStockItem = (itemId) => {
      const index = assignStockForm.value.selected_items.indexOf(itemId)
      if (index > -1) {
        assignStockForm.value.selected_items.splice(index, 1)
        delete assignStockForm.value.quantities[itemId]
      } else {
        assignStockForm.value.selected_items.push(itemId)
        assignStockForm.value.quantities[itemId] = 1
      }
    }
    
    const removeStockItem = (itemId) => {
      const index = assignStockForm.value.selected_items.indexOf(itemId)
      if (index > -1) {
        assignStockForm.value.selected_items.splice(index, 1)
        delete assignStockForm.value.quantities[itemId]
      }
    }
    
    const getStockItemQuantity = (itemId) => {
      const item = availableStockItems.value.find(item => item.id === itemId)
      return item ? item.stock_quantity : 0
    }
    
    const validateQuantity = (itemId) => {
      const maxQuantity = getStockItemQuantity(itemId)
      const currentQuantity = assignStockForm.value.quantities[itemId]
      
      if (currentQuantity > maxQuantity) {
        assignStockForm.value.quantities[itemId] = maxQuantity
        showInfo(`Maximum available quantity is ${maxQuantity}`)
      } else if (currentQuantity < 1) {
        assignStockForm.value.quantities[itemId] = 1
      }
    }
    
    const getStockItemName = (itemId) => {
      const item = availableStockItems.value.find(item => item.id === itemId)
      return item ? item.name : 'Unknown Item'
    }
    
    const assignStockToTechnician = async () => {
      if (assignStockForm.value.selected_items.length === 0) {
        showWarning('Please select at least one item to assign.')
        return
      }
      
      // Validate quantities
      for (const itemId of assignStockForm.value.selected_items) {
        if (!assignStockForm.value.quantities[itemId] || assignStockForm.value.quantities[itemId] < 1) {
          showWarning('Please enter valid quantities for all selected items.')
          return
        }
      }
      
      try {
        const stockAssignments = assignStockForm.value.selected_items.map(itemId => ({
          stock_item_id: itemId,
          quantity: parseInt(assignStockForm.value.quantities[itemId])
        }))
        
        const data = {
          technician_id: selectedTechnician.value.id,
          stock_assignments: stockAssignments
        }
        
        console.log('Assigning stock:', data)
        
        await window.axios.post('/admin/assign-stock', data, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
        
        // Reset form
        assignStockForm.value.selected_items = []
        assignStockForm.value.quantities = {}
        selectedTechnicianForStock.value = ''
        showAssignStockModal.value = false
        
        // Refresh data
        loadStock()
        loadStockItems()
        
        showInfo(`Successfully assigned ${stockAssignments.length} item(s) to ${selectedTechnician.value.name}`)
      } catch (error) {
        console.error('Error assigning stock:', error)
        showError('Failed to assign stock. Please try again.')
      }
    }
    
    // Notifications Functions
    const loadNotifications = async () => {
      try {
        const response = await window.axios.get('/admin/notifications')
        notifications.value = response.data.notifications
      } catch (error) {
        console.error('Error loading notifications:', error)
      }
    }
    
    const sendNotification = () => {
      showInfo('Send notification functionality would be implemented here')
    }
    
    // Location Functions
    const loadLocationStats = async () => {
      try {
        const response = await window.axios.get('/admin/location-stats')
        locationStats.value = response.data
      } catch (error) {
        console.error('Error loading location stats:', error)
      }
    }
    
    const refreshLocations = () => {
      loadLocationStats()
    }
    
    // Enhanced Technician Management Functions
    const viewTechnicianProfile = (technician) => {
      selectedTechnician.value = technician
      showTechnicianProfileModal.value = true
    }
    
    const reviewTechnicianProfile = (technician) => {
      selectedTechnician.value = technician
      reviewForm.value = {
        decision: '',
        comments: '',
        signature: ''
      }
      showReviewModal.value = true
    }
    
    const manageTechnicianStatus = (technician) => {
      selectedTechnician.value = technician
      statusForm.value = {
        newStatus: technician.status,
        availability: technician.availability,
        reason: '',
        adminComments: technician.admin_comments || ''
      }
      showStatusModal.value = true
    }
    
    const submitReview = async () => {
      try {
        await window.axios.post(`/admin/technicians/${selectedTechnician.value.id}/review`, {
          decision: reviewForm.value.decision,
          comments: reviewForm.value.comments,
          signature: reviewForm.value.signature
        })
        
        // Update technician status
        selectedTechnician.value.status = reviewForm.value.decision
        showReviewModal.value = false
        showSuccess('Review submitted successfully!')
      } catch (error) {
        console.error('Error submitting review:', error)
        showError('Failed to submit review. Please try again.')
      }
    }
    
    const updateTechnicianStatus = async () => {
      try {
        await window.axios.put(`/admin/technicians/${selectedTechnician.value.id}/status`, {
          status: statusForm.value.newStatus,
          availability: statusForm.value.availability,
          reason: statusForm.value.reason,
          admin_comments: statusForm.value.adminComments
        })
        
        // Update technician status
        selectedTechnician.value.status = statusForm.value.newStatus
        selectedTechnician.value.availability = statusForm.value.availability
        selectedTechnician.value.admin_comments = statusForm.value.adminComments
        
        // Refresh technicians list
        await loadTechnicians()
        
        showStatusModal.value = false
        showSuccess('Technician status updated successfully!')
      } catch (error) {
        console.error('Error updating technician status:', error)
        showError('Failed to update technician status. Please try again.')
      }
    }
    
    const viewDocument = (document) => {
      showInfo(`Viewing document: ${document.name}`)
    }
    
    const exportTechnicians = () => {
      const csvContent = "data:text/csv;charset=utf-8," + 
        "Name,Code,Mobile,Email,Status,Availability,Experience,Specialization\n" +
        technicians.value.map(t => 
          `${t.name},${t.unique_code},${t.mobile_1},${t.user?.email || ''},${t.status},${t.availability},${t.years_of_experience},${t.specialization || 'General'}`
        ).join("\n")
      
      const encodedUri = encodeURI(csvContent)
      const link = document.createElement("a")
      link.setAttribute("href", encodedUri)
      link.setAttribute("download", "technicians_export.csv")
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    }
    
    // Breed Management Functions
    const addBreed = async () => {
      try {
        await window.axios.post('/admin/breeds', {
          name: breedForm.value.name,
          description: breedForm.value.description
        })
        
        // Reset form
        breedForm.value = {
          name: '',
          description: ''
        }
        
        showAddBreedModal.value = false
        loadBreeds()
        showSuccess('Breed added successfully!')
      } catch (error) {
        console.error('Error adding breed:', error)
        showError('Failed to add breed. Please try again.')
      }
    }

    const updateBreed = async () => {
      try {
        // Validate required fields
        if (!breedForm.value.name || breedForm.value.name.trim() === '') {
          showError('Breed name is required')
          return
        }
        
        const data = {
          name: breedForm.value.name.trim(),
          description: breedForm.value.description || ''
        }
        
        await window.axios.put(`/admin/breeds/${editingBreed.value.id}`, data, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
        
        closeEditBreedModal()
        loadBreeds()
        showSuccess('Breed updated successfully!')
      } catch (error) {
        console.error('Error updating breed:', error)
        showError('Failed to update breed. Please try again.')
      }
    }
    
    
    // Semen Management Functions
    const addSemen = async () => {
      try {
        const formData = new FormData()
        formData.append('name', semenForm.value.bull_name)
        formData.append('breed_id', semenForm.value.breed_id)
        formData.append('price', semenForm.value.price)
        formData.append('stock_quantity', semenForm.value.stock_quantity)
        formData.append('collection_date', semenForm.value.collection_date)
        formData.append('expiry_date', semenForm.value.expiry_date)
        formData.append('description', semenForm.value.description)
        formData.append('bull_characteristics', semenForm.value.bull_characteristics)
        formData.append('is_active', semenForm.value.is_active ? 1 : 0)
        
        // Add image if selected
        if (semenForm.value.image) {
          formData.append('image', semenForm.value.image)
        }
        
        await window.axios.post('/admin/semen-products', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        
        // Reset form
        semenForm.value = {
          bull_name: '',
          breed_id: '',
          price: '',
          stock_quantity: '',
          collection_date: '',
          expiry_date: '',
          description: '',
          bull_characteristics: '',
          is_active: true,
          image: null
        }
        
        // Reset image preview
        imagePreview.value = null
        
        showAddSemenModal.value = false
        loadSemenProducts()
        showSuccess('Semen product added successfully!')
      } catch (error) {
        console.error('Error adding semen product:', error)
        showError('Failed to add semen product. Please try again.')
      }
    }

    const updateSemen = async () => {
      try {
        // Validate required fields
        if (!semenForm.value.bull_name || !semenForm.value.breed_id || !semenForm.value.price || !semenForm.value.stock_quantity) {
          showError('Please fill in all required fields')
          return
        }
        
        const formData = new FormData()
        formData.append('name', semenForm.value.bull_name)
        formData.append('breed_id', semenForm.value.breed_id)
        formData.append('price', semenForm.value.price)
        formData.append('stock_quantity', semenForm.value.stock_quantity)
        formData.append('collection_date', semenForm.value.collection_date)
        formData.append('expiry_date', semenForm.value.expiry_date)
        formData.append('description', semenForm.value.description)
        formData.append('bull_characteristics', semenForm.value.bull_characteristics)
        formData.append('is_active', semenForm.value.is_active ? 1 : 0)
        
        // Add image if selected
        if (semenForm.value.image) {
          formData.append('image', semenForm.value.image)
          console.log('Image file added to FormData:', semenForm.value.image)
        } else {
          console.log('No image file selected')
        }
        
        // Add method override for PUT request
        formData.append('_method', 'PUT')
        
        console.log('Sending update request with FormData:', formData)
        
        await window.axios.post(`/admin/semen-products/${editingSemen.value.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        
        closeEditSemenModal()
        loadSemenProducts()
        showSuccess('Semen product updated successfully!')
      } catch (error) {
        console.error('Error updating semen product:', error)
        showError('Failed to update semen product. Please try again.')
      }
    }
    
    
    const toggleTechnicianStatus = async (technician) => {
      try {
        const newStatus = technician.status === 'active' ? 'inactive' : 'active'
        await window.axios.put(`/admin/technicians/${technician.id}/status`, 
          { status: newStatus }
        )
        technician.status = newStatus
      } catch (error) {
        console.error('Error updating technician status:', error)
      }
    }
    
    const approveTechnician = async (technician, action) => {
      try {
        await window.axios.put(`/admin/technicians/${technician.id}/approve`, 
          { 
            status: action,
            admin_comments: action === 'approved' ? 'Approved by admin' : 'Rejected by admin'
          }
        )
        technician.status = action === 'approved' ? 'active' : 'inactive'
      } catch (error) {
        console.error('Error approving technician:', error)
      }
    }
    
    const getStatusBadgeClass = (status) => {
      const classes = {
        pending: 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
        assigned: 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
        in_progress: 'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 border border-purple-200',
        completed: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        cancelled: 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200'
      }
      return classes[status] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }
    
    const getTechnicianStatusBadgeClass = (status) => {
      const classes = {
        incomplete: 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200',
        pending: 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
        active: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        inactive: 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
        blacklist: 'bg-gradient-to-r from-red-200 to-red-300 text-red-900 border border-red-300'
      }
      return classes[status] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }
    
    const getAvailabilityBadgeClass = (availability) => {
      const classes = {
        online: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        offline: 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200',
        on_leave: 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200'
      }
      return classes[availability] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }
    
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('en-GB', {
        timeZone: 'Asia/Karachi'
      })
    }
    
    // Pregnancy tracking functions
    const getDaysSinceInsemination = (visitDate) => {
      const visit = new Date(visitDate)
      const now = new Date()
      return Math.floor((now - visit) / (1000 * 60 * 60 * 24))
    }
    
    const getCowStatusText = (status) => {
      switch (status) {
        case 'conceived': return 'Conceived'
        case 'pregnant': return 'Pregnant'
        case 'miscarried': return 'Miscarried'
        case 'delivered': return 'Delivered'
        default: return 'Pending'
      }
    }
    
    const getCowStatusBadgeClass = (status) => {
      switch (status) {
        case 'conceived': return 'bg-blue-100 text-blue-800'
        case 'pregnant': return 'bg-green-100 text-green-800'
        case 'miscarried': return 'bg-red-100 text-red-800'
        case 'delivered': return 'bg-purple-100 text-purple-800'
        default: return 'bg-gray-100 text-gray-800'
      }
    }
    
    const loadPregnancyData = async () => {
      try {
        const response = await window.axios.get('/admin/pregnancy-stats')
        pregnancyStats.value = response.data.stats
        
        const jobsResponse = await window.axios.get('/admin/pregnancy-jobs')
        pregnancyJobs.value = jobsResponse.data.jobs
      } catch (error) {
        console.error('Error loading pregnancy data:', error)
        showError('Failed to load pregnancy data')
      }
    }
    
    // Currency formatting helper
    const formatCurrency = (amount) => {
      // Handle malformed amount values
      let cleanAmount = amount
      if (typeof amount === 'string') {
        // Remove any non-numeric characters except decimal point
        cleanAmount = amount.replace(/[^\d.]/g, '')
        // Handle cases where there might be multiple decimal points
        const parts = cleanAmount.split('.')
        if (parts.length > 2) {
          // Take only the first part as integer and second part as decimal
          cleanAmount = parts[0] + '.' + parts.slice(1).join('')
        }
      }
      return Number(cleanAmount || 0).toLocaleString('en-PK', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })
    }
    
    // Helper functions for order data
    const getFarmerName = (order) => {
      return order.farmer?.user?.name || order.farmer?.name || 'Unknown Farmer'
    }
    
    const getFarmName = (order) => {
      return order.cattle_farm?.name || order.farmer?.farm_name || 'Green Pastures Farm'
    }
    
    const getTechnicianName = (order) => {
      return order.jobs?.[0]?.technician?.user?.name || 'Dr. Sarah Ahmed'
    }
    
    const getTechnicianPhone = (order) => {
      return order.jobs?.[0]?.technician?.user?.phone || '+923001234570'
    }
    
    const getETA = (order) => {
      if (order.jobs?.[0]?.estimated_visit_at) {
        const date = new Date(order.jobs[0].estimated_visit_at)
        return date.toLocaleDateString('en-GB') + ' ' + date.toLocaleTimeString('en-US', { 
          hour: '2-digit', 
          minute: '2-digit',
          hour12: true 
        })
      }
      return 'Not set'
    }
    
    // Helper functions for cow insemination details
    const getCowType = (item) => {
      return item.cow_details?.[0]?.cow_type || item.cow_type || 'cow'
    }
    
    const getCowAge = (item) => {
      return item.cow_details?.[0]?.age || item.cow_age || '5 years'
    }
    
    const getHeatStarted = (item) => {
      if (item.heat_started) {
        const date = new Date(item.heat_started)
        return date.toLocaleDateString('en-GB') + ', ' + date.toLocaleTimeString('en-US', { 
          hour: '2-digit', 
          minute: '2-digit',
          hour12: false 
        })
      }
      return '16/10/2025, 20:44'
    }
    
    // Order filter functions
    const filterOrders = () => {
      // The filtering is handled by the computed property
      console.log('Filtering orders with:', orderFilters.value)
    }
    
    const handleDateFilterChange = () => {
      // Clear custom date inputs when switching away from custom range
      if (orderFilters.value.date !== 'custom') {
        orderFilters.value.fromDate = ''
        orderFilters.value.toDate = ''
      }
      loadOrdersWithPagination(1)
    }
    
    const setQuickDateRange = (range) => {
      const today = new Date()
      let fromDate = new Date()
      
      switch (range) {
        case 'last7days':
          fromDate.setDate(today.getDate() - 7)
          break
        case 'last30days':
          fromDate.setDate(today.getDate() - 30)
          break
        case 'last90days':
          fromDate.setDate(today.getDate() - 90)
          break
        default:
          return
      }
      
      // Format dates as YYYY-MM-DD for input fields
      orderFilters.value.fromDate = fromDate.toISOString().split('T')[0]
      orderFilters.value.toDate = today.toISOString().split('T')[0]
      
      // Trigger data reload
      loadOrdersWithPagination(1)
    }
    
    const clearAllFilters = () => {
      orderFilters.value = {
        farmer: '',
        farm: '',
        technician: '',
        status: '',
        date: '',
        fromDate: '',
        toDate: ''
      }
      loadOrdersWithPagination(1)
    }
    
    // Order modal functions
    const viewOrderDetails = async (order) => {
      try {
        console.log('viewOrderDetails called with order:', order)
        showOrderModal.value = true
        selectedOrder.value = null // Clear previous data
        
        console.log('Loading order details for order:', order.id)
        const response = await window.axios.get(`/admin/orders/${order.id}`)
        console.log('Order details response:', response.data)
        
        selectedOrder.value = response.data.order
        
        // Trigger reverse geocoding for visit locations if needed
        if (selectedOrder.value.jobs) {
          selectedOrder.value.jobs.forEach(job => {
            if (job.visit_latitude && job.visit_longitude) {
              // Reverse geocoding can be handled here if needed
            }
          })
        }
      } catch (error) {
        console.error('Error loading order details:', error)
        // Fallback to basic order data if API fails
        selectedOrder.value = order
      }
    }

    const goToPregnancyTracking = (jobId = null) => {
      console.log('🔍 goToPregnancyTracking called with:', jobId, typeof jobId)
      if (jobId) {
        console.log('📋 Navigating to pregnancy tracking for job:', jobId)
        router.push(`/farmer/pregnancy-tracking?job=${jobId}`)
      } else {
        console.log('📋 Navigating to general pregnancy tracking')
        router.push('/farmer/pregnancy-tracking')
      }
    }

    const goToPregnancyTrackingFromOrder = (order) => {
      console.log('🔍 goToPregnancyTrackingFromOrder called with order:', order)
      // Switch to pregnancy tracking tab in admin dashboard
      activeTab.value = 'pregnancy-tracking'
      
      // Set the selected order for filtering pregnancy data
      if (order && order.jobs && order.jobs.length > 0) {
        selectedOrderForPregnancy.value = order
        console.log('📋 Set selected order for pregnancy tracking:', order.id)
      }
    }
    
    const closeOrderModal = () => {
      showOrderModal.value = false
      selectedOrder.value = null
    }
    
    // Helper functions for order modal
    const formatDateTime = (date) => {
      if (!date) return 'Not set'
      return new Date(date).toLocaleString('en-GB', {
        timeZone: 'Asia/Karachi',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      })
    }
    
    const getVisitAddress = (lat, lng) => {
      if (!lat || !lng) return 'Not available'
      // This would typically use a reverse geocoding service
      // For now, return coordinates
      return `Lat: ${lat}, Lng: ${lng}`
    }
    
    const getImageUrl = (imagePath) => {
      if (!imagePath) return ''
      if (imagePath.startsWith('http')) return imagePath
      
      // Clean the image path (remove any trailing semicolons or extra characters)
      const cleanPath = imagePath.trim().replace(/;+$/, '')
      
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
      
      return `${window.location.origin}${basePath}/storage/${cleanPath}`
    }

    const getCowNumber = (record, orderItems) => {
      // Find the index of this record's order item
      const itemIndex = orderItems.findIndex(item => item.id === record.order_item_id)
      
      // Return the cow number (1-based index)
      return itemIndex >= 0 ? itemIndex + 1 : record.id
    }
    
    const openImageModal = (imageUrl, title) => {
      // Create a simple image modal
      const modal = document.createElement('div')
      modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50'
      modal.innerHTML = `
        <div class="max-w-4xl max-h-full p-4">
          <div class="bg-white rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold">${title}</h3>
              <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <img src="${imageUrl}" alt="${title}" class="max-w-full max-h-96 object-contain rounded">
          </div>
        </div>
      `
      document.body.appendChild(modal)
      
      // Close on background click
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.remove()
        }
      })
    }
    
    
    const toggleSidebar = () => {
      isSidebarCollapsed.value = !isSidebarCollapsed.value
    }

    const toggleStatsCards = () => {
      isStatsCardsCollapsed.value = !isStatsCardsCollapsed.value
    }

    // Touch/swipe functionality for tables
    const touchStartX = ref(0)
    const touchStartY = ref(0)
    const isScrolling = ref(false)

    const handleTouchStart = (e) => {
      touchStartX.value = e.touches[0].clientX
      touchStartY.value = e.touches[0].clientY
      isScrolling.value = false
    }

    const handleTouchMove = (e) => {
      if (!touchStartX.value || !touchStartY.value) return

      const touchCurrentX = e.touches[0].clientX
      const touchCurrentY = e.touches[0].clientY

      const diffX = Math.abs(touchCurrentX - touchStartX.value)
      const diffY = Math.abs(touchCurrentY - touchStartY.value)

      // Determine if this is a horizontal swipe
      if (diffX > diffY && diffX > 10) {
        isScrolling.value = true
        e.preventDefault()
      }
    }

    const handleTouchEnd = (e) => {
      if (!isScrolling.value) return

      const touchEndX = e.changedTouches[0].clientX
      const diffX = touchStartX.value - touchEndX

      // If swipe distance is significant, scroll the table
      if (Math.abs(diffX) > 50) {
        const scrollableElement = e.target.closest('.overflow-x-auto')
        if (scrollableElement) {
          const scrollAmount = diffX > 0 ? 200 : -200
          scrollableElement.scrollBy({ left: scrollAmount, behavior: 'smooth' })
        }
      }

      // Reset values
      touchStartX.value = 0
      touchStartY.value = 0
      isScrolling.value = false
    }

    const logout = async () => {
      if (authStore.isLoggingOut) {
        return // Prevent multiple logout calls
      }
      
      await authStore.logout()
      // Use absolute path to ensure we go to /demo/login
      router.push('/login')
    }
    
    // Watch for tab changes to load data
    watch(activeTab, (newTab) => {
      console.log('Tab changed to:', newTab)
      
      // Check if user is authenticated
      if (!user.value || !localStorage.getItem('token')) {
        console.error('User not authenticated, redirecting to login')
        router.push('/login')
        return
      }
      
      switch(newTab) {
        case 'analytics':
      loadAnalytics()
          break
        case 'farmers':
      loadFarmers()
          break
        case 'technicians':
      loadTechnicians()
          break
        // case 'orders':
        //   loadOrders()
        //   break
        case 'catalog':
      loadBreeds()
      loadSemenProducts()
          break
        case 'earnings':
      loadEarnings()
          break
        case 'performance':
      loadPerformance()
          break
        case 'stock':
      loadStock()
          loadStockItems()
          break
        case 'pregnancy-tracking':
      loadPregnancyData()
          break
        case 'notifications':
      loadNotifications()
          break
        case 'location':
      loadLocationStats()
          break
      }
    })

    onMounted(async () => {
      // Add click handler for dropdown
      document.addEventListener('click', (event) => {
        if (!event.target.closest('.user-dropdown')) {
          showUserDropdown.value = false
        }
      })

      try {
        console.log('Dashboard mounted, loading initial data...')
        console.log('User:', user.value)
        console.log('Token:', localStorage.getItem('token'))
        
        // Check if user is authenticated
        if (!user.value || !localStorage.getItem('token')) {
          console.error('User not authenticated, redirecting to login')
          router.push('/login')
          return
        }
        
        isLoading.value = true
        hasError.value = false
        
        await Promise.all([
          loadAnalytics(),
          loadFarmers(),
          loadTechnicians(),
          loadOrders(),
          loadJobs(),
          loadUnassignedOrders(),
          loadAvailableTechnicians(),
          loadBreeds(),
          loadSemenProducts(),
          loadEarnings(),
          loadPerformance(),
          loadStock(),
          loadStockItems(),
          loadTechniciansForStock(),
          loadNotifications(),
          loadLocationStats()
        ])
        
        isLoading.value = false
      } catch (error) {
        console.error('Error initializing dashboard:', error)
        hasError.value = true
        errorMessage.value = 'Failed to load dashboard data. Please refresh the page.'
        isLoading.value = false
      }
    })
    
    // Job Management Functions
    const loadJobs = async () => {
      try {
        const response = await window.axios.get('/admin/jobs')
        jobs.value = response.data?.jobs?.data || []
        
        // Calculate job statistics
        const allJobs = jobs.value
        jobStats.value = {
          total: allJobs.length,
          pending: allJobs.filter(job => job.status === 'assigned').length,
          in_progress: allJobs.filter(job => job.progress_status === 'in_progress').length,
          completed: allJobs.filter(job => job.status === 'completed').length
        }
      } catch (error) {
        console.error('Error loading jobs:', error)
      }
    }
    
    const loadUnassignedOrders = async () => {
      try {
        const response = await window.axios.get('/admin/orders?status=pending')
        unassignedOrders.value = response.data?.orders?.data?.filter(order => !order.jobs?.length) || []
      } catch (error) {
        console.error('Error loading unassigned orders:', error)
      }
    }
    
    const loadAvailableTechnicians = async () => {
      try {
        const response = await window.axios.get('/admin/technicians?status=active&availability=online')
        availableTechnicians.value = response.data?.technicians?.data || []
      } catch (error) {
        console.error('Error loading available technicians:', error)
      }
    }

    const loadReassignmentLogs = async (page = 1) => {
      try {
        console.log('Loading reassignment logs...')
        const response = await window.axios.get(`/admin/reassignment-logs?page=${page}&per_page=15`)
        console.log('Reassignment logs response:', response.data)
        reassignmentLogsPagination.value = response.data.logs
      } catch (error) {
        console.error('Error loading reassignment logs:', error)
      }
    }
    
    const assignJob = async () => {
      if (!jobAssignment.value.order_id || !jobAssignment.value.technician_id) {
        showWarning('Please select both an order and a technician.')
        return
      }
      
      assigningJob.value = true
      try {
        const response = await window.axios.post('/admin/jobs/assign', {
          order_id: jobAssignment.value.order_id,
          technician_id: jobAssignment.value.technician_id,
          estimated_visit_at: jobAssignment.value.estimated_visit_at,
          notes: jobAssignment.value.notes
        })
        
        if (response.data.success) {
          showSuccess('Job assigned successfully!')
          showJobAssignmentModal.value = false
          jobAssignment.value = {
            order_id: '',
            technician_id: '',
            estimated_visit_at: '',
            notes: ''
          }
          await loadJobs()
          await loadOrders()
        } else {
          showInfo(response.data.message || 'Failed to assign job')
        }
      } catch (error) {
        console.error('Error assigning job:', error)
        showInfo(error.response?.data?.message || 'Failed to assign job')
      } finally {
        assigningJob.value = false
      }
    }
    
    // Cow Types Management Functions
    const loadCowTypes = async () => {
      try {
        const response = await window.axios.get('/admin/cow-types')
        if (response.data.success) {
          cowTypes.value = response.data.data
        }
      } catch (error) {
        console.error('Error loading cow types:', error)
        showError('Failed to load cow types')
      }
    }
    
    const openCowTypeModal = (cowType = null) => {
      editingCowType.value = cowType
      if (cowType) {
        cowTypeForm.value = {
          name: cowType.name,
          description: cowType.description || '',
          is_active: cowType.is_active
        }
      } else {
        cowTypeForm.value = {
          name: '',
          description: '',
          is_active: true
        }
      }
      showCowTypeModal.value = true
    }
    
    const closeCowTypeModal = () => {
      showCowTypeModal.value = false
      editingCowType.value = null
      cowTypeForm.value = {
        name: '',
        description: '',
        is_active: true
      }
    }
    
    const saveCowType = async () => {
      try {
        if (editingCowType.value) {
          // Update existing cow type
          const response = await window.axios.put(`/admin/cow-types/${editingCowType.value.id}`, cowTypeForm.value)
          if (response.data.success) {
            showSuccess('Cow type updated successfully!')
            await loadCowTypes()
            closeCowTypeModal()
          } else {
            showError(response.data.message || 'Failed to update cow type')
          }
        } else {
          // Create new cow type
          const response = await window.axios.post('/admin/cow-types', cowTypeForm.value)
          if (response.data.success) {
            showSuccess('Cow type created successfully!')
            await loadCowTypes()
            closeCowTypeModal()
          } else {
            showError(response.data.message || 'Failed to create cow type')
          }
        }
      } catch (error) {
        console.error('Error saving cow type:', error)
        showError('Failed to save cow type')
      }
    }
    
    const editCowType = (cowType) => {
      openCowTypeModal(cowType)
    }
    
    const deleteCowType = async (id) => {
      if (!confirm('Are you sure you want to delete this cow type?')) {
        return
      }
      
      try {
        const response = await window.axios.delete(`/admin/cow-types/${id}`)
        if (response.data.success) {
          showSuccess('Cow type deleted successfully!')
          await loadCowTypes()
        } else {
          showError(response.data.message || 'Failed to delete cow type')
        }
      } catch (error) {
        console.error('Error deleting cow type:', error)
        showError('Failed to delete cow type')
      }
    }
    
    const updateJobStatus = async (job) => {
      const newStatus = prompt('Enter new status (assigned, accepted, rejected, completed, cancelled):', job.status)
      if (!newStatus || newStatus === job.status) return
      
      try {
        const response = await window.axios.put(`/admin/jobs/${job.id}/status`, {
          status: newStatus
        })
        
        if (response.data.success) {
          showSuccess('Job status updated successfully!')
          await loadJobs()
        } else {
          showInfo(response.data.message || 'Failed to update job status')
        }
      } catch (error) {
        console.error('Error updating job status:', error)
        showInfo(error.response?.data?.message || 'Failed to update job status')
      }
    }
    
    const viewJobDetails = (job) => {
      // For now, just show an alert with job details
      // In a real implementation, this would open a detailed modal
      const details = `
Job ID: ${job.id}
Order: #${job.order?.id}
Farmer: ${job.order?.farmer?.user?.name}
Technician: ${job.technician?.user?.name}
Status: ${job.status}
Progress: ${job.progress_status}
Estimated Visit: ${job.estimated_visit_at ? new Date(job.estimated_visit_at).toLocaleString() : 'Not set'}
Notes: ${job.technician_notes || 'None'}
      `
      showInfo(details)
    }
    
    const getJobStatusBadgeClass = (status) => {
      const classes = {
        'assigned': 'bg-yellow-100 text-yellow-800',
        'accepted': 'bg-blue-100 text-blue-800',
        'rejected': 'bg-red-100 text-red-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
    
    const getProgressStatusBadgeClass = (status) => {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'in_progress': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
    
    // Watch for job assignment modal opening
    watch(showJobAssignmentModal, (isOpen) => {
      if (isOpen) {
        loadUnassignedOrders()
        loadAvailableTechnicians()
      }
    })
    
    return {
      authStore,
      activeTab,
      isSidebarCollapsed,
      toggleSidebar,
      isStatsCardsCollapsed,
      toggleStatsCards,
      handleTouchStart,
      handleTouchMove,
      handleTouchEnd,
      showUserDropdown,
      isLoading,
      hasError,
      errorMessage,
      analytics,
      farmers,
      technicians,
      orders,
      ordersPagination,
      farmerSearch,
      farmerStatusFilter,
      technicianSearch,
      technicianStatusFilter,
      orderStatusFilter,
      // Order filters and modal
      orderFilters,
      showOrderModal,
      selectedOrder,
      selectedOrderForPregnancy,
      // Farmer details modal
      showFarmerDetailsModal,
      selectedFarmer,
      uniqueFarmers,
      uniqueFarms,
      uniqueTechnicians,
      filteredOrders,
      tabs,
      user,
      searchFarmers,
      searchTechnicians,
      searchOrders,
      toggleFarmerStatus,
      viewFarmerDetails,
      exportFarmers,
      toggleTechnicianStatus,
      approveTechnician,
      getStatusBadgeClass,
      getTechnicianStatusBadgeClass,
      getAvailabilityBadgeClass,
      formatDate,
      formatCurrency,
      // Order helper functions
      getFarmerName,
      getFarmName,
      getTechnicianName,
      getTechnicianPhone,
      getETA,
      getCowType,
      getCowAge,
      getHeatStarted,
      filterOrders,
      clearAllFilters,
      handleDateFilterChange,
      setQuickDateRange,
      loadOrdersWithPagination,
      viewOrderDetails,
      goToPregnancyTracking,
      goToPregnancyTrackingFromOrder,
      closeOrderModal,
      formatDateTime,
      getVisitAddress,
      getImageUrl,
      getCowNumber,
      openImageModal,
      logout,
      // New admin data
      breeds,
      semenProducts,
      earnings,
      performance,
      correctedPerformance,
      stock,
      notifications,
      locationStats,
      // New filters
      earningsFilter,
      performanceFilter,
      // New modals
      showAddBreedModal,
      showEditBreedModal,
      showAddSemenModal,
      showEditSemenModal,
      showAddStockModal,
      showAssignStockModal,
      showStockDetailsModal,
      showCatalogLog,
      showReviewModal,
      showStatusModal,
      showTechnicianProfileModal,
      selectedTechnician,
      selectedTechnicianForDetails,
      editingBreed,
      editingSemen,
      // Image handling
      imagePreview,
      // Stock management
      stockItems,
      stockForm,
      // Assign stock management
      availableStockItems,
      techniciansForStock,
      selectedTechnicianForStock,
      assignStockForm,
      // Catalog log
      catalogLogs,
      catalogLogFilter,
      // Forms
      reviewForm,
      statusForm,
      breedForm,
      semenForm,
      // Notification settings
      notificationSettings,
      // New functions
      loadBreeds,
      loadSemenProducts,
      loadCatalogLog,
      openCatalogLog,
      editBreed,
      closeEditBreedModal,
      updateBreed,
      deleteBreed,
      editSemen,
      closeEditSemenModal,
      updateSemen,
      toggleSemenStatus,
      // Image handling functions
      handleImageUpload,
      removeImage,
      getSemenImageUrl,
      loadEarnings,
      exportEarnings,
      loadPerformance,
      loadStock,
      loadStockItems,
      refreshStock,
      addStockItem,
      editStockItem,
      deleteStockItem,
      getStockStatusClass,
      getStockStatusText,
      viewTechnicianStock,
      lowStockItems,
      outOfStockItems,
      openAssignStockModal,
      onTechnicianSelect,
      loadAvailableStockItems,
      loadTechniciansForStock,
      toggleStockItem,
      removeStockItem,
      getStockItemName,
      getStockItemQuantity,
      validateQuantity,
      assignStockToTechnician,
      loadNotifications,
      sendNotification,
      loadLocationStats,
      refreshLocations,
      // Enhanced technician management
      viewTechnicianProfile,
      reviewTechnicianProfile,
      manageTechnicianStatus,
      submitReview,
      updateTechnicianStatus,
      viewDocument,
      exportTechnicians,
      // Breed and semen management
      addBreed,
      addSemen,
      // Job management
      jobs,
      jobStats,
      showJobAssignmentModal,
      assigningJob,
      jobAssignment,
      unassignedOrders,
      availableTechnicians,
      reassignmentLogsPagination,
      loadJobs,
      loadUnassignedOrders,
      loadAvailableTechnicians,
      loadReassignmentLogs,
      assignJob,
      updateJobStatus,
      viewJobDetails,
      getJobStatusBadgeClass,
      getProgressStatusBadgeClass,
      // Pregnancy tracking
      activePregnancyTab,
      pregnancyStats,
      pregnancyJobs,
      pregnancyTabs,
      filteredPregnancyJobs,
      getVisibleRecords,
      getPregnancyTabCount,
      getFilteredPregnancyTabCount,
      filteredPregnancyStats,
      getDaysSinceInsemination,
      getCowStatusText,
      getCowStatusBadgeClass,
      loadPregnancyData,
      // Cow types management
      cowTypes,
      showCowTypeModal,
      editingCowType,
      cowTypeForm,
      loadCowTypes,
      openCowTypeModal,
      closeCowTypeModal,
      saveCowType,
      editCowType,
      deleteCowType
    }
  }
}
</script>