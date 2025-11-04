<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100 flex relative overflow-x-hidden">
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
            <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div>
              <h1 class="text-lg font-bold text-gray-900">Farmer Panel</h1>
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
            @click="handleTabClick(tab.id)"
            :class="[
            'w-full flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group',
              activeTab === tab.id 
              ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-white/60'
          ]"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="tab.id === 'overview'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            <path v-else-if="tab.id === 'farms'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            <path v-else-if="tab.id === 'orders'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            <path v-else-if="tab.id === 'pregnancy'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path v-else-if="tab.id === 'profile'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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
      <header class="bg-white/60 backdrop-blur-md shadow-lg border-b border-white/20 px-4 sm:px-6 py-4 relative z-50">
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
          
          <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ tabs.find(t => t.id === activeTab)?.name }}</h2>
            <p class="text-xs text-gray-500 mt-1">RohiWala.pk</p>
          </div>
          
          <!-- Right side: Store Navigation, User Info, and Logout -->
          <div class="flex items-center space-x-2 sm:space-x-4">
            <!-- Store Navigation -->
            <div class="hidden sm:flex items-center space-x-3">
              <a 
                href="/demo" 
                target="_blank"
                class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 transition-colors"
              >
                <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>
                <span class="hidden sm:inline">Visit Store</span>
              </a>
             
            </div>
            
            <!-- User Dropdown -->
            <div class="relative border-l border-gray-200 pl-2 sm:pl-4 user-dropdown z-[100]">
              <!-- User Avatar Button -->
              <button 
                @click="showUserDropdown = !showUserDropdown"
                class="flex items-center space-x-2 sm:space-x-3 hover:bg-gray-50 rounded-lg p-1 sm:p-2 transition-colors"
              >
                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-semibold">{{ farmer?.name?.charAt(0) || 'U' }}</span>
                </div>
                <div class="hidden sm:block text-left">
                  <p class="text-sm font-medium text-gray-900">{{ farmer?.name || 'User' }}</p>
                  <p class="text-xs text-gray-500">Farmer</p>
                </div>
                <svg class="hidden sm:block w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <div 
                v-if="showUserDropdown"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-[9999]"
              >
                <div class="px-4 py-3 border-b border-gray-100">
                  <p class="text-sm font-medium text-gray-900">{{ farmer?.name || 'User' }}</p>
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
      <main class="flex-1 p-4 sm:p-6 overflow-y-auto overflow-x-hidden min-w-0">
        <!-- Overview Tab -->
      <div v-if="activeTab === 'overview'" class="space-y-6 sm:space-y-8 min-w-0">
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
            <div class="p-4 sm:p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Orders</p>
                  <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ orders.length }}</p>
                  <div class="flex items-center text-green-600 text-xs sm:text-sm mt-1">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    All Time
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
          </div>

          <!-- Farms Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-4 sm:p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">My Farms</p>
                  <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ farms.length }}</p>
                  <div class="flex items-center text-green-600 text-xs sm:text-sm mt-1">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Active
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-green-500 to-emerald-600"></div>
          </div>

          <!-- Pending Orders Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-4 sm:p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Orders</p>
                  <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ pendingOrdersCount }}</p>
                  <div class="flex items-center text-amber-600 text-xs sm:text-sm mt-1">
                    <div class="w-2 h-2 bg-amber-500 rounded-full mr-2 animate-pulse"></div>
                    In Progress
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>
          </div>

          <!-- Completed Orders Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-4 sm:p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Completed Orders</p>
                  <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ completedOrdersCount }}</p>
                  <div class="flex items-center text-green-600 text-xs sm:text-sm mt-1">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Success
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
          <div class="px-4 sm:px-6 py-4 sm:py-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-white/20">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Recent Orders
              </h3>
              <button class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- Filter Section -->
          <div class="px-4 sm:px-6 py-4 bg-gray-50/50 border-b border-white/20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Farm Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Farm</label>
                <select
                  v-model="orderFilters.farm"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Farms</option>
                  <option v-for="farm in farms" :key="farm.id" :value="farm.id">
                    {{ farm.name }}
                  </option>
                </select>
                          </div>
              
              <!-- Technician Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Technician</label>
                <select
                  v-model="orderFilters.technician"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Technicians</option>
                  <option v-for="technician in technicians" :key="technician.id" :value="technician.id">
                    {{ technician.user?.name || technician.name || 'Unknown Technician' }}
                  </option>
                </select>
              </div>
              
              <!-- Status Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Status</label>
                <select
                  v-model="orderFilters.status"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Statuses</option>
                  <option value="pending">Pending</option>
                  <option value="assigned">Assigned</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                          </div>
              
              <!-- Date Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Date</label>
                <select
                  v-model="orderFilters.dateRange"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Dates</option>
                  <option value="today">Today</option>
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                  <option value="custom">Custom Range</option>
                </select>
                        </div>
                          </div>
            
            <!-- Custom Date Range (shown when custom is selected) -->
            <div v-if="orderFilters.dateRange === 'custom'" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">From Date</label>
                <input
                  v-model="orderFilters.fromDate"
                  type="date"
                  class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                />
                          </div>
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">To Date</label>
                <input
                  v-model="orderFilters.toDate"
                  type="date"
                  class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                />
          </div>
        </div>

            <!-- Clear Filters Button -->
            <div class="mt-4 flex justify-end">
              <button
                @click="clearOrderFilters"
                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-lg transition-colors"
              >
                Clear All Filters
              </button>
            </div>
          </div>
          
            <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="min-w-full">
              <thead class="bg-gray-50/50">
                <tr>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order #</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Farm</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Technician</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ETA</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Visit Fee</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                  <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="order in paginatedOrders" :key="order.id" class="hover:bg-gray-50/50 transition-colors duration-150">
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(order.created_at) }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-white text-xs font-bold">#</span>
                      </div>
                      <span class="text-sm font-semibold text-gray-900">{{ order.order_number }}</span>
                    </div>
                    </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-green-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                      </div>
                      <span class="text-sm font-medium text-gray-900">{{ order.cattle_farm?.name }}</span>
                    </div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <div v-if="order.jobs?.[0]?.technician">
                        <div class="font-medium">{{ order.jobs[0].technician.user.name }}</div>
                        <div class="text-xs text-gray-500">{{ order.jobs[0].technician.user.mobile || 'Phone not available' }}</div>
                      </div>
                      <div v-else class="text-gray-500">Not Assigned</div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <div v-if="order.jobs?.[0]?.estimated_visit_at">
                        <div class="text-sm font-medium">{{ formatETADate(order.jobs[0].estimated_visit_at) }}</div>
                        <div class="text-xs text-gray-500">{{ formatETATime(order.jobs[0].estimated_visit_at) }}</div>
                      </div>
                      <div v-else class="text-sm text-gray-400">
                        Not set
                      </div>
                    </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-900">₨{{ order.total_amount }}</span>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-bold text-gray-900">₨{{ order.visit_fee || '0.00' }}</span>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusBadgeClass(order.status)" class="px-3 py-1 inline-flex text-xs font-semibold rounded-full">
                        {{ order.status }}
                      </span>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div class="flex space-x-2">
                        <button 
                          @click="viewOrderDetails(order)"
                          class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-colors"
                          title="View Order Details"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                          </svg>
                        </button>
                        <button 
                          v-if="order.jobs?.[0]?.technician"
                          @click="callTechnician(order.jobs[0].technician)"
                          class="p-1 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                          title="Call Technician"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                          </svg>
                        </button>
                        <button 
                          v-if="order.jobs?.[0]?.technician"
                          @click="messageTechnician(order.jobs[0].technician)"
                          class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                          title="Message Technician"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
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
          
          <!-- Scroll indicator for mobile -->
          <div class="lg:hidden px-4 py-2 bg-gray-50/50 border-t border-white/20">
            <div class="flex items-center justify-center text-xs text-gray-500">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
              </svg>
              Swipe to see more columns
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
              </svg>
            </div>
          </div>
          
          <!-- Pagination Controls -->
          <div v-if="ordersPagination.total > 0" class="px-6 py-4 bg-gray-50/50 border-t border-white/20">
            <div class="flex items-center justify-between">
              <!-- Pagination Info -->
              <div class="text-sm text-gray-600">
                Showing {{ paginationInfo.start }} to {{ paginationInfo.end }} of {{ paginationInfo.total }} orders
              </div>
              
              <!-- Pagination Controls -->
              <div v-if="totalPages > 1" class="flex items-center space-x-2">
                <!-- Previous Button -->
                <button
                  @click="prevPage"
                  :disabled="ordersPagination.current_page === 1"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Previous
                </button>
                
                <!-- Page Numbers -->
                <div class="flex space-x-1">
                  <button
                    v-for="page in Math.min(5, totalPages)"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                      'px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                      ordersPagination.current_page === page
                        ? 'bg-green-600 text-white'
                        : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                  
                  <!-- Show ellipsis if there are more pages -->
                  <span v-if="totalPages > 5" class="px-3 py-2 text-sm text-gray-500">...</span>
                  
                  <!-- Show last page if it's not in the first 5 -->
                  <button
                    v-if="totalPages > 5 && ordersPagination.current_page < totalPages - 2"
                    @click="goToPage(totalPages)"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                  >
                    {{ totalPages }}
                  </button>
                </div>
                
                <!-- Next Button -->
                <button
                  @click="nextPage"
                  :disabled="ordersPagination.current_page === totalPages"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Next
                </button>
              </div>
              
              <!-- Show message when there's only one page -->
              <div v-else class="text-sm text-gray-500">
                All orders displayed
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Farms Tab -->
      <div v-if="activeTab === 'farms'" class="space-y-8 min-w-0">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">My Farms</h2>
            <p class="text-gray-600 mt-1">Manage your farm locations and livestock</p>
          </div>
          <button @click="showAddFarmModal = true" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Farm
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="farm in farms" :key="farm.id" class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-bold text-gray-900">{{ farm.name }}</h3>
                </div>
                <div class="flex space-x-2">
                  <button @click="editFarm(farm)" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200" title="Edit Farm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button @click="deleteFarm(farm)" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200" title="Delete Farm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="space-y-3">
                <div class="flex justify-between items-center py-2 px-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg">
                  <div class="flex items-center">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-600">Cows</span>
                  </div>
                  <span class="text-lg font-bold text-gray-900">{{ farm.number_of_cows }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                  <div class="flex items-center">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-600">Calves</span>
                  </div>
                  <span class="text-lg font-bold text-gray-900">{{ farm.number_of_calves }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg">
                  <div class="flex items-center">
                    <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-600">Bulls</span>
                  </div>
                  <span class="text-lg font-bold text-gray-900">{{ farm.number_of_bulls }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-gradient-to-r from-purple-50 to-violet-50 rounded-lg">
                  <div class="flex items-center">
                    <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-600">Goats</span>
                  </div>
                  <span class="text-lg font-bold text-gray-900">{{ farm.number_of_goats }}</span>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-green-500 to-emerald-600"></div>
          </div>
        </div>
      </div>

      <!-- Orders Tab -->
      <div v-if="activeTab === 'orders'" class="space-y-6 min-w-0">
        

        <!-- Orders Filter Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Farm Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Farm</label>
                <select
                  v-model="orderFilters.farm"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Farms</option>
                  <option v-for="farm in farms" :key="farm.id" :value="farm.id">
                    {{ farm.name }}
                  </option>
                </select>
              </div>
              
              <!-- Technician Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Technician</label>
                <select
                  v-model="orderFilters.technician"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Technicians</option>
                  <option v-for="technician in technicians" :key="technician.id" :value="technician.id">
                    {{ technician.user?.name || technician.name || 'Unknown Technician' }}
                  </option>
                </select>
              </div>
              
              <!-- Status Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Status</label>
                <select
                  v-model="orderFilters.status"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Statuses</option>
                  <option value="pending">Pending</option>
                  <option value="assigned">Assigned</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
              
              <!-- Date Filter -->
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Filter by Date</label>
                <select
                  v-model="orderFilters.dateRange"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                  <option value="">All Dates</option>
                  <option value="today">Today</option>
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                  <option value="custom">Custom Range</option>
                </select>
              </div>
            </div>
            
            <!-- Custom Date Range (shown when custom is selected) -->
            <div v-if="orderFilters.dateRange === 'custom'" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">From Date</label>
                <input
                  v-model="orderFilters.fromDate"
                  type="date"
                  class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">To Date</label>
                <input
                  v-model="orderFilters.toDate"
                  type="date"
                  class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                />
              </div>
            </div>
            
            <!-- Clear Filters Button -->
            <div class="mt-4 flex justify-end">
              <button
                @click="clearOrderFilters"
                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-lg transition-colors"
              >
                Clear All Filters
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETA</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Fee</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="order in filteredOrders" :key="order.id">
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(order.created_at) }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ order.order_number }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ order.cattle_farm?.name }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div v-if="order.jobs && order.jobs.length > 0">
                      <div class="font-medium">{{ order.jobs[0].technician.user.name }}</div>
                      <div class="text-xs text-gray-500">{{ order.jobs[0].technician.user.mobile || 'Phone not available' }}</div>
                    </div>
                    <div v-else class="text-gray-400">Not assigned</div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div v-if="order.jobs?.[0]?.estimated_visit_at">
                      <div class="text-sm font-medium">{{ formatETADate(order.jobs[0].estimated_visit_at) }}</div>
                      <div class="text-xs text-gray-500">{{ formatETATime(order.jobs[0].estimated_visit_at) }}</div>
                    </div>
                    <div v-else class="text-sm text-gray-400">
                      Not set
                    </div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ order.order_items?.length || 0 }} items
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ₨{{ order.total_amount }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ₨{{ order.visit_fee || '0.00' }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusBadgeClass(order.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex space-x-2">
                      <button 
                        @click="viewOrderDetails(order)"
                        class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-colors"
                        title="View Order Details"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                      <button 
                        v-if="order.jobs?.[0]?.technician"
                        @click="callTechnician(order.jobs[0].technician)"
                        class="p-1 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                        title="Call Technician"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                      </button>
                      <button 
                        v-if="order.jobs?.[0]?.technician"
                        @click="messageTechnician(order.jobs[0].technician)"
                        class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                        title="Message Technician"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                      </button>
                      <button 
                        v-if="order.jobs && order.jobs.length > 0 && order.jobs[0].status === 'completed'"
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
          
          <!-- Scroll indicator for mobile -->
          <div class="lg:hidden px-4 py-2 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-center text-xs text-gray-500">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
              </svg>
              Swipe to see more columns
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Pregnancy Tracking Tab -->
      <div v-if="activeTab === 'pregnancy'" class="space-y-6 min-w-0">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Pregnancy Tracking</h2>
          <button @click="() => goToPregnancyTracking()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
            View Full Tracking
          </button>
        </div>
        
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
          <p class="text-gray-600 mb-4">Track pregnancy outcomes and deliveries for your cattle.</p>
          <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Pregnancy Tracking</h3>
            <p class="mt-1 text-sm text-gray-500">Track your cattle's pregnancy journey from insemination to delivery.</p>
            <div class="mt-6">
              <button
                @click="() => goToPregnancyTracking()"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
              >
                Open Pregnancy Tracking
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Profile Tab -->
      <div v-if="activeTab === 'profile'" class="space-y-6 min-w-0">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Profile Information</h3>
            <form @submit.prevent="updateProfile" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Name</label>
                  <input v-model="profileForm.name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                  <input v-model="profileForm.date_of_birth" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Mobile</label>
                <input v-model="profileForm.mobile" type="text" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
              </div>
              <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                  Update Profile
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    </div>

    <!-- Add Farm Modal -->
    <div v-if="showAddFarmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Farm</h3>
          <form @submit.prevent="addFarm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Farm Name</label>
                <input v-model="farmForm.name" type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter farm name">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
                <input v-model="farmForm.father_name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter father's name (optional)">
              </div>
            </div>

            <!-- Google Maps Location Picker -->
            <div class="bg-gray-50 p-4 rounded-xl">
              <GoogleMapLocationPicker
                v-model="farmLocation"
                @update:modelValue="updateFarmLocation"
              />
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Cows</label>
                <input v-model="farmForm.number_of_cows" type="number" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Calves</label>
                <input v-model="farmForm.number_of_calves" type="number" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Bulls</label>
                <input v-model="farmForm.number_of_bulls" type="number" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Goats</label>
                <input v-model="farmForm.number_of_goats" type="number" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0">
              </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button type="button" @click="showAddFarmModal = false" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-400 transition-colors duration-200">
                Cancel
              </button>
              <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors duration-200">
                Add Farm
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Farm Modal -->
    <div v-if="showEditFarmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Edit Farm</h2>
            <button @click="showEditFarmModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form @submit.prevent="updateFarm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Name *</label>
                <input
                  v-model="farmForm.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  placeholder="Enter farm name"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Father's Name</label>
                <input
                  v-model="farmForm.father_name"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  placeholder="Enter father's name (optional)"
                />
              </div>
            </div>

            <!-- Google Maps Location Picker -->
            <div class="bg-gray-50 p-4 rounded-xl">
              <GoogleMapLocationPicker
                v-model="farmLocation"
                :initial-lat="farmForm.latitude"
                :initial-lng="farmForm.longitude"
                @update:modelValue="updateFarmLocation"
              />
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Cows *</label>
                <input
                  v-model.number="farmForm.number_of_cows"
                  type="number"
                  min="0"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Calves *</label>
                <input
                  v-model.number="farmForm.number_of_calves"
                  type="number"
                  min="0"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Bulls *</label>
                <input
                  v-model.number="farmForm.number_of_bulls"
                  type="number"
                  min="0"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Goats *</label>
                <input
                  v-model.number="farmForm.number_of_goats"
                  type="number"
                  min="0"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                />
              </div>
            </div>

            <div class="flex justify-end space-x-4">
              <button
                type="button"
                @click="showEditFarmModal = false"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium"
              >
                Update Farm
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Technician Details Modal -->
    <div v-if="showTechnicianModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Technician Details</h2>
            <button @click="showTechnicianModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div v-if="selectedTechnician" class="space-y-6">
            <!-- Technician Profile -->
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900">{{ selectedTechnician.user.name }}</h3>
              <p class="text-gray-600">Assigned Technician</p>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Contact Information</h4>
                <div class="space-y-3">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Phone Number</p>
                      <p class="text-lg font-semibold text-gray-700">{{ selectedTechnician.user.mobile || 'Not available' }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Email Address</p>
                      <p class="text-sm text-gray-700">{{ selectedTechnician.user.email || 'Not available' }}</p>
                    </div>
                  </div>
                </div>
              </div>

              
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Details Modal -->
    <div v-if="showOrderDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Order Details</h2>
            <button 
              @click="showOrderDetailsModal = false"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div v-if="loadingOrderDetails" class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto mb-4"></div>
            <p class="text-gray-500">Loading order details...</p>
          </div>
          
          <div v-if="!loadingOrderDetails && !selectedOrderDetails" class="text-center py-8">
            <p class="text-gray-500">No order details available</p>
          </div>
          
          <div v-if="selectedOrderDetails" class="space-y-6">
            
            <!-- Order Information -->
            <div class="bg-gray-50 p-6 rounded-lg">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-gray-500">Order Number</label>
                  <p class="text-lg font-semibold text-gray-900">{{ selectedOrderDetails.order_number }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Order Date</label>
                  <p class="text-lg font-semibold text-gray-900">{{ formatDateTime(selectedOrderDetails.created_at) }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Total Amount</label>
                  <p class="text-lg font-semibold text-green-600">₨{{ selectedOrderDetails.total_amount }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Visit Fee</label>
                  <p class="text-lg font-semibold text-blue-600">₨{{ selectedOrderDetails.visit_fee || '0.00' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Status</label>
                  <span :class="getStatusBadgeClass(selectedOrderDetails.status)" class="px-3 py-1 inline-flex text-sm font-semibold rounded-full">
                    {{ selectedOrderDetails.status }}
                  </span>
                </div>
              </div>
              <div v-if="selectedOrderDetails.notes" class="mt-4">
                <label class="text-sm font-medium text-gray-500">Order Notes</label>
                <p class="text-gray-900 mt-1">{{ selectedOrderDetails.notes }}</p>
              </div>
            </div>

            <!-- Farm Information -->
            <div v-if="selectedOrderDetails.cattle_farm" class="bg-blue-50 p-6 rounded-lg">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Farm Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-gray-500">Farm Name</label>
                  <p class="text-lg font-semibold text-gray-900">{{ selectedOrderDetails.cattle_farm.name }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Farm Owner Name</label>
                  <p class="text-lg font-semibold text-gray-900">{{ selectedOrderDetails.cattle_farm.father_name || 'Not provided' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Number of Cows</label>
                  <p class="text-lg font-semibold text-gray-900">{{ selectedOrderDetails.cattle_farm.number_of_cows }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500">Number of Calves</label>
                  <p class="text-lg font-semibold text-gray-900">{{ selectedOrderDetails.cattle_farm.number_of_calves }}</p>
                </div>
              </div>
            </div>

            <!-- Technician Information -->
            <div v-if="selectedOrderDetails.jobs && selectedOrderDetails.jobs.length > 0" class="bg-yellow-50 p-6 rounded-lg">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Technician Information</h3>
              <div v-for="(job, index) in selectedOrderDetails.jobs" :key="index"  class="bg-white p-4 rounded-lg border mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                      <label class="text-sm font-medium text-gray-500">Technician Name</label>
                      <p class="text-lg font-semibold text-gray-900">{{ job.technician?.name || 'Not assigned' }}</p>
                      <p v-if="job.technician?.user?.mobile" class="text-sm text-gray-600">{{ job.technician.user.mobile }}</p>
                    </div>
                    <div v-if="job.technician?.years_of_experience">
                      <label class="text-sm font-medium text-gray-500">Experience</label>
                      <p class="text-sm text-gray-900">{{ job.technician.years_of_experience }} years</p>
                    </div>
                    <div v-if="job.technician?.areas_of_expertise">
                      <label class="text-sm font-medium text-gray-500">Areas of Expertise</label>
                      <p class="text-sm text-gray-900">{{ job.technician.areas_of_expertise }}</p>
                    </div>
                    <div v-if="job.technician?.clinic_address">
                      <label class="text-sm font-medium text-gray-500">Clinic Address</label>
                      <p class="text-sm text-gray-900">{{ job.technician.clinic_address }}</p>
                    </div>
                  </div>
                
                <!-- ETA Information Display -->
                <div v-if="job.estimated_visit_at" class="mb-4 bg-green-50 p-4 rounded-lg">
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
                
                <div v-if="job.actual_visit_at" class="mb-4">
                  <label class="text-sm font-medium text-gray-500">Actual Visit Date</label>
                  <p class="text-sm font-semibold text-gray-900">{{ formatDateTime(job.actual_visit_at) }}</p>
                </div>
                
                <div v-if="job.technician_notes && !job.estimated_visit_at" class="mb-4">
                  <label class="text-sm font-medium text-gray-500">Technician Notes</label>
                  <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ job.technician_notes }}</p>
                </div>
                
                <div v-if="job.farmer_notes" class="mb-4">
                  <label class="text-sm font-medium text-gray-500">Farmer Notes</label>
                  <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ job.farmer_notes }}</p>
                </div>
              </div>
            </div>

            <!-- Cow Insemination Details -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Cow Insemination Details</h3>
              <div v-if="selectedOrderDetails.order_items?.length" class="space-y-4">
                <div v-for="(item, index) in selectedOrderDetails.order_items" :key="item.id" class="border border-gray-200 rounded-lg p-4">
                  <h4 class="text-md font-semibold text-gray-800 mb-3">
                    Cow {{ index + 1 }} - {{ item.bull_semen?.bull_name || 'N/A' }}
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
                  <div v-if="selectedOrderDetails.jobs && selectedOrderDetails.jobs[0]?.cow_insemination_records && selectedOrderDetails.jobs[0].cow_insemination_records.find(record => record.order_item_id === item.id)" class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="text-sm font-semibold text-gray-700 mb-3">Visit Record</h5>
                    <div v-for="record in selectedOrderDetails.jobs[0].cow_insemination_records.filter(record => record.order_item_id === item.id)" :key="record.id" class="space-y-3">
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

            

          </div>
        </div>
      </div>
    </div>

    <!-- Image Modal -->
    <div v-if="showImageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900">{{ selectedImageTitle }}</h3>
          <button @click="closeImageModal" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4">
          <img :src="selectedImageUrl" :alt="selectedImageTitle" class="max-w-full max-h-[70vh] object-contain mx-auto">
        </div>
      </div>
    </div>
    
    <!-- Notification -->
    <div v-if="notification.show" class="fixed top-4 right-4 z-50 max-w-sm w-full">
      <div :class="[
        'rounded-lg shadow-lg p-4 flex items-center',
        notification.type === 'success' ? 'bg-green-500 text-white' : 
        notification.type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
      ]">
        <div class="flex-1">
          <p class="text-sm font-medium">{{ notification.message }}</p>
        </div>
        <button @click="notification.show = false" class="ml-4 text-white hover:text-gray-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Profile Completion Alert -->
    <div v-if="profileCompletionAlert.show" class="fixed top-4 left-4 right-4 z-50 max-w-4xl mx-auto">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-lg p-4 flex items-center">
        <div class="flex-shrink-0 mr-4">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-semibold mb-1">Welcome to RohiWala.pk!</h3>
          <p class="text-sm">{{ profileCompletionAlert.message }}</p>
        </div>
        <button @click="dismissProfileAlert" class="ml-4 text-white hover:text-gray-200 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import GoogleMapLocationPicker from '@/components/GoogleMapLocationPicker.vue'

export default {
  name: 'FarmerDashboard',
  components: {
    GoogleMapLocationPicker
  },
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    
    const activeTab = ref('overview')
    const isSidebarCollapsed = ref(true)
    const isStatsCardsCollapsed = ref(false)
    const farmer = ref(null)
    const farms = ref([])
    const orders = ref([])
    const ordersPagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      data: []
    })
    const showAddFarmModal = ref(false)
    const showEditFarmModal = ref(false)
    const showOrderModal = ref(false)
    const showTechnicianModal = ref(false)
    const showOrderDetailsModal = ref(false)
    const loadingOrderDetails = ref(false)
    const showUserDropdown = ref(false)
    const editingFarm = ref(null)
    const selectedTechnician = ref(null)
    const selectedOrderDetails = ref(null)
    
    // Image modal state
    const showImageModal = ref(false)
    const selectedImageUrl = ref('')
    const selectedImageTitle = ref('')
    
    // Visit address cache
    const visitAddressCache = ref({})
    
    // Profile completion alert state
    const profileCompletionAlert = ref({
      show: false,
      message: 'Welcome! Please complete your profile to get started. Fill in all required information to access full features.'
    })
    
    // Notification state
    const notification = ref({
      show: false,
      message: '',
      type: 'success' // success, error, info
    })
    
    const farmForm = ref({
      name: '',
      father_name: '',
      latitude: '',
      longitude: '',
      number_of_cows: 0,
      number_of_calves: 0,
      number_of_bulls: 0,
      number_of_goats: 0
    })
    
    const farmLocation = ref({
      lat: 31.5204, // Default to Lahore, Pakistan
      lng: 74.3587
    })
    
    const profileForm = ref({
      name: '',
      mobile: '',
      date_of_birth: ''
    })
    
    const tabs = [
      { id: 'overview', name: 'Overview' },
      { id: 'farms', name: 'My Farms' },
      // { id: 'orders', name: 'Orders' }, // Commented out as requested
      { id: 'pregnancy', name: 'Pregnancy Tracking' },
      { id: 'profile', name: 'Profile' }
    ]
    
    const pendingOrdersCount = computed(() => 
      orders.value.filter(order => ['pending', 'assigned', 'in_progress'].includes(order.status)).length
    )
    
    const completedOrdersCount = computed(() => 
      orders.value.filter(order => order.status === 'completed').length
    )
    
    const recentOrders = computed(() => 
      orders.value.slice(0, 5)
    )
    
    const activeOrders = computed(() => 
      orders.value.filter(order => ['pending', 'assigned', 'in_progress'].includes(order.status))
    )
    
    // Filtered orders based on filter criteria
    const filteredOrders = computed(() => {
      let filtered = orders.value
      
      // Filter by farm
      if (orderFilters.value.farm) {
        filtered = filtered.filter(order => order.cattle_farm?.id == orderFilters.value.farm)
      }
      
      // Filter by technician
      if (orderFilters.value.technician) {
        filtered = filtered.filter(order => 
          order.jobs && order.jobs.some(job => job.technician?.id == orderFilters.value.technician)
        )
      }
      
      // Filter by status
      if (orderFilters.value.status) {
        filtered = filtered.filter(order => order.status === orderFilters.value.status)
      }
      
      // Filter by date range
      if (orderFilters.value.dateRange) {
        const now = new Date()
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
        
        filtered = filtered.filter(order => {
          const orderDate = new Date(order.created_at)
          const orderDateOnly = new Date(orderDate.getFullYear(), orderDate.getMonth(), orderDate.getDate())
          
          switch (orderFilters.value.dateRange) {
            case 'today':
              return orderDateOnly.getTime() === today.getTime()
            case 'week':
              const weekAgo = new Date(today)
              weekAgo.setDate(today.getDate() - 7)
              return orderDateOnly >= weekAgo
            case 'month':
              const monthAgo = new Date(today)
              monthAgo.setMonth(today.getMonth() - 1)
              return orderDateOnly >= monthAgo
            case 'custom':
              if (orderFilters.value.fromDate && orderFilters.value.toDate) {
                const fromDate = new Date(orderFilters.value.fromDate)
                const toDate = new Date(orderFilters.value.toDate)
                return orderDateOnly >= fromDate && orderDateOnly <= toDate
              }
              return true
            default:
              return true
          }
        })
      }
      
      return filtered
    })
    
    // Pagination computed properties (server-side)
    const totalPages = computed(() => {
      return ordersPagination.value.last_page || 1
    })
    
    const paginatedOrders = computed(() => {
      return ordersPagination.value.data || []
    })
    
    const paginationInfo = computed(() => {
      const start = (ordersPagination.value.current_page - 1) * ordersPagination.value.per_page + 1
      const end = Math.min(ordersPagination.value.current_page * ordersPagination.value.per_page, ordersPagination.value.total)
      return {
        start,
        end,
        total: ordersPagination.value.total || 0
      }
    })
    
    // Order filtering variables
    const orderFilters = ref({
      farm: '',
      technician: '',
      status: '',
      dateRange: '',
      fromDate: '',
      toDate: ''
    })
    
    // Pagination variables
    const currentPage = ref(1)
    const itemsPerPage = ref(10)
    
    const technicians = ref([])
    
    // Pagination methods (server-side)
    const goToPage = async (page) => {
      if (page >= 1 && page <= totalPages.value) {
        await loadOrdersWithPagination(page)
      }
    }
    
    const nextPage = async () => {
      if (ordersPagination.value.current_page < totalPages.value) {
        await loadOrdersWithPagination(ordersPagination.value.current_page + 1)
      }
    }
    
    const prevPage = async () => {
      if (ordersPagination.value.current_page > 1) {
        await loadOrdersWithPagination(ordersPagination.value.current_page - 1)
      }
    }
    
    // Reset pagination when filters change
    watch(orderFilters, () => {
      loadOrdersWithPagination(1)
    }, { deep: true })
    
    const loadProfile = async () => {
      try {
        const response = await window.axios.get('/farmer/profile')
        farmer.value = response.data.farmer
        profileForm.value = {
          name: farmer.value.name,
          mobile: farmer.value.mobile,
          date_of_birth: farmer.value.date_of_birth
        }
      } catch (error) {
        console.error('Error loading profile:', error)
      }
    }
    
    const loadFarms = async () => {
      try {
        const response = await window.axios.get('/farmer/farms')
        farms.value = response.data.farms
      } catch (error) {
        console.error('Error loading farms:', error)
      }
    }
    
   
    
    const loadOrdersWithPagination = async (page = 1) => {
      try {
        const params = new URLSearchParams()
        params.append('page', page)
        params.append('per_page', '10')
        
        // Add filter parameters
        if (orderFilters.value.farm) {
          params.append('farm', orderFilters.value.farm)
        }
        
        if (orderFilters.value.technician) {
          params.append('technician', orderFilters.value.technician)
        }
        
        if (orderFilters.value.status) {
          params.append('status', orderFilters.value.status)
        }
        
        if (orderFilters.value.dateRange) {
          params.append('date_range', orderFilters.value.dateRange)
          
          if (orderFilters.value.dateRange === 'custom' && orderFilters.value.fromDate && orderFilters.value.toDate) {
            params.append('from_date', orderFilters.value.fromDate)
            params.append('to_date', orderFilters.value.toDate)
          }
        }
        
        const response = await window.axios.get(`/farmer/orders?${params}`)
        console.log('Pagination response:', response.data.orders)
        ordersPagination.value = response.data.orders
        
        // Always extract technicians from orders after loading orders
        extractTechniciansFromOrders()
      } catch (error) {
        console.error('Error loading orders with pagination:', error)
      }
    }
    
    // Extract technicians from orders
    const extractTechniciansFromOrders = () => {
      const uniqueTechnicians = new Map()
      
      // Use paginated orders for technician extraction
      const ordersToProcess = ordersPagination.value.data || orders.value
      ordersToProcess.forEach(order => {
        // Check all jobs in the order, not just the first one
        if (order.jobs && Array.isArray(order.jobs)) {
          order.jobs.forEach(job => {
            if (job.technician && job.technician.id) {
              const tech = job.technician
              if (!uniqueTechnicians.has(tech.id)) {
                uniqueTechnicians.set(tech.id, tech)
              }
            }
          })
        }
      })
      
      technicians.value = Array.from(uniqueTechnicians.values())
      console.log('Extracted technicians from orders:', technicians.value.length, 'technicians found')
    }
    
    const addFarm = async () => {
      try {
        await window.axios.post('/farmer/farms', farmForm.value)
        showAddFarmModal.value = false
        farmForm.value = {
          name: '',
          father_name: '',
          latitude: '',
          longitude: '',
          number_of_cows: 0,
          number_of_calves: 0,
          number_of_bulls: 0,
          number_of_goats: 0
        }
        loadFarms()
      } catch (error) {
        console.error('Error adding farm:', error)
      }
    }
    
    const editFarm = (farm) => {
      editingFarm.value = farm
      farmForm.value = {
        name: farm.name,
        father_name: farm.father_name || '',
        latitude: farm.latitude,
        longitude: farm.longitude,
        number_of_cows: farm.number_of_cows,
        number_of_calves: farm.number_of_calves,
        number_of_bulls: farm.number_of_bulls,
        number_of_goats: farm.number_of_goats
      }
      farmLocation.value = {
        lat: parseFloat(farm.latitude) || 31.5204,
        lng: parseFloat(farm.longitude) || 74.3587
      }
      showEditFarmModal.value = true
    }
    
    const updateFarm = async () => {
      try {
        await window.axios.put(`/farmer/farms/${editingFarm.value.id}`, farmForm.value)
        showEditFarmModal.value = false
        editingFarm.value = null
        farmForm.value = {
          name: '',
          father_name: '',
          latitude: '',
          longitude: '',
          number_of_cows: 0,
          number_of_calves: 0,
          number_of_bulls: 0,
          number_of_goats: 0
        }
        loadFarms()
        showSuccess('Farm updated successfully!')
      } catch (error) {
        console.error('Error updating farm:', error)
        showError('Failed to update farm. Please try again.')
      }
    }
    
    const deleteFarm = async (farm) => {
      const result = await window.confirm(
        `Are you sure you want to delete "${farm.name}"? This action cannot be undone.`,
        'Delete Farm'
      )
      
      if (result.isConfirmed) {
        try {
          await window.axios.delete(`/farmer/farms/${farm.id}`)
          loadFarms()
          showSuccess('Farm deleted successfully!')
        } catch (error) {
          console.error('Error deleting farm:', error)
          showError('Failed to delete farm. Please try again.')
        }
      }
    }
    
    const updateProfile = async () => {
      try {
        await window.axios.put('/farmer/profile', profileForm.value)
        loadProfile()
        showNotification('Profile updated successfully!', 'success')
      } catch (error) {
        console.error('Error updating profile:', error)
        showNotification('Failed to update profile. Please try again.', 'error')
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
    
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('en-GB', {
        timeZone: 'Asia/Karachi'
      })
    }
    
    const formatETADate = (eta) => {
      if (!eta) return 'N/A'
      return new Date(eta).toLocaleDateString('en-GB', {
        timeZone: 'Asia/Karachi'
      })
    }
    
    const formatETATime = (eta) => {
      if (!eta) return 'N/A'
      return new Date(eta).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Karachi'
      })
    }
    
    const formatDateTime = (date) => {
      if (!date) return 'Not specified'
      
      try {
        // Extract time exactly as stored in API (no timezone conversion)
        const dateStr = date.toString()
        
        // If it's in ISO format like "2025-10-16T11:14:00.000000Z"
        if (dateStr.includes('T') && dateStr.includes('Z')) {
          const [datePart, timePart] = dateStr.split('T')
          const time = timePart.split('.')[0] // Remove milliseconds
          const [year, month, day] = datePart.split('-')
          const [hour, minute] = time.split(':')
          
          // Format as DD/MM/YYYY, HH:MM
          return `${day}/${month}/${year}, ${hour}:${minute}`
        }
        
        // Fallback to regular date formatting
        const d = new Date(date)
        if (isNaN(d.getTime())) {
          return 'Invalid date'
        }
        
        const formatted = d.toLocaleString('en-GB', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        })
        
        return formatted
      } catch (error) {
        console.error('Error formatting date:', error, 'Original date:', date)
        return 'Invalid date'
      }
    }
    
    const logout = async () => {
      if (authStore.isLoggingOut) {
        return // Prevent multiple logout calls
      }
      
      await authStore.logout()
      // Use absolute path to ensure we go to /demo/login
      router.push('/login')
    }
    
    const goToSemenCatalog = () => {
      window.open('/demo/products', '_blank')
    }
    
    const goToServiceConfirmation = (jobId) => {
      router.push(`/farmer/service-confirmation/${jobId}`)
    }
    
    const goToPregnancyTracking = (jobId = null) => {
      console.log('🔍 goToPregnancyTracking called with:', jobId, typeof jobId)
      
      // Check if jobId is a valid number or string, not an event object
      if (jobId && typeof jobId === 'number' || (typeof jobId === 'string' && !isNaN(jobId))) {
        console.log('📋 Navigating to specific job:', jobId)
        router.push(`/farmer/pregnancy-tracking?job=${jobId}`)
      } else {
        console.log('📋 Navigating to general pregnancy tracking')
        router.push('/farmer/pregnancy-tracking')
      }
    }
    
    const updateFarmLocation = (location) => {
      farmForm.value.latitude = location.lat
      farmForm.value.longitude = location.lng
    }
    
    const callTechnician = (technician) => {
      selectedTechnician.value = technician
      showTechnicianModal.value = true
    }
    
    const messageTechnician = (technician) => {
      selectedTechnician.value = technician
      showTechnicianModal.value = true
    }
    
    const initiateCall = (technician) => {
      if (technician.user.mobile) {
        window.open(`tel:${technician.user.mobile}`, '_self')
        showTechnicianModal.value = false
      } else {
        showInfo('Technician phone number not available')
      }
    }
    
    const initiateMessage = (technician) => {
      if (technician.user.mobile) {
        const message = `Hello ${technician.user.name}, I have a question about my order.`
        window.open(`sms:${technician.user.mobile}?body=${encodeURIComponent(message)}`, '_self')
        showTechnicianModal.value = false
      } else {
        showInfo('Technician phone number not available')
      }
    }
    
    const dismissProfileAlert = () => {
      profileCompletionAlert.value.show = false
      localStorage.removeItem('showProfileCompletionAlert')
    }
    
    const toggleSidebar = () => {
      isSidebarCollapsed.value = !isSidebarCollapsed.value
    }
    
    const toggleStatsCards = () => {
      isStatsCardsCollapsed.value = !isStatsCardsCollapsed.value
    }
    
    const handleTabClick = (tabId) => {
      activeTab.value = tabId
      // Close sidebar on mobile/tablet after clicking
      if (window.innerWidth < 1024) {
        isSidebarCollapsed.value = true
      }
    }
    
    const showNotification = (message, type = 'success') => {
      notification.value = {
        show: true,
        message,
        type
      }
      // Auto hide after 5 seconds
      setTimeout(() => {
        notification.value.show = false
      }, 5000)
    }
    
    // View order details
    const viewOrderDetails = async (order) => {
      try {
        loadingOrderDetails.value = true
        selectedOrderDetails.value = null
        showOrderDetailsModal.value = true
        
        console.log('Loading order details for order:', order.id)
        const response = await window.axios.get(`/farmer/orders/${order.id}`)
        console.log('Order details response:', response.data)
        
        selectedOrderDetails.value = response.data.order
        
        // Trigger reverse geocoding for visit locations
        if (selectedOrderDetails.value.jobs) {
          selectedOrderDetails.value.jobs.forEach(job => {
            if (job.visit_latitude && job.visit_longitude) {
              reverseGeocodeVisitLocation(job.visit_latitude, job.visit_longitude)
            }
          })
        }
      } catch (error) {
        console.error('Error loading order details:', error)
        showNotification('Failed to load order details. Please try again.', 'error')
      } finally {
        loadingOrderDetails.value = false
      }
    }
    
    // Clear order filters function
    const clearOrderFilters = async () => {
      orderFilters.value = {
        farm: '',
        technician: '',
        status: '',
        dateRange: '',
        fromDate: '',
        toDate: ''
      }
      
      // Reload data without filters
      await loadOrdersWithPagination(1)
    }
    
    // Load technicians for filter dropdown
    const loadTechnicians = async () => {
      // Since there's no general /api/technicians endpoint, 
      // we'll extract technicians from orders data
      // This approach is actually better as it only shows technicians the farmer has worked with
      extractTechniciansFromOrders()
    }
    
    onMounted(() => {
      // Check URL parameters for tab switching
      const urlParams = new URLSearchParams(window.location.search)
      const tabParam = urlParams.get('tab')
      if (tabParam === 'profile') {
        activeTab.value = 'profile'
      }
      
      loadProfile()
      loadFarms()
      loadOrdersWithPagination(1) // Load paginated orders for the table
      loadTechnicians()
      
      // Check if user should see profile completion alert
      if (localStorage.getItem('showProfileCompletionAlert') === 'true') {
        profileCompletionAlert.value.show = true
      }
      
      // Close dropdown when clicking outside
      document.addEventListener('click', (event) => {
        if (!event.target.closest('.user-dropdown')) {
          showUserDropdown.value = false
        }
      })
    })

    // Helper functions for visit information and images
    const getImageUrl = (imagePath) => {
      if (!imagePath) return ''
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

    const openImageModal = (imageUrl, title) => {
      selectedImageUrl.value = imageUrl
      selectedImageTitle.value = title
      showImageModal.value = true
    }

    const closeImageModal = () => {
      showImageModal.value = false
      selectedImageUrl.value = ''
      selectedImageTitle.value = ''
    }

    const getVisitAddress = (lat, lng) => {
      if (!lat || !lng) return ''
      const key = `${lat},${lng}`
      return visitAddressCache.value[key] || ''
    }

    const openInGoogleMaps = (lat, lng) => {
      if (!lat || !lng) return
      const url = `https://www.google.com/maps?q=${lat},${lng}`
      window.open(url, '_blank')
    }

    const reverseGeocodeVisitLocation = async (lat, lng) => {
      if (!lat || !lng) return ''
      
      const key = `${lat},${lng}`
      if (visitAddressCache.value[key]) return visitAddressCache.value[key]
      
      try {
        // Using Nominatim (OpenStreetMap) - free geocoding service
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        const data = await response.json()
        
        if (data && data.display_name) {
          const address = data.display_name
          visitAddressCache.value[key] = address
          return address
        }
      } catch (error) {
        console.error('Geocoding error:', error)
      }
      
      return ''
    }

    const getJobStatusBadgeClass = (status) => {
      const classes = {
        pending: 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
        accepted: 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
        completed: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        rejected: 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
        administered: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        failed: 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
        failed_no_heat: 'bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 border border-orange-200'
      }
      return classes[status] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }

    const getCowStatusBadgeClass = (status) => {
      const classes = {
        pregnant: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        not_pregnant: 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
        in_heat: 'bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800 border border-pink-200',
        not_in_heat: 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
      }
      return classes[status] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }

    const formatCurrency = (amount) => {
      if (!amount) return '0.00'
      return parseFloat(amount).toFixed(2)
    }
    
    return {
      authStore,
      activeTab,
      isSidebarCollapsed,
      toggleSidebar,
      isStatsCardsCollapsed,
      toggleStatsCards,
      handleTabClick,
      farmer,
      farms,
      orders,
      ordersPagination,
      showAddFarmModal,
      showEditFarmModal,
      showOrderModal,
      showOrderDetailsModal,
      loadingOrderDetails,
      showUserDropdown,
      selectedOrderDetails,
      showImageModal,
      selectedImageUrl,
      selectedImageTitle,
      visitAddressCache,
      editingFarm,
      farmForm,
      farmLocation,
      profileForm,
      tabs,
      pendingOrdersCount,
      completedOrdersCount,
      recentOrders,
      activeOrders,
      orderFilters,
      technicians,
      filteredOrders,
      clearOrderFilters,
      // Pagination
      currentPage,
      itemsPerPage,
      totalPages,
      paginatedOrders,
      paginationInfo,
      goToPage,
      nextPage,
      prevPage,
      loadTechnicians,
      loadOrdersWithPagination,
      extractTechniciansFromOrders,
      viewOrderDetails,
      addFarm,
      editFarm,
      updateFarm,
      deleteFarm,
      updateProfile,
      getStatusBadgeClass,
      getJobStatusBadgeClass,
      getCowStatusBadgeClass,
      getImageUrl,
      openImageModal,
      closeImageModal,
      getVisitAddress,
      openInGoogleMaps,
      reverseGeocodeVisitLocation,
      formatCurrency,
      formatDate,
      formatETADate,
      formatETATime,
      formatDateTime,
      logout,
      goToSemenCatalog,
      goToServiceConfirmation,
      goToPregnancyTracking,
      updateFarmLocation,
      callTechnician,
      messageTechnician,
      initiateCall,
      initiateMessage,
      showTechnicianModal,
      selectedTechnician,
      profileCompletionAlert,
      dismissProfileAlert,
      notification,
      showNotification
    }
  }
}
</script>