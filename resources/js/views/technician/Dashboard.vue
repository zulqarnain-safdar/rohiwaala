<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-violet-50 to-indigo-100 flex relative overflow-x-hidden">
    <!-- Sidebar -->
    <div 
      :class="[
        'bg-white/80 backdrop-blur-md shadow-xl border-r border-white/20 flex-shrink-0 transition-all duration-300',
        'fixed lg:sticky inset-y-0 left-0 z-40',
        sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-64',
        'lg:w-64'
      ]"
    >
      <!-- Logo & Header -->
      <div class="p-6 border-b border-white/20">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h1 class="text-lg font-bold text-gray-900">Technician Panel</h1>
            </div>
          </div>
          <!-- Close Button for Mobile -->
          <button 
            @click="sidebarOpen = false"
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
              ? 'bg-gradient-to-r from-purple-500 to-violet-600 text-white shadow-lg' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-white/60'
          ]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="tab.id === 'jobs'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            <path v-else-if="tab.id === 'pregnancy-tracking'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path v-else-if="tab.id === 'availability'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path v-else-if="tab.id === 'inventory'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            <path v-else-if="tab.id === 'earnings'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            <path v-else-if="tab.id === 'reassignment-logs'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            <path v-else-if="tab.id === 'profile'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          <span>{{ tab.name }}</span>
        </button>
      </nav>

    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 max-w-full">
      <!-- Mobile Overlay -->
      <div v-if="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30"></div>

      <!-- Top Header -->
      <header class="bg-white/60 backdrop-blur-md shadow-lg border-b border-white/20 px-4 sm:px-6 py-4 relative z-50">
        <div class="flex items-center justify-between">
          <!-- Hamburger Menu Button (Mobile/Tablet) -->
          <button 
            @click="sidebarOpen = !sidebarOpen"
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
          
          <!-- Right side: Date and User Info -->
          <div class="flex items-center space-x-2 sm:space-x-4">
            <div class="hidden sm:block text-sm text-gray-600">
              {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
            </div>
            
            <!-- User Dropdown -->
            <div class="relative border-l border-gray-200 pl-2 sm:pl-4 user-dropdown z-[100]">
              <!-- User Avatar Button -->
              <button 
                @click="showUserDropdown = !showUserDropdown"
                class="flex items-center space-x-2 sm:space-x-3 hover:bg-gray-50 rounded-lg p-1 sm:p-2 transition-colors"
              >
                <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-violet-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-semibold">{{ technician?.name?.charAt(0) || 'U' }}</span>
                </div>
                <div class="hidden sm:block text-left">
                  <p class="text-sm font-medium text-gray-900">{{ technician?.name || 'User' }}</p>
                  <p class="text-xs text-gray-500">Technician</p>
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
                  <p class="text-sm font-medium text-gray-900">{{ technician?.name || 'User' }}</p>
                  <div class="mt-1">
                    <span :class="getAvailabilityBadgeClass(technician?.availability)" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full">
                      {{ technician?.availability || 'offline' }}
                    </span>
                  </div>
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
      <div v-if="activeTab === 'overview'" class="space-y-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Total Jobs Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-600 mb-1">Total Jobs</p>
                  <p class="text-3xl font-bold text-gray-900">{{ jobs.length }}</p>
                  <div class="flex items-center text-blue-600 text-sm mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    All Time
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
          </div>

          <!-- Total Earnings Card -->
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
                  <p class="text-sm font-medium text-gray-600 mb-1">Total Earnings</p>
                  <p class="text-3xl font-bold text-gray-900">₨{{ totalEarnings }}</p>
                  <div class="flex items-center text-green-600 text-sm mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                    +15%
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-green-500 to-emerald-600"></div>
          </div>

          <!-- Pending Jobs Card -->
          <div class="group bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl rounded-2xl border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-600 mb-1">Pending Jobs</p>
                  <p class="text-3xl font-bold text-gray-900">{{ pendingJobsCount }}</p>
                  <div class="flex items-center text-amber-600 text-sm mt-1">
                    <div class="w-2 h-2 bg-amber-500 rounded-full mr-2 animate-pulse"></div>
                    Awaiting
                  </div>
                </div>
              </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>
          </div>

          <!-- Completed Jobs Card -->
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
                  <p class="text-sm font-medium text-gray-600 mb-1">Completed Jobs</p>
                  <p class="text-3xl font-bold text-gray-900">{{ completedJobsCount }}</p>
                  <div class="flex items-center text-green-600 text-sm mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Recent Jobs -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Jobs</h3>
            <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job #</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="job in recentJobs" :key="job.id">
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      #{{ job.id }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ job.order?.farmer?.user?.name }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ job.order?.cattle_farm?.name }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                      <span :class="getJobStatusBadgeClass(job.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ formatJobStatus(job.status) }}
                      </span>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₨{{ formatCurrency(job.amount_paid) }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(job.created_at) }}
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
      </div>

      <!-- Jobs Tab -->
      <div v-if="activeTab === 'jobs'" class="space-y-6">
        
        <!-- Job Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ jobStats.pending || 0 }}</div>
            <div class="text-sm text-gray-600">Pending</div>
          </div>
          <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ jobStats.completed || 0 }}</div>
            <div class="text-sm text-gray-600">Completed</div>
          </div>
          <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ jobStats.in_progress || 0 }}</div>
            <div class="text-sm text-gray-600">In Progress</div>
          </div>
          <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ jobStats.reassigned || 0 }}</div>
            <div class="text-sm text-gray-600">Reassigned</div>
          </div>
        </div>

         <!-- Filters -->
         <div class="bg-white p-4 rounded-lg shadow mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Farm</label>
              <select v-model="farmFilter" @change="applyFilters" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Farms</option>
                <option v-for="farm in uniqueFarms" :key="farm" :value="farm">{{ farm }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Farmer</label>
              <select v-model="farmerFilter" @change="applyFilters" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Farmers</option>
                <option v-for="farmer in uniqueFarmers" :key="farmer" :value="farmer">{{ farmer }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
              <select v-model="statusFilter" @change="applyFilters" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="accepted">Accepted</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="rejected">Reassigned</option>
                <option value="no_heat">No Heat</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
              <select v-model="dateFilter" @change="applyFilters" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Dates</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="custom">Custom Range</option>
              </select>
              <!-- Custom Date Range Inputs -->
              <div v-if="dateFilter === 'custom'" class="mt-2 space-y-2">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
                  <input 
                    v-model="customDateFrom" 
                    type="date" 
                    @change="applyFilters"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
                  <input 
                    v-model="customDateTo" 
                    type="date" 
                    @change="applyFilters"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="mt-4 flex justify-end">
            <button @click="clearFilters" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
              Clear All Filters
            </button>
          </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semen</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETA</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Fee</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="job in paginatedJobs" :key="job.id">
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(job.order?.created_at || job.created_at) }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="flex items-center">
                      <div class="w-4 h-4 bg-green-500 rounded mr-2 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">#</span>
                      </div>
                      ORD-{{ String(job.order?.id || job.id).padStart(6, '0') }}
                    </div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ job.order?.farmer?.user?.name }}</div>
                    <div class="text-sm text-gray-500">{{ job.order?.farmer?.mobile_1 }}</div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-4 h-4 bg-green-500 rounded mr-2 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                        </svg>
                      </div>
                      <div class="text-sm text-gray-900">{{ job.order?.cattle_farm?.name }}</div>
                    </div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ job.order?.order_items?.[0]?.bull_semen?.bull_name || 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ job.order?.order_items?.[0]?.bull_semen?.breed?.name || 'N/A' }}</div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                    <span :class="getJobStatusBadgeClass(job.status)" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ formatJobStatus(job.status) }}
                    </span>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div v-if="job.estimated_visit_at">
                      <div class="text-sm font-medium">{{ formatETADate(job.estimated_visit_at) }}</div>
                      <div class="text-xs text-gray-500">{{ formatETATime(job.estimated_visit_at) }}</div>
                    </div>
                    <div v-else class="text-sm text-gray-400">
                      Not set
                    </div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rs{{ formatCurrency(job.amount_paid || job.order?.total_amount) }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rs{{ formatCurrency(job.order?.visit_fee || 0) }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-3">
                      <button @click="viewJobDetails(job)" class="text-gray-400 hover:text-gray-600" title="View Details">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                      <button 
                        v-if="job.status === 'pending'"
                        @click="confirmAcceptJob(job)"
                        class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition-colors"
                      >
                        Accept
                      </button>
                      <button 
                        v-if="job.status === 'pending'"
                        @click="openReassignModal(job)"
                        class="text-blue-600 hover:text-blue-800 font-medium"
                      >
                        Reassign
                      </button>
                      <button 
                        v-if="job.status === 'completed'"
                        @click="goToPregnancyTracking(job.id)"
                        class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                        title="Pregnancy Tracking"
                      >
                        Pregnancy Tracking
                      </button>
                      <button 
                        v-if="job.status === 'accepted' && !job.estimated_visit_at"
                        @click="provideETA(job)"
                        class="text-blue-600 hover:text-blue-800 font-medium"
                      >
                        Provide ETA
                      </button>
                      <button 
                        v-if="(job.status === 'accepted' && job.estimated_visit_at) || job.status === 'eta_provided'"
                        @click="confirmStartJob(job)"
                        class="text-green-600 hover:text-green-800 font-medium"
                      >
                        Start Job
                      </button>
                      <button 
                        v-if="job.status === 'in_progress'"
                        @click="openJobModal(job)"
                        class="text-purple-600 hover:text-purple-800 font-medium"
                      >
                        Farm Visit
                      </button>
                      <button 
                        v-if="job.status === 'in_progress'"
                        @click="confirmCompleteJob(job)"
                        class="text-green-600 hover:text-green-800 font-medium"
                      >
                        Complete
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
          
          <!-- Pagination Controls -->
          <div v-if="jobsPagination.total > 0" class="px-6 py-4 bg-gray-50/50 border-t border-white/20">
            <div class="flex items-center justify-between">
              <!-- Pagination Info -->
              <div class="text-sm text-gray-600">
                Showing {{ paginationInfo.start }} to {{ paginationInfo.end }} of {{ paginationInfo.total }} jobs
              </div>
              
              <!-- Pagination Controls -->
              <div v-if="totalPages > 1" class="flex items-center space-x-2">
                <!-- Previous Button -->
                <button
                  @click="prevPage"
                  :disabled="jobsPagination.current_page === 1"
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
                      jobsPagination.current_page === page
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
                    v-if="totalPages > 5 && jobsPagination.current_page < totalPages - 2"
                    @click="goToPage(totalPages)"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                  >
                    {{ totalPages }}
                  </button>
                </div>
                
                <!-- Next Button -->
                <button
                  @click="nextPage"
                  :disabled="jobsPagination.current_page === totalPages"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Next
                </button>
              </div>
              
              <!-- Show message when there's only one page -->
              <div v-else class="text-sm text-gray-500">
                All jobs displayed
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings Tab -->
      <div v-if="activeTab === 'earnings'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Earnings</h2>
          <div class="flex space-x-4">
            <select v-model="earningsPeriod" @change="loadEarnings" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="day">Today</option>
              <option value="week">This Week</option>
              <option value="month">This Month</option>
              <option value="year">This Year</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                    <dd class="text-lg font-medium text-gray-900">₨{{ formatCurrency(earnings.total) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Jobs Completed</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ earnings.job_count || 0 }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Average per Job</dt>
                    <dd class="text-lg font-medium text-gray-900">RS{{ averagePerJob }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Earnings History -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Earnings History</h3>
            <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job #</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="job in earnings.jobs" :key="job.id">
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      #{{ job.id }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ job.order?.farmer?.user?.name }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₨{{ formatCurrency(job.amount_paid) }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(job.created_at) }}
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
      </div>

      <!-- Reassignment Logs Tab -->
      <div v-if="activeTab === 'reassignment-logs'" class="space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Reassignment Logs</h2>
          <div class="text-sm text-gray-600">
            Track all job reassignments you're involved in
          </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Reassignment History</h3>
          </div>
          
          <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reassigned By</th>
                  <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="log in reassignmentLogsPagination.data" :key="log.id" class="hover:bg-gray-50">
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(log.created_at) }}
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-white text-xs font-bold">#</span>
                      </div>
                      <div>
                        <div class="font-medium">{{ log.order?.order_number }}</div>
                        <div class="text-xs text-gray-500">{{ log.order?.cattle_farm?.name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                  <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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

      <!-- Profile Tab -->
      <div v-if="activeTab === 'profile'" class="space-y-6">
        <!-- Profile Status & Unique Code -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Profile Status</h3>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-violet-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-xl font-bold">{{ technician?.unique_code?.charAt(0) || 'T' }}</span>
                  </div>
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900">{{ technician?.name || 'Technician' }}</h4>
                  <p class="text-sm text-gray-500">Unique Code: {{ technician?.unique_code || 'Not assigned' }}</p>
                  <div class="flex items-center mt-2">
                    <span :class="getStatusBadgeClass(technician?.status)" class="px-3 py-1 text-xs font-semibold rounded-full">
                      {{ technician?.status || 'Pending' }}
                    </span>
                    <span v-if="technician?.status === 'rejected'" class="ml-2 text-sm text-red-600">
                      {{ technician?.rejection_reason || 'Profile rejected' }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm text-gray-500">Profile Completion</p>
                <div class="w-32 bg-gray-200 rounded-full h-2 mt-1">
                  <div class="bg-blue-600 h-2 rounded-full" :style="{ width: profileCompletion + '%' }"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ profileCompletion }}% Complete</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile Information Form -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
          </div>
          <div class="p-6">
            <form @submit.prevent="updateProfile" class="space-y-6">
              <!-- Basic Information -->
              <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                    <input v-model="profileForm.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Father's Name *</label>
                    <input v-model="profileForm.father_name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">CNIC * (Must be unique)</label>
                    <input v-model="profileForm.cnic" type="text" required pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" placeholder="12345-1234567-1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                    <input v-model="profileForm.date_of_birth" type="date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Contact Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Primary Mobile *</label>
                    <input v-model="profileForm.mobile_1" type="tel" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Secondary Mobile</label>
                    <input v-model="profileForm.mobile_2" type="tel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="profileForm.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                </div>
              </div>

              <!-- Address Information -->
              <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Address Information</h4>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Home Address *</label>
                    <textarea v-model="profileForm.home_address" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Clinic/Work Address</label>
                    <textarea v-model="profileForm.clinic_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Province *</label>
                      <select v-model="profileForm.province" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Province</option>
                        <option value="Punjab">Punjab</option>
                        <option value="Sindh">Sindh</option>
                        <option value="KPK">KPK</option>
                        <option value="Balochistan">Balochistan</option>
                        <option value="Islamabad">Islamabad</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Division *</label>
                      <select v-model="profileForm.division" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Division</option>
                        <option v-for="division in getDivisionsForProvince(profileForm.province)" :key="division" :value="division">{{ division }}</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">District *</label>
                      <select v-model="profileForm.district" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select District</option>
                        <option v-for="district in getDistrictsForDivision(profileForm.division)" :key="district" :value="district">{{ district }}</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Tehsil/City *</label>
                      <input v-model="profileForm.tehsil_city" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter tehsil/city">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Professional Information -->
              <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Professional Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Years of Experience *</label>
                    <input v-model="profileForm.years_of_experience" type="number" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Degree Year *</label>
                    <input v-model="profileForm.degree_year" type="number" min="1950" :max="new Date().getFullYear()" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Capacity (Customers per Month) *</label>
                    <input v-model="profileForm.customers_per_month" type="number" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Specialization</label>
                    <input v-model="profileForm.specialization" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  </div>
                </div>
                <div class="mt-4">
                  <label class="block text-sm font-medium text-gray-700">Areas of Expertise *</label>
                  <textarea v-model="profileForm.areas_of_expertise" rows="3" required placeholder="e.g., Artificial Insemination, Animal Health, Breeding Management" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
              </div>

              <!-- Document Uploads -->
              <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Required Documents</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- CNIC Front -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CNIC Front *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                      <div class="space-y-1 text-center">
                        <div v-if="!documents.cnic_front" class="space-y-2">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                              <span>Upload CNIC Front</span>
                              <input type="file" @change="handleDocumentUpload('cnic_front', $event)" class="sr-only" accept="image/*,.pdf" :disabled="uploading.cnic_front">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                        </div>
                        <div v-else class="space-y-2">
                          <img :src="documents.cnic_front" alt="CNIC Front" class="mx-auto h-32 w-auto object-contain rounded-md">
                          <div class="flex space-x-2 justify-center">
                            <button @click="viewDocument(documents.cnic_front)" type="button" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                            <button @click="deleteDocument('cnic_front')" type="button" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                          </div>
                        </div>
                        <div v-if="uploading.cnic_front" class="text-sm text-blue-600">Uploading...</div>
                      </div>
                    </div>
                  </div>

                  <!-- CNIC Back -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CNIC Back *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                      <div class="space-y-1 text-center">
                        <div v-if="!documents.cnic_back" class="space-y-2">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                              <span>Upload CNIC Back</span>
                              <input type="file" @change="handleDocumentUpload('cnic_back', $event)" class="sr-only" accept="image/*,.pdf" :disabled="uploading.cnic_back">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                        </div>
                        <div v-else class="space-y-2">
                          <img :src="documents.cnic_back" alt="CNIC Back" class="mx-auto h-32 w-auto object-contain rounded-md">
                          <div class="flex space-x-2 justify-center">
                            <button @click="viewDocument(documents.cnic_back)" type="button" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                            <button @click="deleteDocument('cnic_back')" type="button" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                          </div>
                        </div>
                        <div v-if="uploading.cnic_back" class="text-sm text-blue-600">Uploading...</div>
                      </div>
                    </div>
                  </div>

                  <!-- Degree Front -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Degree Certificate Front *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                      <div class="space-y-1 text-center">
                        <div v-if="!documents.degree_front" class="space-y-2">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                              <span>Upload Degree Front</span>
                              <input type="file" @change="handleDocumentUpload('degree_front', $event)" class="sr-only" accept="image/*,.pdf" :disabled="uploading.degree_front">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                        </div>
                        <div v-else class="space-y-2">
                          <img :src="documents.degree_front" alt="Degree Front" class="mx-auto h-32 w-auto object-contain rounded-md">
                          <div class="flex space-x-2 justify-center">
                            <button @click="viewDocument(documents.degree_front)" type="button" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                            <button @click="deleteDocument('degree_front')" type="button" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                          </div>
                        </div>
                        <div v-if="uploading.degree_front" class="text-sm text-blue-600">Uploading...</div>
                      </div>
                    </div>
                  </div>

                  <!-- Degree Back -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Degree Certificate Back *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                      <div class="space-y-1 text-center">
                        <div v-if="!documents.degree_back" class="space-y-2">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                              <span>Upload Degree Back</span>
                              <input type="file" @change="handleDocumentUpload('degree_back', $event)" class="sr-only" accept="image/*,.pdf" :disabled="uploading.degree_back">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                        </div>
                        <div v-else class="space-y-2">
                          <img :src="documents.degree_back" alt="Degree Back" class="mx-auto h-32 w-auto object-contain rounded-md">
                          <div class="flex space-x-2 justify-center">
                            <button @click="viewDocument(documents.degree_back)" type="button" class="text-sm text-blue-600 hover:text-blue-800">View</button>
                            <button @click="deleteDocument('degree_back')" type="button" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                          </div>
                        </div>
                        <div v-if="uploading.degree_back" class="text-sm text-blue-600">Uploading...</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Location Information -->
              <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Location Information</h4>
                <div class="bg-gray-50 p-4 rounded-xl">
                  <GoogleMapLocationPicker
                    v-model="technicianLocation"
                    :initial-lat="profileForm.clinic_latitude"
                    :initial-lng="profileForm.clinic_longitude"
                    @update:modelValue="updateTechnicianLocation"
                  />
                </div>
              </div>

              <div class="flex justify-between">
                
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                  Update Profile
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Pregnancy Tracking Tab -->
      <div v-if="activeTab === 'pregnancy-tracking'" class="space-y-6">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Pregnancy Tracking</h2>
            <div class="text-sm text-gray-600">
              Track pregnancy outcomes for your completed jobs
            </div>
          </div>
          
          <!-- Filter for specific job if selected -->
          <div v-if="selectedJobForPregnancy" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-blue-900">Showing Pregnancy Details for Specific Job</h3>
                <p class="text-sm text-blue-700">Job ID: {{ selectedJobForPregnancy }}</p>
              </div>
              <button 
                @click="selectedJobForPregnancy = null"
                class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
              >
                Show All Jobs
              </button>
            </div>
          </div>

          <div v-if="filteredCompletedJobs.length > 0" class="space-y-4">
            <div
              v-for="job in filteredCompletedJobs"
              :key="job.id"
              class="p-6 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl border border-purple-200 hover:shadow-lg transition-shadow"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-4 mb-4">
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ job.order.order_number }}</h3>
                      <p class="text-sm text-gray-600">{{ job.order.cattle_farm.name }}</p>
                      <p class="text-xs text-gray-500 mt-1">
                        Breed: {{ job.order.order_items?.[0]?.bull_semen?.breed?.name || 'N/A' }}
                      </p>
                    </div>
                    <div class="flex items-center space-x-2">
                      
                      <span class="text-sm text-gray-500">
                        {{ formatDate(job.actual_visit_at) }}
                      </span>
                    </div>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                      <label class="text-sm font-semibold text-gray-500">Farmer</label>
                      <p class="text-sm font-medium">{{ job.order.farmer.user.name }}</p>
                    </div>
                    <div>
                      <label class="text-sm font-semibold text-gray-500">Days Since Insemination</label>
                      <p class="text-sm font-medium text-purple-600 font-bold">
                        {{ getDaysSinceInsemination(job.actual_visit_at) }} days
                      </p>
                    </div>
                    
                  </div>

                  <!-- Individual Cow Status Display -->
                  <div v-if="job.cow_insemination_records && job.cow_insemination_records.length > 0" class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Individual Cow Status</h4>
                    <div class="space-y-2">
                      <div 
                        v-for="record in job.cow_insemination_records" 
                        :key="record.id"
                        class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200"
                      >
                        <div class="flex items-center space-x-3">
                          <span class="text-sm font-medium text-gray-900">
                            Cow {{ getCowNumber(record, job.order?.order_items || []) }}
                          </span>
                          <span class="text-xs text-gray-500">
                            {{ record.orderItem?.bull_semen?.bull_name || 'N/A' }}
                          </span>
                        </div>
                        <div class="flex items-center space-x-2">
                          <span :class="getCowStatusBadgeClass(record?.pregnancy_status || 'pending')" class="text-xs font-semibold px-2 py-1 rounded-full">
                            {{ getCowStatusText(record?.pregnancy_status || 'pending') }}
                          </span>
                          <span class="text-xs text-gray-500">
                            {{ getDaysSinceInsemination(record?.insemination_date) }} days
                          </span>
                        </div>
                        
                        <!-- Calf Photos for each individual cow record -->
                        <div v-if="record && (record.pregnancy_status || 'pending') === 'delivered' && (record.calf_image || job.cow_insemination_records[0]?.calf_image)" class="mt-3">
                          <h5 class="text-xs font-semibold text-gray-600 mb-2">
                            Calf Photos
                          </h5>
                          <div class="flex space-x-2">
                            <template v-if="record.calf_image">
                              <img 
                                v-for="(image, index) in record.calf_image.split(',').filter(img => img && img.trim())"
                                :key="'record-' + index"
                                :src="getImageUrl(image.trim())" 
                                :alt="'Calf photo for Cow ' + getCowNumber(record, job.order?.order_items || [])"
                                class="w-16 h-16 object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-80 transition-opacity"
                                @click="openImageModal(getImageUrl(image.trim()), 'Calf Photo - Cow ' + getCowNumber(record, job.order?.order_items || []))"
                              />
                            </template>
                            <template v-else-if="job.cow_insemination_records[0]?.calf_image">
                              <img 
                                v-for="(image, index) in job.cow_insemination_records[0].calf_image.split(',').filter(img => img && img.trim())"
                                :key="'job-first-record-' + index"
                                :src="getImageUrl(image.trim())" 
                                :alt="'Calf photo for Cow ' + getCowNumber(record, job.order?.order_items || [])"
                                class="w-16 h-16 object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-80 transition-opacity"
                                @click="openImageModal(getImageUrl(image.trim()), 'Calf Photo - Cow ' + getCowNumber(record, job.order?.order_items || []))"
                              />
                            </template>
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
          </div>
          
          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
              {{ selectedJobForPregnancy ? 'No pregnancy records found for this job' : 'No pregnancy records found' }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              {{ selectedJobForPregnancy ? 'This job may not have pregnancy tracking data yet.' : 'Complete some insemination jobs to start tracking pregnancies.' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Availability Tab -->
      <div v-if="activeTab === 'availability'" class="space-y-6">
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Set Your Availability</h3>
          </div>
          <div class="p-6">
            <div class="space-y-6">
              <!-- Current Status -->
              <div class="text-center">
                <div class="inline-flex items-center space-x-4">
                  <div class="text-center">
                    <div :class="getAvailabilityBadgeClass(technician?.availability)" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-2">
                      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="technician?.availability === 'online'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path v-else-if="technician?.availability === 'offline'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ technician?.availability || 'Offline' }}</p>
                  </div>
                </div>
              </div>

              <!-- Availability Options -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button 
                  @click="updateAvailability('online')"
                  :class="technician?.availability === 'online' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-green-50'"
                  class="p-6 border-2 rounded-lg text-center transition-all duration-200"
                >
                  <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold mb-2">Online</h3>
                  <p class="text-sm">Available for new jobs</p>
                </button>

                <button 
                  @click="updateAvailability('offline')"
                  :class="technician?.availability === 'offline' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-red-50'"
                  class="p-6 border-2 rounded-lg text-center transition-all duration-200"
                >
                  <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold mb-2">Offline</h3>
                  <p class="text-sm">Not available for jobs</p>
                </button>

                <button 
                  @click="updateAvailability('on_leave')"
                  :class="technician?.availability === 'on_leave' ? 'bg-yellow-600 text-white border-yellow-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-yellow-50'"
                  class="p-6 border-2 rounded-lg text-center transition-all duration-200"
                >
                  <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold mb-2">On Leave</h3>
                  <p class="text-sm">Temporarily unavailable</p>
                </button>
              </div>

              <!-- Working Hours -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-md font-semibold text-gray-900 mb-4">Working Hours</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input v-model="workingHours.start_time" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input v-model="workingHours.end_time" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                  </div>
                </div>
                <div class="mt-4">
                  <label class="flex items-center">
                    <input v-model="workingHours.available_24_7" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Available 24/7</span>
                  </label>
                </div>
              </div>

              <!-- Service Areas -->
              <!-- <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-md font-semibold text-gray-900 mb-4">Service Areas</h4>
                <div class="space-y-2">
                  <div v-for="(area, index) in serviceAreas" :key="index" class="flex items-center space-x-2">
                    <input v-model="area.name" type="text" placeholder="Enter area name" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <input v-model="area.radius" type="number" placeholder="Radius (km)" class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <button @click="removeServiceArea(index)" type="button" class="text-red-600 hover:text-red-800">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                  <button @click="addServiceArea" type="button" class="text-blue-600 hover:text-blue-800 text-sm">
                    + Add Service Area
                  </button>
                </div>
              </div> -->
              
              <!-- Save Settings Button -->
              <div class="flex justify-end">
                <button @click="saveAvailabilitySettings" type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                  Save Settings
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Tab -->
      <div v-if="activeTab === 'inventory'" class="space-y-6">
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">My Inventory</h3>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
              <div class="bg-blue-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ inventory.total_items || 0 }}</div>
                <div class="text-sm text-gray-600">Total Items</div>
              </div>
              <div class="bg-green-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ inventory.available_items || 0 }}</div>
                <div class="text-sm text-gray-600">Available</div>
              </div>
              <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ inventory.low_stock_items || 0 }}</div>
                <div class="text-sm text-gray-600">Low Stock</div>
              </div>
              <div class="bg-red-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-red-600">{{ inventory.out_of_stock || 0 }}</div>
                <div class="text-sm text-gray-600">Out of Stock</div>
              </div>
            </div>

            <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="item in inventory.items" :key="item.id">
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ item.bull_name }}</div>
                      <div class="text-sm text-gray-500">₨{{ formatCurrency(item.price) }}</div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ item.breed?.name }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ item.stock_quantity }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                      <span :class="getStockStatusClass(item.stock_quantity)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ getStockStatus(item.stock_quantity) }}
                      </span>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button @click="requestStock(item)" class="text-blue-600 hover:text-blue-800">Request Stock</button>
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
      </div>

      <!-- Farm Visit Modal -->
      <div v-if="showJobModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Farm Visit - Job #{{ selectedJob?.id }}</h2>
              <button @click="closeJobModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <div class="space-y-6">
              <!-- Job Information -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm text-gray-600">Farmer</p>
                    <p class="font-medium">{{ selectedJob?.order?.farmer?.user?.name }}</p>
                    <p class="text-sm text-gray-500">{{ selectedJob?.order?.farmer?.mobile_1 }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Farm</p>
                    <p class="font-medium">{{ selectedJob?.order?.cattle_farm?.name }}</p>
                    <p class="text-sm text-gray-500">{{ selectedJob?.order?.cattle_farm?.address }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Semen</p>
                    <p class="font-medium">{{ selectedJob?.order?.order_items?.[0]?.bull_semen?.bull_name || 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ selectedJob?.order?.order_items?.[0]?.bull_semen?.breed?.name || 'N/A' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Amount</p>
                    <p class="font-medium text-green-600">₨{{ formatCurrency(selectedJob?.amount_paid) }}</p>
                  </div>
                </div>
              </div>

              <!-- Technician Notes Display (always shown) -->
              <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Technician Notes</h3>
                <p class="text-sm text-gray-900 bg-white p-3 rounded border border-blue-200">
                  {{ selectedJob?.technician_notes || 'No notes from technician' }}
                </p>
              </div>

              <!-- Cow Insemination Records -->
              <div v-for="(cowRecord, index) in farmVisitForm.cow_records" :key="cowRecord.order_item_id" class="bg-white border rounded-lg p-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                  Cow {{ getCowNumber(cowRecord, selectedJob?.order?.order_items || []) }} - {{ selectedJob?.order?.order_items?.[index]?.bull_semen?.bull_name || 'N/A' }}
                  <span class="text-sm text-gray-500">({{ selectedJob?.order?.order_items?.[index]?.bull_semen?.breed?.name || 'N/A' }})</span>
                </h3>
                
                <!-- Cow Heat Check -->
                <div class="mb-4">
                  <h4 class="text-md font-semibold text-gray-800 mb-3">Cow Heat Check</h4>
                  <div class="space-y-3">
                    <div>
                      <label class="flex items-center">
                        <input v-model="cowRecord.cow_in_heat" type="radio" value="yes" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Cow is in heat - Proceed with insemination</span>
                      </label>
                    </div>
                    <div>
                      <label class="flex items-center">
                        <input v-model="cowRecord.cow_in_heat" type="radio" value="no" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Cow is not in heat - Record visit fee only</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Cow Photo -->
                <div v-if="cowRecord.cow_in_heat === 'yes'" class="mb-4">
                  <h4 class="text-md font-semibold text-gray-800 mb-3">Take Cow Photo</h4>
                  
                  <!-- Image Preview -->
                  <div v-if="cowRecord.cow_photo" class="mb-4">
                    <div class="relative inline-block">
                      <img :src="cowRecord.cow_photo.preview" :alt="cowRecord.cow_photo.name" class="max-w-full h-48 object-cover rounded-lg border border-gray-300">
                      <button @click="cowRecord.cow_photo = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                        ×
                      </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ cowRecord.cow_photo.name }}</p>
                  </div>
                  
                  <!-- Upload Area -->
                  <div v-if="!cowRecord.cow_photo" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                      <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      <div class="flex text-sm text-gray-600">
                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
                          <span>Take Photo</span>
                          <input type="file" @change="(event) => handleCowPhotoUpload(event, index)" class="sr-only" accept="image/*" capture="environment">
                        </label>
                      </div>
                      <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                    </div>
                  </div>
                  
                  <!-- Replace Photo Button -->
                  <div v-if="cowRecord.cow_photo" class="text-center">
                    <label class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Replace Photo
                      <input type="file" @change="(event) => handleCowPhotoUpload(event, index)" class="sr-only" accept="image/*" capture="environment">
                    </label>
                  </div>
                </div>

                <!-- Straw Information -->
                <div v-if="cowRecord.cow_in_heat === 'yes'" class="mb-4">
                  <h4 class="text-md font-semibold text-gray-800 mb-3">Straw Information</h4>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Straw Barcode/Code</label>
                      <div class="flex space-x-2">
                        <input v-model="cowRecord.straw_barcode_code" type="text" placeholder="Scan or enter straw code" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <button @click="scanBarcode(index)" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                          Scan
                        </button>
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Straw Photo</label>
                      
                      <!-- Image Preview -->
                      <div v-if="cowRecord.straw_photo" class="mb-4">
                        <div class="relative inline-block">
                          <img :src="cowRecord.straw_photo.preview" :alt="cowRecord.straw_photo.name" class="max-w-full h-48 object-cover rounded-lg border border-gray-300">
                          <button @click="cowRecord.straw_photo = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                            ×
                          </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">{{ cowRecord.straw_photo.name }}</p>
                      </div>
                      
                      <!-- Upload Area -->
                      <div v-if="!cowRecord.straw_photo" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
                              <span>Upload Straw Photo</span>
                              <input type="file" @change="(event) => handleStrawPhotoUpload(event, index)" class="sr-only" accept="image/*" capture="environment">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                        </div>
                      </div>
                      
                      <!-- Replace Photo Button -->
                      <div v-if="cowRecord.straw_photo" class="text-center">
                        <label class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                          Replace Photo
                          <input type="file" @change="(event) => handleStrawPhotoUpload(event, index)" class="sr-only" accept="image/*" capture="environment">
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Payment Information for this cow -->
                <div class="mb-4">
                  <h4 class="text-md font-semibold text-gray-800 mb-3">Payment Information</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Amount Received</label>
                      <input v-model="cowRecord.amount_received" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                      <select v-model="cowRecord.payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Notes for this cow -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                  <textarea v-model="cowRecord.notes" rows="2" placeholder="Add any notes about this cow..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
              </div>



              <!-- Location Recording -->
              <div class="bg-white border rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Visit Location</h3>
                <div class="bg-gray-50 p-4 rounded-xl">
                  <GoogleMapLocationPicker
                    v-model="technicianCurrentLocation"
                    :initial-lat="parseFloat(farmVisitForm.latitude) || 31.5204"
                    :initial-lng="parseFloat(farmVisitForm.longitude) || 74.3587"
                    :readonly="true"
                    :hide-location-controls="true"
                    @update:modelValue="updateTechnicianCurrentLocation"
                    @get-current-location="handleCurrentLocationObtained"
                    @get-current-location-error="handleCurrentLocationError"
                  />
                </div>
                <p class="text-xs text-gray-500 mt-2">Automatically detecting your current location for this visit.</p>
              </div>

              <!-- Notes -->
              <div class="bg-white border rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <textarea v-model="farmVisitForm.notes" rows="3" placeholder="Add any additional notes about the visit..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
              </div>

              <div class="flex justify-end space-x-4">
                <button @click="closeJobModal" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                  Cancel
                </button>
                <button 
                  @click="handleCompleteJob"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                  Complete Job
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ETA Modal -->
      <div v-if="showETAModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900">Provide ETA</h2>
              <button @click="closeETAModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="submitETA" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Arrival Time</label>
                <input 
                  v-model="etaForm.eta" 
                  type="datetime-local" 
                  :min="getCurrentDateTime()"
                  required 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea v-model="etaForm.notes" rows="3" placeholder="Any additional information for the farmer..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
              </div>
              <div class="flex justify-end space-x-4">
                <button type="button" @click="closeETAModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                  Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                  Submit ETA
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Reassign Modal -->
      <div v-if="showReassignModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900">Reassign Job</h2>
              <button @click="closeReassignModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Job Details</label>
                <div class="bg-gray-50 p-3 rounded-lg">
                  <p class="text-sm"><strong>Order:</strong> {{ selectedJobForReassign?.order?.order_number }}</p>
                  <p class="text-sm"><strong>Farmer:</strong> {{ selectedJobForReassign?.order?.farmer?.user?.name }}</p>
                  <p class="text-sm"><strong>Farm:</strong> {{ selectedJobForReassign?.order?.cattle_farm?.name }}</p>
                  <p class="text-sm"><strong>Amount:</strong> Rs{{ formatCurrency(selectedJobForReassign?.amount_paid || selectedJobForReassign?.order?.total_amount) }}</p>
                  <p class="text-sm"><strong>Visit Fee:</strong> Rs{{ formatCurrency(selectedJobForReassign?.order?.visit_fee || 0) }}</p>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Technicians</label>
                <div class="flex space-x-2">
                  <input 
                    v-model="technicianSearchQuery" 
                    @input="searchTechnicians"
                    type="text" 
                    placeholder="Search by name or location..."
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                  <button 
                    @click="searchTechnicians"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Search
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Distance</label>
                <select v-model="radiusFilter" @change="searchTechnicians" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="all">All Technicians</option>
                  <option value="30">Within 30 km</option>
                  <option value="50">Within 50 km</option>
                  <option value="100">Within 100 km</option>
                </select>
              </div>

              <div v-if="availableTechnicians.length > 0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available Technicians</label>
                <div class="max-h-60 overflow-y-auto space-y-2">
                  <div 
                    v-for="technician in availableTechnicians" 
                    :key="technician.id"
                    @click="selectTechnician(technician)"
                    :class="[
                      'p-3 border rounded-lg cursor-pointer transition-colors',
                      selectedTechnicianForReassign?.id === technician.id 
                        ? 'border-blue-500 bg-blue-50' 
                        : 'border-gray-200 hover:border-gray-300'
                    ]"
                  >
                    <div class="flex justify-between items-start">
                      <div>
                        <p class="font-medium text-gray-900">{{ technician.user.name }}</p>
                        <p class="text-sm text-gray-500">{{ technician.user.mobile }}</p>
                        <p class="text-sm text-gray-500">{{ technician.tehsil_city || technician.city }}, {{ technician.province }}</p>
                        <p v-if="technician.distance" class="text-sm text-blue-600">{{ technician.distance.toFixed(1) }} km away</p>
                      </div>
                      <div class="text-right">
                        <span :class="getAvailabilityBadgeClass(technician.availability)" class="px-2 py-1 text-xs font-semibold rounded-full">
                          {{ technician.availability }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else-if="technicianSearchQuery || radiusFilter !== 'all'" class="text-center py-4">
                <p class="text-gray-500">No technicians found matching your criteria.</p>
                <p class="text-sm text-gray-400 mt-2">
                  Try:
                  <br>• Removing the search term to see all technicians
                  <br>• Increasing the distance radius
                  <br>• Selecting "All Technicians" from the distance filter
                </p>
              </div>

              <div v-if="selectedTechnicianForReassign">
                <label class="block text-sm font-medium text-gray-700 mb-2">Selected Technician</label>
                <div class="bg-blue-50 p-3 rounded-lg">
                  <p class="font-medium text-gray-900">{{ selectedTechnicianForReassign.user.name }}</p>
                  <p class="text-sm text-gray-500">{{ selectedTechnicianForReassign.user.mobile }}</p>
                  <p class="text-sm text-gray-500">{{ selectedTechnicianForReassign.tehsil_city || selectedTechnicianForReassign.city }}, {{ selectedTechnicianForReassign.province }}</p>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Reason for Reassignment <span class="text-red-500">*</span>
                </label>
                <textarea 
                  v-model="reassignReason"
                  placeholder="Please explain why you are reassigning this job..."
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                  required
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">This information will be logged and visible to administrators.</p>
              </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
              <button @click="closeReassignModal" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Cancel
              </button>
              <button 
                @click="confirmReassignJob"
                :disabled="!selectedTechnicianForReassign || !reassignReason.trim()"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
              >
                Reassign Job
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Job Details Modal -->
      <div v-if="showJobDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900">Order Details</h2>
              <button @click="closeJobDetailsModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <div v-if="selectedJobForDetails" class="space-y-6">
              <!-- Order Information -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <span class="text-sm font-medium text-gray-500">Order Number:</span>
                    <p class="text-sm text-gray-900">ORD-{{ String(selectedJobForDetails.order?.id || selectedJobForDetails.id).padStart(6, '0') }}</p>
                  </div>
                  <div>
                    <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                    <p class="text-sm text-green-600 font-semibold">Rs{{ formatCurrency(selectedJobForDetails.amount_paid || selectedJobForDetails.order?.total_amount) }}</p>
                  </div>
                  <div>
                    <span class="text-sm font-medium text-gray-500">Visit Fee:</span>
                    <p class="text-sm text-blue-600 font-semibold">Rs{{ formatCurrency(selectedJobForDetails.order?.visit_fee || 0) }}</p>
                  </div>
                  <div>
                    <span class="text-sm font-medium text-gray-500">Order Date:</span>
                    <p class="text-sm text-gray-900">{{ formatDate(selectedJobForDetails.order?.created_at || selectedJobForDetails.created_at) }}</p>
                  </div>
                  <div>
                    <span class="text-sm font-medium text-gray-500">Status:</span>
                    <span :class="getJobStatusBadgeClass(selectedJobForDetails.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ formatJobStatus(selectedJobForDetails.status) }}
                    </span>
                  </div>
                  <div v-if="selectedJobForDetails.order?.notes" class="md:col-span-2">
                    <span class="text-sm font-medium text-gray-500">Order Notes:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ selectedJobForDetails.order.notes }}</p>
                  </div>
                </div>
              </div>

              <!-- Farm Information -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Farm Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <span class="text-sm font-medium text-gray-500">Farm Name:</span>
                    <p class="text-sm text-gray-900">{{ selectedJobForDetails.order?.cattle_farm?.name || 'N/A' }}</p>
                  </div>
                  
                  <div>
                    <span class="text-sm font-medium text-gray-500">Farm Location:</span>
                    <div v-if="selectedJobForDetails.order?.cattle_farm?.latitude && selectedJobForDetails.order?.cattle_farm?.longitude" class="mt-1">
                      <button 
                        @click="openInGoogleMaps(selectedJobForDetails.order.cattle_farm.latitude, selectedJobForDetails.order.cattle_farm.longitude)"
                        class="text-sm text-blue-600 hover:text-blue-800 underline flex items-center gap-1 transition-colors"
                      >
                        <div class="flex items-start space-x-2">
                          <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          <div>
                            <div v-if="farmAddress">{{ farmAddress }}</div>
                            <div v-else class="text-gray-500">Loading address...</div>
                            <div class="text-xs text-gray-500 mt-1">(Click to open in Google Maps)</div>
                          </div>
                        </div>
                        
                      </button>
                    </div>
                    <div v-else class="text-sm text-gray-500 mt-1">
                      Location not available
                    </div>
                  </div>
                 
                </div>
              </div>

               <!-- ETA Information Display -->
               <div v-if="selectedJobForDetails?.estimated_visit_at" class="bg-green-50 rounded-lg p-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Estimated Arrival Time</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <span class="text-sm font-medium text-gray-600">ETA Date & Time:</span>
                    <p class="text-sm text-gray-900 font-semibold mt-1">
                      {{ formatETADate(selectedJobForDetails.estimated_visit_at) }} at {{ formatETATime(selectedJobForDetails.estimated_visit_at) }}
                    </p>
                  </div>
                  <div v-if="selectedJobForDetails.technician_notes">
                    <span class="text-sm font-medium text-gray-600">ETA Notes:</span>
                    <p class="text-sm text-gray-900 bg-white p-2 rounded border border-green-200 mt-1">
                      {{ selectedJobForDetails.technician_notes }}
                    </p>
                  </div>
                </div>
              </div>

               

              <!-- Cow Insemination Details -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cow Insemination Details</h3>
                <div v-if="selectedJobForDetails.order?.order_items?.length" class="space-y-4">
                  <div v-for="(item, index) in selectedJobForDetails.order.order_items" :key="item.id" class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-gray-800 mb-3">
                      Cow {{ getCowNumber({ order_item_id: item.id }, selectedJobForDetails.order?.order_items || []) }} - {{ item.bull_semen?.bull_name || 'N/A' }}
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
                        <p class="text-sm text-gray-900">{{ item.heat_started_at ? formatDate(item.heat_started_at) : 'N/A' }}</p>
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
                    <div v-if="selectedJobForDetails.cow_insemination_records && selectedJobForDetails.cow_insemination_records.find(record => record.order_item_id === item.id)" class="bg-gray-50 p-4 rounded-lg">
                      <h5 class="text-sm font-semibold text-gray-700 mb-3">Visit Record</h5>
                      <div v-for="record in selectedJobForDetails.cow_insemination_records.filter(record => record.order_item_id === item.id)" :key="record.id" class="space-y-3">
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

            <div class="flex justify-end mt-6">
              <button @click="closeJobDetailsModal" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Image Modal -->
      <div v-if="showImageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 z-50" @click="closeImageModal">
        <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-hidden" @click.stop>
          <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">{{ selectedImageTitle }}</h3>
            <button @click="closeImageModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div class="p-4">
            <img :src="selectedImageUrl" :alt="selectedImageTitle" class="max-w-full max-h-[70vh] object-contain mx-auto">
          </div>
        </div>
      </div>
      </main>
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
  name: 'TechnicianDashboard',
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    
    // Notification functions
    const showSuccess = (message) => {
      console.log('SUCCESS:', message)
      // Show success notification using SweetAlert
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Success!',
          text: message,
          icon: 'success',
          timer: 3000,
          showConfirmButton: false,
          toast: true,
          position: 'top-end'
        })
      } else {
        // Fallback to browser alert if SweetAlert is not available
        alert(message)
      }
    }
    
    const showError = (message) => {
      console.error('ERROR:', message)
      // Show error notification using SweetAlert
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Error!',
          text: message,
          icon: 'error',
          timer: 5000,
          showConfirmButton: false,
          toast: true,
          position: 'top-end'
        })
      } else {
        // Fallback to browser alert if SweetAlert is not available
        alert(message)
      }
    }
    
    const showInfo = (message) => {
      console.info('INFO:', message)
      // Show info notification using SweetAlert
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Info',
          text: message,
          icon: 'info',
          timer: 4000,
          showConfirmButton: false,
          toast: true,
          position: 'top-end'
        })
      } else {
        // Fallback to browser alert if SweetAlert is not available
        alert(message)
      }
    }
    
    const showWarning = (message) => {
      console.warn('WARNING:', message)
      // Show warning notification using SweetAlert
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Warning!',
          text: message,
          icon: 'warning',
          timer: 4000,
          showConfirmButton: false,
          toast: true,
          position: 'top-end'
        })
      } else {
        // Fallback to browser alert if SweetAlert is not available
        alert(message)
      }
    }
    
    const activeTab = ref('jobs')
    const sidebarOpen = ref(true)
    const showUserDropdown = ref(false)
    const technician = ref(null)
    
    const handleTabClick = (tabId) => {
      activeTab.value = tabId
      // Close sidebar on mobile/tablet after clicking
      if (window.innerWidth < 1024) {
        sidebarOpen.value = false
      }
    }

    // Watch for tab changes to load data
    watch(activeTab, (newTab) => {
      if (newTab === 'reassignment-logs') {
        loadReassignmentLogs(1)
      }
    })
    const jobs = ref([])
    const jobsPagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      data: []
    })
    const earnings = ref({})
    const earningsPeriod = ref('month')
    const jobStatusFilter = ref('all')
    const reassignmentLogs = ref([])
    const reassignmentLogsPagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      data: []
    })
    
    // Filter variables
    const farmFilter = ref('')
    const farmerFilter = ref('')
    const statusFilter = ref('')
    const dateFilter = ref('')
    const customDateFrom = ref('')
    const customDateTo = ref('')
    
    const profileForm = ref({
      name: '',
      father_name: '',
      cnic: '',
      date_of_birth: '',
      mobile_1: '',
      mobile_2: '',
      email: '',
      home_address: '',
      clinic_address: '',
      province: '',
      division: '',
      district: '',
      tehsil_city: '',
      years_of_experience: 0,
      degree_year: '',
      customers_per_month: 0,
      specialization: '',
      areas_of_expertise: '',
      clinic_latitude: '',
      clinic_longitude: ''
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
        }
      },
      'Islamabad': {
        divisions: ['Islamabad'],
        districts: {
          'Islamabad': ['Islamabad']
        }
      }
    }
    
    // New reactive variables
    const showJobModal = ref(false)
    const showETAModal = ref(false)
    const showJobDetailsModal = ref(false)
    const showReassignModal = ref(false)
    const selectedJob = ref(null)
    const selectedJobForDetails = ref(null)
    const selectedJobForReassign = ref(null)
    const selectedJobForPregnancy = ref(null)
    const selectedTechnicianForReassign = ref(null)
    const reassignReason = ref('')
    const availableTechnicians = ref([])
    const technicianSearchQuery = ref('')
    const radiusFilter = ref('all')
    const visitAddress = ref('')
    const farmAddress = ref('')
    const jobStats = ref({})
    const inventory = ref({})
    const workingHours = ref({
      start_time: '09:00',
      end_time: '17:00',
      available_24_7: false
    })
    // const serviceAreas = ref([])
    
    // Farm visit form
    const farmVisitForm = ref({
      latitude: '',
      longitude: '',
      notes: '',
      cow_records: []
    })
    
    // ETA form
    const etaForm = ref({
      eta: '',
      notes: ''
    })
    
    // Technician location for Google Maps
    const technicianLocation = ref({
      lat: 31.5204, // Default to Lahore, Pakistan
      lng: 74.3587
    })
    
    // Farm location for the visit form map
    const farmLocation = ref({
      lat: null,
      lng: null
    })
    
    // Technician current location for the visit form map
    const technicianCurrentLocation = ref({
      lat: null,
      lng: null
    })
    
    // Document upload state
    const documents = ref({
      cnic_front: null,
      cnic_back: null,
      degree_front: null,
      degree_back: null
    })
    
    const uploading = ref({
      cnic_front: false,
      cnic_back: false,
      degree_front: false,
      degree_back: false
    })
    
    // Notification state
    const notification = ref({
      show: false,
      message: '',
      type: 'success' // success, error, info
    })
    
    // Profile completion alert state
    const profileCompletionAlert = ref({
      show: false,
      message: 'Welcome! Please complete your profile to get started. Fill in all required information to access full features.'
    })
    
    const tabs = [
      { id: 'jobs', name: 'My Jobs' },
      { id: 'pregnancy-tracking', name: 'Pregnancy Tracking' },
      { id: 'availability', name: 'Availability' },
      { id: 'inventory', name: 'Inventory' },
      { id: 'earnings', name: 'Earnings' },
      { id: 'reassignment-logs', name: 'Reassignment Logs' },
      { id: 'profile', name: 'Profile' }
    ]
    
    const pendingJobsCount = computed(() => 
      jobs.value.filter(job => job.status === 'pending').length
    )
    
    const completedJobsCount = computed(() => 
      jobs.value.filter(job => job.status === 'completed').length
    )
    
    const completedJobs = computed(() => 
      jobs.value.filter(job => job.status === 'completed' && job.cow_insemination_records && job.cow_insemination_records.length > 0)
    )
    
    const filteredCompletedJobs = computed(() => {
      if (selectedJobForPregnancy.value) {
        return completedJobs.value.filter(job => job.id === selectedJobForPregnancy.value)
      }
      return completedJobs.value
    })
    
    const totalEarnings = computed(() => {
      const total = jobs.value.reduce((sum, job) => {
        // Handle malformed amount_paid values
        let amount = job.amount_paid
        if (typeof amount === 'string') {
          // Remove any non-numeric characters except decimal point
          amount = amount.replace(/[^\d.]/g, '')
          // Handle cases where there might be multiple decimal points
          const parts = amount.split('.')
          if (parts.length > 2) {
            // Take only the first part as integer and second part as decimal
            amount = parts[0] + '.' + parts.slice(1).join('')
          }
        }
        return sum + (Number(amount) || 0)
      }, 0)
      return total.toLocaleString('en-PK', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })
    })
    
    const recentJobs = computed(() => 
      jobs.value.slice(0, 5)
    )
    
    const averagePerJob = computed(() => 
      earnings.value.job_count > 0 ? (earnings.value.total / earnings.value.job_count).toFixed(2) : 0
    )
    
    // Filter computed properties
    const uniqueFarms = computed(() => {
      const farms = jobs.value.map(job => job.order?.cattle_farm?.name).filter(Boolean)
      return [...new Set(farms)]
    })
    
    const uniqueFarmers = computed(() => {
      const farmers = jobs.value.map(job => job.order?.farmer?.user?.name).filter(Boolean)
      return [...new Set(farmers)]
    })
    
    const filteredJobs = computed(() => {
      let filtered = jobs.value
      
      if (farmFilter.value) {
        filtered = filtered.filter(job => job.order?.cattle_farm?.name === farmFilter.value)
      }
      
      if (farmerFilter.value) {
        filtered = filtered.filter(job => job.order?.farmer?.user?.name === farmerFilter.value)
      }
      
      if (statusFilter.value) {
        filtered = filtered.filter(job => job.status === statusFilter.value)
      }
      
      if (dateFilter.value) {
        const now = new Date()
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
        
        filtered = filtered.filter(job => {
          const jobDate = new Date(job.order?.created_at || job.created_at)
          
          switch (dateFilter.value) {
            case 'today':
              return jobDate >= today
            case 'week':
              const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000)
              return jobDate >= weekAgo
            case 'month':
              const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000)
              return jobDate >= monthAgo
            case 'custom':
              if (customDateFrom.value && customDateTo.value) {
                const fromDate = new Date(customDateFrom.value)
                const toDate = new Date(customDateTo.value)
                // Set time to end of day for toDate to include the entire day
                toDate.setHours(23, 59, 59, 999)
                return jobDate >= fromDate && jobDate <= toDate
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
      return jobsPagination.value.last_page || 1
    })
    
    const paginatedJobs = computed(() => {
      return jobsPagination.value.data || []
    })
    
    const paginationInfo = computed(() => {
      const start = (jobsPagination.value.current_page - 1) * jobsPagination.value.per_page + 1
      const end = Math.min(jobsPagination.value.current_page * jobsPagination.value.per_page, jobsPagination.value.total)
      return {
        start,
        end,
        total: jobsPagination.value.total || 0
      }
    })
    
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
    
    // Profile completion percentage
    const profileCompletion = computed(() => {
      const requiredFields = ['name', 'father_name', 'cnic', 'date_of_birth', 'mobile_1', 'home_address', 'city', 'province', 'district', 'tehsil', 'years_of_experience', 'degree_year', 'customers_per_month', 'areas_of_expertise', 'clinic_latitude', 'clinic_longitude']
      const documentFields = ['cnic_front', 'cnic_back', 'degree_front', 'degree_back']
      
      const filledFields = requiredFields.filter(field => profileForm.value[field] && profileForm.value[field] !== '')
      const filledDocuments = documentFields.filter(field => documents.value[field])
      
      const totalFields = requiredFields.length + documentFields.length
      const totalFilled = filledFields.length + filledDocuments.length
      
      return Math.round((totalFilled / totalFields) * 100)
    })
    
    // Check if profile is complete for submission
    const isProfileComplete = computed(() => {
      return profileCompletion.value >= 80 // 80% completion required
    })
    
    // Stock status helper
    const getStockStatus = (quantity) => {
      if (quantity === 0) return 'Out of Stock'
      if (quantity <= 5) return 'Low Stock'
      return 'Available'
    }
    
    const getStockStatusClass = (quantity) => {
      if (quantity === 0) return 'bg-red-100 text-red-800'
      if (quantity <= 5) return 'bg-yellow-100 text-yellow-800'
      return 'bg-green-100 text-green-800'
    }
    
    // Status badge classes
    const getStatusBadgeClass = (status) => {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
    
    const loadProfile = async () => {
      try {
        console.log('Loading technician profile...')
        const response = await window.axios.get('/technician/profile')
        console.log('Profile response:', response.data)
        technician.value = response.data.technician
        console.log('Technician data:', technician.value)
        console.log('Degree year from API:', technician.value.degree_year)
        profileForm.value = {
          name: technician.value.name,
          father_name: technician.value.father_name,
          cnic: technician.value.cnic,
          date_of_birth: technician.value.date_of_birth,
          mobile_1: technician.value.mobile_1,
          mobile_2: technician.value.mobile_2,
          home_address: technician.value.home_address,
          clinic_address: technician.value.clinic_address,
          province: technician.value.province || '',
          division: technician.value.division || '',
          district: technician.value.district || '',
          tehsil_city: technician.value.tehsil_city || technician.value.tehsil || technician.value.city || '',
          years_of_experience: technician.value.years_of_experience,
          degree_year: technician.value.degree_year ? String(technician.value.degree_year) : '',
          customers_per_month: technician.value.customers_per_month,
          areas_of_expertise: technician.value.areas_of_expertise,
          clinic_latitude: technician.value.clinic_latitude || '',
          clinic_longitude: technician.value.clinic_longitude || ''
        }
        
        console.log('Profile form after assignment:', profileForm.value)
        console.log('Degree year in form:', profileForm.value.degree_year)
        
        // Set technician location for Google Maps
        if (technician.value.clinic_latitude && technician.value.clinic_longitude) {
          technicianLocation.value = {
            lat: parseFloat(technician.value.clinic_latitude),
            lng: parseFloat(technician.value.clinic_longitude)
          }
        }
        
        // Load document URLs
        documents.value = {
          cnic_front: technician.value.cnic_front_image ? getImageUrl(technician.value.cnic_front_image) : null,
          cnic_back: technician.value.cnic_back_image ? getImageUrl(technician.value.cnic_back_image) : null,
          degree_front: technician.value.degree_front_image ? getImageUrl(technician.value.degree_front_image) : null,
          degree_back: technician.value.degree_back_image ? getImageUrl(technician.value.degree_back_image) : null
        }
        
        // Load working hours and service areas
        
        if (technician.value.working_hours_start) {
          // Format datetime to time string (HH:MM)
          const startTime = new Date(technician.value.working_hours_start)
          // Use UTC time to avoid timezone issues
          const hours = startTime.getUTCHours().toString().padStart(2, '0')
          const minutes = startTime.getUTCMinutes().toString().padStart(2, '0')
          workingHours.value.start_time = `${hours}:${minutes}`
        }
        if (technician.value.working_hours_end) {
          // Format datetime to time string (HH:MM)
          const endTime = new Date(technician.value.working_hours_end)
          // Use UTC time to avoid timezone issues
          const hours = endTime.getUTCHours().toString().padStart(2, '0')
          const minutes = endTime.getUTCMinutes().toString().padStart(2, '0')
          workingHours.value.end_time = `${hours}:${minutes}`
        }
        if (technician.value.available_24_7 !== undefined) {
          workingHours.value.available_24_7 = technician.value.available_24_7
        }
        // if (technician.value.service_areas) {
        //   serviceAreas.value = technician.value.service_areas
        // }
      } catch (error) {
        console.error('Error loading profile:', error)
        // Set default data for demo purposes
        technician.value = {
          name: 'Dr. Sarah Ahmed',
          availability: 'online',
          years_of_experience: 5,
          customers_per_month: 25
        }
      }
    }
    
    const updateTechnicianLocation = (location) => {
      technicianLocation.value = location
      profileForm.value.clinic_latitude = location.lat.toString()
      profileForm.value.clinic_longitude = location.lng.toString()
    }
    
    const openInGoogleMaps = (latitude, longitude) => {
      const url = `https://www.google.com/maps?q=${latitude},${longitude}`
      window.open(url, '_blank')
    }
    
    const getFarmAddress = async (latitude, longitude) => {
      try {
        const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=en`)
        const data = await response.json()
        
        if (data.localityInfo && data.localityInfo.administrative) {
          const addressParts = []
          if (data.locality) addressParts.push(data.locality)
          if (data.principalSubdivision) addressParts.push(data.principalSubdivision)
          if (data.countryName) addressParts.push(data.countryName)
          return addressParts.join(', ')
        }
        
        return `${latitude}, ${longitude}`
      } catch (error) {
        console.error('Error getting address:', error)
        return `${latitude}, ${longitude}`
      }
    }
    
    const loadJobs = async () => {
      try {
        console.log('Loading technician jobs...')
        const params = new URLSearchParams()
        if (jobStatusFilter.value !== 'all') {
          params.append('status', jobStatusFilter.value)
        }
        
        const response = await window.axios.get(`/technician/jobs?${params}`)
        console.log('Jobs response:', response.data)
        jobs.value = response.data.jobs?.data || response.data.jobs || []
        
        // Update job statistics after loading jobs
        await loadJobStats()
      } catch (error) {
        console.error('Error loading jobs:', error)
        // Set sample data for demo purposes
        jobs.value = [
          {
            id: 1,
            status: 'pending',
            amount_paid: 200,
            created_at: new Date().toISOString(),
            order: {
              farmer: { 
                user: { name: 'Ahmed Ali' },
                mobile: '+923001234567',
                date_of_birth: '1985-01-15',
                status: 'active'
              },
              cattle_farm: { 
                name: 'Green Valley Farm',
                latitude: 31.5204,
                longitude: 74.3587,
                number_of_cows: 25,
                number_of_calves: 8,
                number_of_bulls: 3
              }
            }
          },
          {
            id: 2,
            status: 'completed',
            amount_paid: 250,
            created_at: new Date(Date.now() - 86400000).toISOString(),
            order: {
              farmer: { 
                user: { name: 'Fatima Khan' },
                mobile: '+923001234568',
                date_of_birth: '1988-03-22',
                status: 'active'
              },
              cattle_farm: { 
                name: 'Sunrise Dairy',
                latitude: 31.5404,
                longitude: 74.3687,
                number_of_cows: 30,
                number_of_calves: 12,
                number_of_bulls: 4
              }
            }
          }
        ]
        
        // Update job statistics after setting sample data
        await loadJobStats()
      }
    }
    
    const loadJobsWithPagination = async (page = 1) => {
      try {
        console.log('Loading technician jobs with pagination...')
        const params = new URLSearchParams()
        
        // Add status filter
        if (jobStatusFilter.value !== 'all') {
          params.append('status', jobStatusFilter.value)
        }
        
        // Add other filters
        if (farmFilter.value) {
          params.append('farm', farmFilter.value)
        }
        
        if (farmerFilter.value) {
          params.append('farmer', farmerFilter.value)
        }
        
        if (statusFilter.value) {
          params.append('job_status', statusFilter.value)
        }
        
        if (dateFilter.value) {
          params.append('date_filter', dateFilter.value)
          
          if (dateFilter.value === 'custom' && customDateFrom.value && customDateTo.value) {
            params.append('date_from', customDateFrom.value)
            params.append('date_to', customDateTo.value)
          }
        }
        
        params.append('page', page)
        params.append('per_page', '10')
        
        const response = await window.axios.get(`/technician/jobs?${params}`)
        console.log('Jobs pagination response:', response.data)
        jobsPagination.value = response.data.jobs
        
        // Update job statistics after loading jobs
        await loadJobStats()
      } catch (error) {
        console.error('Error loading jobs with pagination:', error)
      }
    }
    
    // Pagination methods (server-side)
    const goToPage = async (page) => {
      if (page >= 1 && page <= totalPages.value) {
        await loadJobsWithPagination(page)
      }
    }
    
    const nextPage = async () => {
      if (jobsPagination.value.current_page < totalPages.value) {
        await loadJobsWithPagination(jobsPagination.value.current_page + 1)
      }
    }
    
    const prevPage = async () => {
      if (jobsPagination.value.current_page > 1) {
        await loadJobsWithPagination(jobsPagination.value.current_page - 1)
      }
    }
    
    const loadReassignmentLogs = async (page = 1) => {
      try {
        console.log('Loading reassignment logs...')
        const response = await window.axios.get(`/technician/reassignment-logs?page=${page}&per_page=10`)
        console.log('Reassignment logs response:', response.data)
        reassignmentLogsPagination.value = response.data.logs
      } catch (error) {
        console.error('Error loading reassignment logs:', error)
      }
    }

    const loadEarnings = async () => {
      try {
        console.log('Loading technician earnings...')
        const response = await window.axios.get(`/technician/earnings?period=${earningsPeriod.value}`)
        console.log('Earnings response:', response.data)
        earnings.value = response.data.earnings
      } catch (error) {
        console.error('Error loading earnings:', error)
        // Set sample data for demo purposes
        earnings.value = {
          total: 1250,
          job_count: 5,
          jobs: [
            {
              id: 1,
              amount_paid: 200,
              created_at: new Date().toISOString(),
              order: { farmer: { user: { name: 'Ahmed Ali' } } }
            },
            {
              id: 2,
              amount_paid: 250,
              created_at: new Date(Date.now() - 86400000).toISOString(),
              order: { farmer: { user: { name: 'Fatima Khan' } } }
            }
          ]
        }
      }
    }
    
    const acceptJob = async (job) => {
      try {
        // Set ETA to 2 hours from now in Pakistan timezone
        const now = new Date()
        const pakistanTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Karachi"}))
        pakistanTime.setHours(pakistanTime.getHours() + 2) // 2 hours from now
        
        await window.axios.put(`/technician/jobs/${job.id}/accept`, 
          { estimated_visit_at: pakistanTime.toISOString() }
        )
        job.status = 'accepted'
        job.progress_status = 'accepted'
        await loadJobStats()
      } catch (error) {
        console.error('Error accepting job:', error)
      }
    }
    
    const rejectJob = async (job) => {
      try {
        await window.axios.put(`/technician/jobs/${job.id}/reject`, 
          { notes: 'Rejected by technician' }
        )
        job.status = 'rejected'
        job.progress_status = 'rejected'
        await loadJobStats()
      } catch (error) {
        console.error('Error rejecting job:', error)
      }
    }
    
    const completeJob = async (job) => {
      // Open the job modal for completion details
      openJobModal(job)
    }
    
    // Handle complete job button click
    const handleCompleteJob = () => {
      console.log('handleCompleteJob called')
      console.log('farmVisitForm:', farmVisitForm)
      console.log('farmVisitForm.value:', farmVisitForm.value)
      console.log('farmVisitForm.value.cow_photo:', farmVisitForm.value.cow_photo)
      console.log('farmVisitForm.value.straw_photo:', farmVisitForm.value.straw_photo)
      
      if (!farmVisitForm.value) {
        console.error('farmVisitForm.value is undefined')
        showError('Form data is not available. Please try again.')
        return
      }
      
      submitJobCompletion(farmVisitForm.value)
    }
    
    // New function to handle job completion using the complete endpoint
    const submitJobCompletion = async (completionData) => {
      try {
        console.log('Submitting job completion with data:', completionData)
        
        // Check if completionData is undefined or null
        if (!completionData) {
          console.error('completionData is undefined or null')
          showError('Form data is missing. Please try again.')
          return
        }
        
        console.log('completionData.latitude:', completionData.latitude)
        console.log('completionData.longitude:', completionData.longitude)
        console.log('completionData keys:', Object.keys(completionData))
        
        // Validate required fields
        if (!completionData.latitude || !completionData.longitude) {
          console.error('Missing location data:', { latitude: completionData.latitude, longitude: completionData.longitude })
          showError('Please select a visit location before completing the job.')
          return
        }
        
        // Validate cow records
        if (!completionData.cow_records || !Array.isArray(completionData.cow_records) || completionData.cow_records.length === 0) {
          showError('No cow records found. Please try again.')
          return
        }
        
        // Validate each cow record
        for (let i = 0; i < completionData.cow_records.length; i++) {
          const cowRecord = completionData.cow_records[i]
          if (!cowRecord.cow_in_heat) {
            showError(`Please select the cow heat status for Cow ${i + 1} before completing the job.`)
            return
          }
        }
        
        const formData = new FormData()
        
        // Add basic form data
        const latitude = parseFloat(completionData.latitude)
        const longitude = parseFloat(completionData.longitude)
        
        console.log('Form data values:', {
          latitude,
          longitude,
          cowRecords: completionData.cow_records
        })
        
        formData.append('visit_latitude', latitude)
        formData.append('visit_longitude', longitude)
        
        // Add each cow record
        completionData.cow_records.forEach((cowRecord, index) => {
          console.log(`Processing cow record ${index + 1}:`, cowRecord)
          
          // Add cow record data
          formData.append(`cow_records[${index}][order_item_id]`, cowRecord.order_item_id)
          formData.append(`cow_records[${index}][cow_in_heat]`, cowRecord.cow_in_heat)
          formData.append(`cow_records[${index}][amount_received]`, cowRecord.amount_received || 0)
          formData.append(`cow_records[${index}][payment_method]`, cowRecord.payment_method || '')
          formData.append(`cow_records[${index}][straw_barcode_code]`, cowRecord.straw_barcode_code || '')
          formData.append(`cow_records[${index}][notes]`, cowRecord.notes || '')
          
          // Add cow photo if exists
          if (cowRecord.cow_photo && cowRecord.cow_photo.file) {
            console.log(`Adding cow photo for cow ${index + 1}:`, cowRecord.cow_photo.file.name)
            formData.append(`cow_records[${index}][cow_photo]`, cowRecord.cow_photo.file)
          }
          
          // Add straw photo if exists
          if (cowRecord.straw_photo && cowRecord.straw_photo.file) {
            console.log(`Adding straw photo for cow ${index + 1}:`, cowRecord.straw_photo.file.name)
            formData.append(`cow_records[${index}][straw_photo]`, cowRecord.straw_photo.file)
          }
        })
        
        // Debug: Log all FormData entries
        console.log('FormData entries:')
        for (let [key, value] of formData.entries()) {
          console.log(`${key}:`, value)
        }
        
        // Debug: Check if FormData is properly constructed
        console.log('FormData instanceof FormData:', formData instanceof FormData)
        console.log('FormData constructor:', formData.constructor.name)
        
        // Debug: Check axios configuration
        console.log('Axios defaults:', window.axios.defaults.headers.common)
        
        // Debug: Check if FormData is being recognized by axios
        console.log('FormData type check:', typeof formData, formData.constructor.name)
        console.log('FormData entries count:', Array.from(formData.entries()).length)
        
        // Use POST instead of PUT for FormData (Laravel limitation with multipart/form-data)
        formData.append('_method', 'PUT')
        const response = await window.axios.post(`/technician/jobs/${selectedJob.value.id}/complete`, formData)
        
        console.log('Job completion response:', response.data)
        
        // Update job status from backend response
        if (response.data.success && response.data.job) {
          selectedJob.value.status = response.data.job.status
          selectedJob.value.progress_status = response.data.job.progress_status
        }
        
        closeJobModal()
        await loadJobStats()
        await loadJobs() // Refresh the job list to show updated data
        await loadJobsWithPagination(1) // Also refresh paginated jobs
        showSuccess('Job completed successfully!')
      } catch (error) {
        console.error('Error completing job:', error)
        
        // Log detailed error information
        if (error.response) {
          console.error('Response status:', error.response.status)
          console.error('Response data:', error.response.data)
          console.error('Response headers:', error.response.headers)
          
          if (error.response.data && error.response.data.errors) {
            console.error('Validation errors:', error.response.data.errors)
            // Show specific validation errors
            const errors = error.response.data.errors
            const errorMessages = Object.values(errors).flat()
            showError(`Validation failed: ${errorMessages.join(', ')}`)
          } else {
            showError(error.response.data.message || 'Failed to complete job. Please try again.')
          }
        } else if (error.request) {
          console.error('Request error:', error.request)
          showError('Network error. Please check your connection and try again.')
        } else {
          console.error('Error:', error.message)
          showError('Failed to complete job. Please try again.')
        }
      }
    }
    
    const updateProfile = async () => {
      try {
        console.log('Updating profile with data:', profileForm.value)
        const response = await window.axios.put('/technician/profile', profileForm.value)
        console.log('Profile update response:', response.data)
        
        if (response.data.success) {
          // Show success message
          showNotification('Profile updated successfully!', 'success')
          // Reload profile to get updated data
          await loadProfile()
        } else {
          showNotification('Update failed: ' + (response.data.message || 'Unknown error'), 'error')
        }
      } catch (error) {
        console.error('Error updating profile:', error)
        if (error.response && error.response.data && error.response.data.message) {
          showNotification('Update failed: ' + error.response.data.message, 'error')
        } else {
          showNotification('Update failed. Please try again.', 'error')
        }
      }
    }
    
    const updateAvailability = async (availability) => {
      try {
        const data = {
          availability,
          working_hours_start: workingHours.value.start_time,
          working_hours_end: workingHours.value.end_time,
          available_24_7: workingHours.value.available_24_7,
          // service_areas: serviceAreas.value
        }
        
        await window.axios.put('/technician/availability', data)
        technician.value.availability = availability
        technician.value.working_hours_start = workingHours.value.start_time
        technician.value.working_hours_end = workingHours.value.end_time
        technician.value.available_24_7 = workingHours.value.available_24_7
        // technician.value.service_areas = serviceAreas.value
        
        showSuccess('Availability settings updated successfully!')
      } catch (error) {
        console.error('Error updating availability:', error)
        showError('Failed to update availability settings')
      }
    }
    
    const saveAvailabilitySettings = async () => {
      try {
        const data = {
          availability: technician.value.availability,
          working_hours_start: workingHours.value.start_time,
          working_hours_end: workingHours.value.end_time,
          available_24_7: workingHours.value.available_24_7,
          // service_areas: serviceAreas.value
        }
        
        await window.axios.put('/technician/availability', data)
        technician.value.working_hours_start = workingHours.value.start_time
        technician.value.working_hours_end = workingHours.value.end_time
        technician.value.available_24_7 = workingHours.value.available_24_7
        // technician.value.service_areas = serviceAreas.value
        
        showSuccess('Settings saved successfully!')
      } catch (error) {
        console.error('Error saving settings:', error)
        showError('Failed to save settings')
      }
    }
    
    // New functions for enhanced functionality
    const loadInventory = async () => {
      try {
        const response = await window.axios.get('/technician/inventory')
        if (response.data.success) {
          inventory.value = {
            total_items: response.data.total_items,
            available_items: response.data.available_items,
            low_stock_items: response.data.low_stock_items,
            out_of_stock: response.data.out_of_stock,
            items: response.data.items
          }
        } else {
          throw new Error(response.data.message || 'Failed to load inventory')
        }
      } catch (error) {
        console.error('Error loading inventory:', error)
        // Set empty data on error
        inventory.value = {
          total_items: 0,
          available_items: 0,
          low_stock_items: 0,
          out_of_stock: 0,
          items: []
        }
      }
    }
    
    const loadJobStats = async () => {
      // Get reassignment logs to count reassigned jobs
      let reassignedCount = 0
      try {
        const response = await window.axios.get('/technician/reassignment-logs')
        if (response.data.success) {
          // Count jobs that were reassigned by this technician
          reassignedCount = response.data.logs.data.filter(log => 
            log.reassigned_by_technician_id === technician.value?.id
          ).length
        }
      } catch (error) {
        console.error('Error loading reassignment logs for stats:', error)
      }

      jobStats.value = {
        pending: jobs.value.filter(job => job.status === 'pending').length,
        completed: jobs.value.filter(job => job.status === 'completed').length,
        in_progress: jobs.value.filter(job => job.status === 'in_progress' || job.status === 'accepted').length,
        reassigned: reassignedCount
      }
    }
    
    // Document upload handling
    const handleDocumentUpload = async (type, event) => {
      const file = event.target.files[0]
      if (!file) return
      
      // Validate file size (10MB max)
      if (file.size > 10 * 1024 * 1024) {
        showInfo('File size must be less than 10MB')
        return
      }
      
      // Validate file type
      const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']
      if (!allowedTypes.includes(file.type)) {
        showWarning('Please upload a valid image (JPEG, PNG) or PDF file')
        return
      }
      
      uploading.value[type] = true
      
      try {
        const formData = new FormData()
        formData.append('document', file)
        formData.append('document_type', type)
        
        const response = await window.axios.post('/technician/upload-document', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        
        if (response.data.success) {
          documents.value[type] = response.data.file_url
          // Reload profile to get updated data
          await loadProfile()
        } else {
          showInfo('Upload failed: ' + response.data.message)
        }
      } catch (error) {
        console.error('Upload error:', error)
        showError('Upload failed. Please try again.')
      } finally {
        uploading.value[type] = false
        // Reset file input
        event.target.value = ''
      }
    }
    
    const deleteDocument = async (type) => {
      const result = await window.confirm(
        'Are you sure you want to delete this document?',
        'Delete Document'
      )
      
      if (!result.isConfirmed) return
      
      try {
        const response = await window.axios.delete('/technician/delete-document', {
          data: { document_type: type }
        })
        
        if (response.data.success) {
          documents.value[type] = null
          // Reload profile to get updated data
          await loadProfile()
        } else {
          showInfo('Delete failed: ' + response.data.message)
        }
      } catch (error) {
        console.error('Delete error:', error)
        showError('Delete failed. Please try again.')
      }
    }
    
    const viewDocument = (url) => {
      window.open(url, '_blank')
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
    
    const dismissProfileAlert = () => {
      profileCompletionAlert.value.show = false
      localStorage.removeItem('showProfileCompletionAlert')
    }
    
    // Location services
    const getCurrentLocation = () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const lat = position.coords.latitude
            const lng = position.coords.longitude
            profileForm.value.latitude = lat
            profileForm.value.longitude = lng
            farmVisitForm.value.latitude = lat
            farmVisitForm.value.longitude = lng
          },
          (error) => {
            console.error('Error getting location:', error)
            showWarning('Unable to get your location. Please enter manually.')
          }
        )
      } else {
        showInfo('Geolocation is not supported by this browser.')
      }
    }
    
    // Profile submission for approval
    const submitForApproval = async () => {
      try {
        await window.axios.post('/technician/profile/submit-for-approval')
        showSuccess('Profile submitted for approval successfully!')
        loadProfile()
      } catch (error) {
        console.error('Error submitting profile:', error)
        showError('Failed to submit profile. Please try again.')
      }
    }
    
    // Job management functions
    const provideETA = (job) => {
      selectedJob.value = job
      // Set default ETA to 1 hour from now in Pakistan timezone
      const now = new Date()
      const pakistanTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Karachi"}))
      pakistanTime.setHours(pakistanTime.getHours() + 1)
      
      // Format for datetime-local input (YYYY-MM-DDTHH:MM)
      const year = pakistanTime.getFullYear()
      const month = String(pakistanTime.getMonth() + 1).padStart(2, '0')
      const day = String(pakistanTime.getDate()).padStart(2, '0')
      const hours = String(pakistanTime.getHours()).padStart(2, '0')
      const minutes = String(pakistanTime.getMinutes()).padStart(2, '0')
      
      etaForm.value.eta = `${year}-${month}-${day}T${hours}:${minutes}`
      etaForm.value.notes = ''
      showETAModal.value = true
    }
    
    const submitETA = async () => {
      try {
        // Validate that ETA is in the future
        const etaDate = new Date(etaForm.value.eta)
        const now = new Date()
        
        if (etaDate <= now) {
          showError('ETA must be a future date and time.')
          return
        }
        
        // Convert the ETA to Pakistan timezone before sending to server
        // This ensures the time is treated as Pakistan time, not local browser time
        const pakistanETA = new Date(etaForm.value.eta + '+05:00')
        
        const payload = {
          eta: pakistanETA.toISOString(),
          notes: etaForm.value.notes
        }
        
        await window.axios.put(`/technician/jobs/${selectedJob.value.id}/eta`, payload)
        selectedJob.value.estimated_visit_at = pakistanETA.toISOString()
        selectedJob.value.status = 'eta_provided' // Update status after ETA is provided
        showETAModal.value = false
        etaForm.value = { eta: '', notes: '' }
        showSuccess('ETA submitted successfully!')
        // Reload jobs to get updated data
        await loadJobs()
      } catch (error) {
        console.error('Error submitting ETA:', error)
        
        // Show specific validation errors
        if (error.response?.data?.errors) {
          const errors = error.response.data.errors
          if (errors.eta) {
            showError(`ETA Error: ${errors.eta[0]}`)
          } else {
            showError('Validation failed. Please check your input.')
          }
        } else {
          showError('Failed to submit ETA. Please try again.')
        }
      }
    }
    
    const startJob = async (job) => {
      try {
        await window.axios.put(`/technician/jobs/${job.id}/start`)
        job.status = 'in_progress'
        await loadJobStats()
        showSuccess('Job started successfully!')
      } catch (error) {
        console.error('Error starting job:', error)
        showError('Failed to start job. Please try again.')
      }
    }
    
    // Confirmation functions with SweetAlert
    const confirmAcceptJob = async (job) => {
      const result = await Swal.fire({
        title: 'Accept Job?',
        text: `Are you sure you want to accept this job for ${job.order?.farmer?.user?.name} at ${job.order?.cattle_farm?.name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Accept',
        cancelButtonText: 'Cancel'
      })
      
      if (result.isConfirmed) {
        await acceptJob(job)
      }
    }
    
    
    const confirmStartJob = async (job) => {
      const result = await Swal.fire({
        title: 'Start Job?',
        text: `Are you ready to start this job for ${job.order?.farmer?.user?.name} at ${job.order?.cattle_farm?.name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Start',
        cancelButtonText: 'Cancel'
      })
      
      if (result.isConfirmed) {
        await startJob(job)
      }
    }
    
    const confirmCompleteJob = async (job) => {
      const result = await Swal.fire({
        title: 'Complete Job?',
        text: `Are you sure you want to mark this job as completed for ${job.order?.farmer?.user?.name} at ${job.order?.cattle_farm?.name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Complete',
        cancelButtonText: 'Cancel'
      })
      
      if (result.isConfirmed) {
        await completeJob(job)
      }
    }
    
    // Reassign functionality
    const openReassignModal = async (job) => {
      selectedJobForReassign.value = job
      selectedTechnicianForReassign.value = null
      reassignReason.value = ''
      technicianSearchQuery.value = ''
      radiusFilter.value = 'all'
      showReassignModal.value = true
      await searchTechnicians()
    }
    
    const closeReassignModal = () => {
      showReassignModal.value = false
      selectedJobForReassign.value = null
      selectedTechnicianForReassign.value = null
      reassignReason.value = ''
      availableTechnicians.value = []
      technicianSearchQuery.value = ''
      radiusFilter.value = 'all'
    }
    
    const searchTechnicians = async () => {
      try {
        const params = new URLSearchParams()
        if (technicianSearchQuery.value) {
          params.append('search', technicianSearchQuery.value)
        }
        if (radiusFilter.value !== 'all') {
          params.append('radius', radiusFilter.value)
        }
        if (selectedJobForReassign.value?.order?.cattle_farm?.latitude && selectedJobForReassign.value?.order?.cattle_farm?.longitude) {
          params.append('farm_lat', selectedJobForReassign.value.order.cattle_farm.latitude)
          params.append('farm_lng', selectedJobForReassign.value.order.cattle_farm.longitude)
        }
        
        const response = await window.axios.get(`/technician/available-technicians?${params}`)
        availableTechnicians.value = response.data.technicians || []
      } catch (error) {
        console.error('Error searching technicians:', error)
        showError('Failed to search technicians. Please try again.')
      }
    }
    
    const selectTechnician = (technician) => {
      selectedTechnicianForReassign.value = technician
    }
    
    const confirmReassignJob = async () => {
      if (!selectedTechnicianForReassign.value) {
        showError('Please select a technician to reassign the job to.')
        return
      }
      
      if (!reassignReason.value.trim()) {
        showError('Please provide a reason for reassigning this job.')
        return
      }
      
      const result = await Swal.fire({
        title: 'Reassign Job?',
        text: `Are you sure you want to reassign this job to ${selectedTechnicianForReassign.value.user.name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Reassign',
        cancelButtonText: 'Cancel'
      })
      
      if (result.isConfirmed) {
        await reassignJob()
      }
    }
    
    const reassignJob = async () => {
      try {
        console.log('Reassigning job:', {
          jobId: selectedJobForReassign.value.id,
          jobTechnicianId: selectedJobForReassign.value.technician_id,
          newTechnicianId: selectedTechnicianForReassign.value.id,
          reason: reassignReason.value.trim()
        })
        
        await window.axios.put(`/technician/jobs/${selectedJobForReassign.value.id}/reassign`, {
          technician_id: selectedTechnicianForReassign.value.id,
          reason: reassignReason.value.trim()
        })
        
        showSuccess('Job reassigned successfully!')
        closeReassignModal()
        await loadJobs() // Reload jobs to reflect the change
        await loadJobsWithPagination(1) // Also reload paginated jobs
        await loadJobStats() // Update stats to reflect reassignment
      } catch (error) {
        console.error('Error reassigning job:', error)
        const errorMessage = error.response?.data?.message || 'Failed to reassign job'
        
        // If the error indicates the job is no longer assigned to this technician, refresh the page
        if (errorMessage.includes('no longer assigned to you')) {
          showError(errorMessage + ' Refreshing page...')
          setTimeout(() => {
            window.location.reload()
          }, 2000)
        } else {
          showError(errorMessage)
        }
      }
    }
    
    
    const openJobModal = (job) => {
      console.log('=== OPENING JOB MODAL ===')
      selectedJob.value = job
      console.log('Opening job modal for job:', job)
      console.log('Job order data:', job.order)
      console.log('Farm latitude:', job.order?.cattle_farm?.latitude)
      console.log('Farm longitude:', job.order?.cattle_farm?.longitude)
      
      // Initialize cow records for each order item
      const cowRecords = job.order?.order_items?.map(item => ({
        order_item_id: item.id,
        cow_in_heat: '',
        straw_barcode_code: '',
        amount_received: item.total_price || 0,
        payment_method: '',
        notes: '',
        cow_photo: null,
        straw_photo: null
      })) || []
      
      const newFormData = {
        latitude: job.order?.cattle_farm?.latitude || '31.5204',
        longitude: job.order?.cattle_farm?.longitude || '74.3587',
        notes: '',
        cow_records: cowRecords
      }
      
      console.log('Setting farmVisitForm.value to:', newFormData)
      farmVisitForm.value = newFormData
      
      console.log('farmVisitForm.value after setting:', farmVisitForm.value)
      console.log('farmVisitForm ref object:', farmVisitForm)
      
      // Initialize farm location for the map (fallback)
      farmLocation.value = {
        lat: parseFloat(job.order?.cattle_farm?.latitude) || 31.5204,
        lng: parseFloat(job.order?.cattle_farm?.longitude) || 74.3587
      }
      
      // Initialize technician current location
      technicianCurrentLocation.value = {
        lat: null,
        lng: null
      }
      
      // Try to get technician's current location
      getTechnicianCurrentLocation()
      
      console.log('Setting showJobModal to true')
      showJobModal.value = true
      console.log('=== JOB MODAL OPENED ===')
    }
    
    const closeJobModal = () => {
      showJobModal.value = false
      selectedJob.value = null
    }
    
    const updateFarmVisitLocation = (location) => {
      farmVisitForm.value.latitude = location.lat
      farmVisitForm.value.longitude = location.lng
    }
    
    const updateTechnicianCurrentLocation = (location) => {
      farmVisitForm.value.latitude = location.lat
      farmVisitForm.value.longitude = location.lng
    }
    
    const getTechnicianCurrentLocation = () => {
      if (!navigator.geolocation) {
        showError('Geolocation is not supported by this browser.')
        return
      }

      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude
          const lng = position.coords.longitude
          
          // Update the technician current location
          technicianCurrentLocation.value = {
            lat: lat,
            lng: lng
          }
          
          // Update the form with current location
          farmVisitForm.value.latitude = lat
          farmVisitForm.value.longitude = lng
          
          // No success notification for automatic location detection
        },
        (error) => {
          console.error('Error getting location:', error)
          let errorMessage = 'Unable to retrieve your location. '
          
          switch(error.code) {
            case error.PERMISSION_DENIED:
              errorMessage += 'Please allow location permissions and try again.'
              break
            case error.POSITION_UNAVAILABLE:
              errorMessage += 'Location information is unavailable.'
              break
            case error.TIMEOUT:
              errorMessage += 'Location request timed out.'
              break
            default:
              errorMessage += 'Please try again or select location manually.'
              break
          }
          
          showError(errorMessage)
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 300000
        }
      )
    }
    
    const handleCurrentLocationObtained = (location) => {
      // Update the technician current location
      technicianCurrentLocation.value = {
        lat: location.lat,
        lng: location.lng
      }
      
      // Update the form with current location
      farmVisitForm.value.latitude = location.lat
      farmVisitForm.value.longitude = location.lng
      
      // No success notification for automatic location detection
    }
    
    const handleCurrentLocationError = (error) => {
      console.error('Error getting location:', error)
      let errorMessage = 'Unable to retrieve your location. '
      
      switch(error.code) {
        case error.PERMISSION_DENIED:
          errorMessage += 'Please allow location permissions and try again.'
          break
        case error.POSITION_UNAVAILABLE:
          errorMessage += 'Location information is unavailable.'
          break
        case error.TIMEOUT:
          errorMessage += 'Location request timed out.'
          break
        default:
          errorMessage += 'Please try again or select location manually.'
          break
      }
      
      showError(errorMessage)
    }
    
    
    const closeETAModal = () => {
      showETAModal.value = false
      selectedJob.value = null
      etaForm.value = { eta: '', notes: '' }
    }

    // Helper function to get current datetime in format for datetime-local input
    const getCurrentDateTime = () => {
      const now = new Date()
      const year = now.getFullYear()
      const month = String(now.getMonth() + 1).padStart(2, '0')
      const day = String(now.getDate()).padStart(2, '0')
      const hours = String(now.getHours()).padStart(2, '0')
      const minutes = String(now.getMinutes()).padStart(2, '0')
      
      return `${year}-${month}-${day}T${hours}:${minutes}`
    }
    
    // Farm visit functions
    const handleCowPhotoUpload = (event, index) => {
      const file = event.target.files[0]
      if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
          showError('Please select a valid image file.')
          return
        }
        
        // Validate file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
          showError('File size must be less than 10MB.')
          return
        }
        
        // Create a preview URL and store the file
        const reader = new FileReader()
        reader.onload = (e) => {
          if (farmVisitForm.value.cow_records && farmVisitForm.value.cow_records[index]) {
            farmVisitForm.value.cow_records[index].cow_photo = {
              file: file,
              preview: e.target.result,
              name: file.name
            }
            console.log('Cow photo uploaded and stored for cow', index + 1, ':', farmVisitForm.value.cow_records[index].cow_photo)
          }
        }
        reader.readAsDataURL(file)
        
        console.log('Cow photo uploaded for cow', index + 1, ':', file.name)
      }
    }
    
    const handleStrawPhotoUpload = (event, index) => {
      const file = event.target.files[0]
      if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
          showError('Please select a valid image file.')
          return
        }
        
        // Validate file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
          showError('File size must be less than 10MB.')
          return
        }
        
        // Create a preview URL and store the file
        const reader = new FileReader()
        reader.onload = (e) => {
          if (farmVisitForm.value.cow_records && farmVisitForm.value.cow_records[index]) {
            farmVisitForm.value.cow_records[index].straw_photo = {
              file: file,
              preview: e.target.result,
              name: file.name
            }
            console.log('Straw photo uploaded and stored for cow', index + 1, ':', farmVisitForm.value.cow_records[index].straw_photo)
          }
        }
        reader.readAsDataURL(file)
        
        console.log('Straw photo uploaded for cow', index + 1, ':', file.name)
      }
    }
    
    const scanBarcode = (index) => {
      // In a real app, this would open camera for barcode scanning
      showInfo('Barcode scanner would open here for cow ' + (index + 1))
    }
    
    // Helper function to get full image URL
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

    const getCowNumber = (record, orderItems) => {
      // Handle undefined record
      if (!record) return 'N/A'
      
      // Find the index of this record's order item
      const itemIndex = orderItems.findIndex(item => item.id === record.order_item_id)
      
      // Return the cow number (1-based index)
      return itemIndex >= 0 ? itemIndex + 1 : (record.id || 'N/A')
    }
    
    // Image modal functions
    const showImageModal = ref(false)
    const selectedImageUrl = ref('')
    const selectedImageTitle = ref('')
    
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
    
    
    const viewJobDetails = async (job) => {
      selectedJobForDetails.value = job
      showJobDetailsModal.value = true
      
      // Refresh job data to get latest visit records
      try {
        const response = await window.axios.get(`/technician/jobs/${job.id}`)
        if (response.data.success && response.data.job) {
          selectedJobForDetails.value = response.data.job
        }
      } catch (error) {
        console.error('Error refreshing job details:', error)
        // Continue with existing job data if refresh fails
      }
      
      // Reverse geocode visit coordinates to get address
      if (job.visit_latitude && job.visit_longitude) {
        await reverseGeocodeVisitLocation(job.visit_latitude, job.visit_longitude)
      } else {
        visitAddress.value = ''
      }
      
      // Reverse geocode farm coordinates to get address
      if (job.order?.cattle_farm?.latitude && job.order?.cattle_farm?.longitude) {
        farmAddress.value = await getFarmAddress(job.order.cattle_farm.latitude, job.order.cattle_farm.longitude)
      } else {
        farmAddress.value = ''
      }
    }

    const goToPregnancyTracking = (jobId = null) => {
      console.log('🔍 goToPregnancyTracking called with:', jobId, typeof jobId)
      if (jobId) {
        console.log('📋 Navigating to pregnancy tracking for job:', jobId)
        // Set the selected job for pregnancy tracking
        selectedJobForPregnancy.value = jobId
        // Switch to pregnancy tracking tab
        activeTab.value = 'pregnancy-tracking'
      } else {
        console.log('📋 Navigating to general pregnancy tracking')
        // Clear selected job and switch to pregnancy tracking tab
        selectedJobForPregnancy.value = null
        activeTab.value = 'pregnancy-tracking'
      }
    }
    
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
        script.src = `https://maps.googleapis.com/maps/api/js?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY}&libraries=places&callback=initGoogleMapsForDashboard`
        script.async = true
        script.defer = true
        script.setAttribute('data-google-maps-loader', 'true')
        
        window.initGoogleMapsForDashboard = () => {
          resolve()
          delete window.initGoogleMapsForDashboard
        }
        
        script.onerror = () => {
          reject(new Error('Failed to load Google Maps API'))
          delete window.initGoogleMapsForDashboard
        }
        
        document.head.appendChild(script)
      })
    }

    const reverseGeocodeVisitLocation = async (lat, lng) => {
      try {
        // Ensure Google Maps API is loaded
        await loadGoogleMapsAPI()
        
        const geocoder = new google.maps.Geocoder()
        const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) }
        
        geocoder.geocode({ location: latlng }, (results, status) => {
          if (status === 'OK' && results && results[0]) {
            visitAddress.value = results[0].formatted_address
          } else {
            visitAddress.value = `Coordinates: ${lat}, ${lng}`
          }
        })
      } catch (error) {
        console.error('Error reverse geocoding:', error)
        visitAddress.value = `Coordinates: ${lat}, ${lng}`
      }
    }
    
    const closeJobDetailsModal = () => {
      showJobDetailsModal.value = false
      selectedJobForDetails.value = null
      visitAddress.value = ''
      farmAddress.value = ''
    }
    
    // Service area management
    // const addServiceArea = () => {
    //   serviceAreas.value.push({ name: '', radius: 10 })
    // }
    
    // const removeServiceArea = (index) => {
    //   serviceAreas.value.splice(index, 1)
    // }
    
    // Inventory management
    const requestStock = (item) => {
      showInfo(`Requesting stock for ${item.bull_name}`)
    }
    
    const getJobStatusBadgeClass = (status) => {
      const classes = {
        pending: 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200',
        accepted: 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
        completed: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        rejected: 'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 border border-purple-200',
        reassigned: 'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 border border-purple-200'
      }
      return classes[status] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }

    const formatJobStatus = (status) => {
      const statusMap = {
        pending: 'Pending',
        accepted: 'Accepted',
        completed: 'Completed',
        rejected: 'Reassigned',
        in_progress: 'In Progress',
        eta_provided: 'ETA Provided',
        failed: 'Failed',
        no_heat: 'No Heat'
      }
      return statusMap[status] || status
    }
    
    
    const getAvailabilityBadgeClass = (availability) => {
      const classes = {
        online: 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
        offline: 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200',
        on_leave: 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200'
      }
      return classes[availability] || 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200'
    }

    // Helper functions for location dropdowns
    const getDivisionsForProvince = (province) => {
      if (!province || !pakistanData[province]) return []
      return pakistanData[province].divisions || []
    }

    const getDistrictsForDivision = (division) => {
      if (!division) return []
      
      // Find the province that contains this division
      for (const province in pakistanData) {
        if (pakistanData[province].divisions.includes(division)) {
          return pakistanData[province].districts[division] || []
        }
      }
      return []
    }

    // Watch for province changes to reset division and district
    watch(() => profileForm.value.province, (newProvince, oldProvince) => {
      if (newProvince !== oldProvince) {
        profileForm.value.division = ''
        profileForm.value.district = ''
      }
    })

    // Watch for division changes to reset district
    watch(() => profileForm.value.division, (newDivision, oldDivision) => {
      if (newDivision !== oldDivision) {
        profileForm.value.district = ''
      }
    })
    
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('en-GB', {
        timeZone: 'Asia/Karachi'
      })
    }
    
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
    
    // Filter methods
    const applyFilters = async () => {
      console.log('Filters applied:', {
        farm: farmFilter.value,
        farmer: farmerFilter.value,
        status: statusFilter.value,
        date: dateFilter.value
      })
      
      // Reset to first page and reload with filters
      await loadJobsWithPagination(1)
    }
    
    const clearFilters = async () => {
      farmFilter.value = ''
      farmerFilter.value = ''
      statusFilter.value = ''
      dateFilter.value = ''
      customDateFrom.value = ''
      customDateTo.value = ''
      
      // Reload data without filters
      await loadJobsWithPagination(1)
    }
    
    const callFarmer = (phoneNumber) => {
      if (phoneNumber) {
        window.open(`tel:${phoneNumber}`, '_self')
      }
    }
    
    const messageFarmer = (farmerId) => {
      // This would typically open a messaging interface
      console.log('Opening message interface for farmer:', farmerId)
      // You could implement a messaging modal or redirect to a messaging page
    }
    
    const logout = async () => {
      if (authStore.isLoggingOut) {
        return // Prevent multiple logout calls
      }
      
      await authStore.logout()
      // Use absolute path to ensure we go to /demo/login
      router.push('/login')
    }
    
    onMounted(async () => {
      // Add click handler for dropdown
      document.addEventListener('click', (event) => {
        if (!event.target.closest('.user-dropdown')) {
          showUserDropdown.value = false
        }
      })

      console.log('Technician Dashboard mounted')
      console.log('Auth store user:', authStore.user)
      console.log('Auth store token:', authStore.token)
      console.log('Is authenticated:', authStore.isAuthenticated)
      
      // Check URL parameters for tab switching
      const urlParams = new URLSearchParams(window.location.search)
      const tabParam = urlParams.get('tab')
      if (tabParam === 'profile') {
        activeTab.value = 'profile'
      }
      
      await loadProfile()
      await loadJobs() // Keep this for overview stats
      await loadJobsWithPagination(1) // Load paginated jobs for the table
      loadEarnings()
      loadInventory()
      await loadJobStats() // This should be called after loadJobs completes
      
      // Check if user should see profile completion alert
      if (localStorage.getItem('showProfileCompletionAlert') === 'true') {
        profileCompletionAlert.value.show = true
      }
    })
    
    return {
      authStore,
      activeTab,
      sidebarOpen,
      handleTabClick,
      showUserDropdown,
      technician,
      jobs,
      jobsPagination,
      reassignmentLogsPagination,
      earnings,
      earningsPeriod,
      jobStatusFilter,
      // Filter variables
      farmFilter,
      farmerFilter,
      statusFilter,
      dateFilter,
      customDateFrom,
      customDateTo,
      profileForm,
      tabs,
      pendingJobsCount,
      completedJobsCount,
      totalEarnings,
      recentJobs,
      averagePerJob,
      // Filter computed properties
      uniqueFarms,
      uniqueFarmers,
      filteredJobs,
      // Pagination computed properties
      totalPages,
      paginatedJobs,
      paginationInfo,
      // New computed properties
      profileCompletion,
      isProfileComplete,
      getStockStatus,
      getStockStatusClass,
      getStatusBadgeClass,
      // New reactive variables
      showJobModal,
      showETAModal,
      showJobDetailsModal,
      showReassignModal,
      selectedJob,
      selectedJobForDetails,
      selectedJobForReassign,
      selectedTechnicianForReassign,
      reassignReason,
      availableTechnicians,
      technicianSearchQuery,
      radiusFilter,
      visitAddress,
      farmAddress,
      showImageModal,
      selectedImageUrl,
      selectedImageTitle,
      jobStats,
      inventory,
      workingHours,
      // serviceAreas,
      farmVisitForm,
      etaForm,
      technicianLocation,
      farmLocation,
      technicianCurrentLocation,
      documents,
      uploading,
      notification,
      profileCompletionAlert,
      // Existing functions
      loadProfile,
      updateTechnicianLocation,
      openInGoogleMaps,
      getFarmAddress,
      loadJobs,
      loadJobsWithPagination,
      loadEarnings,
      loadReassignmentLogs,
      acceptJob,
      rejectJob,
      completeJob,
      confirmAcceptJob,
      confirmStartJob,
      confirmCompleteJob,
      updateProfile,
      updateAvailability,
      saveAvailabilitySettings,
      getJobStatusBadgeClass,
      formatJobStatus,
      getCowStatusBadgeClass,
      getAvailabilityBadgeClass,
      getDivisionsForProvince,
      getDistrictsForDivision,
      pakistanData,
      formatDate,
      formatETADate,
      formatETATime,
      logout,
      // New functions
      loadInventory,
      loadJobStats,
      handleDocumentUpload,
      deleteDocument,
      viewDocument,
      showNotification,
      dismissProfileAlert,
      getCurrentLocation,
      submitForApproval,
      provideETA,
      submitETA,
      startJob,
      openJobModal,
      closeJobModal,
      updateFarmVisitLocation,
      updateTechnicianCurrentLocation,
      getTechnicianCurrentLocation,
      handleCurrentLocationObtained,
      handleCurrentLocationError,
      closeETAModal,
      closeJobDetailsModal,
      handleCowPhotoUpload,
      handleStrawPhotoUpload,
      scanBarcode,
      getImageUrl,
      getCowNumber,
      openImageModal,
      closeImageModal,
      handleCompleteJob,
      submitJobCompletion,
      openReassignModal,
      closeReassignModal,
      searchTechnicians,
      selectTechnician,
      confirmReassignJob,
      reassignJob,
      viewJobDetails,
      goToPregnancyTracking,
      loadGoogleMapsAPI,
      reverseGeocodeVisitLocation,
      // addServiceArea,
      // removeServiceArea,
      requestStock,
      formatCurrency,
      getCurrentDateTime,
      // Pagination methods
      goToPage,
      nextPage,
      prevPage,
      // Filter methods
      applyFilters,
      clearFilters,
      // Notification functions
      showSuccess,
      showError,
      showInfo,
      showWarning,
      // Pregnancy tracking functions
      completedJobs,
      filteredCompletedJobs,
      selectedJobForPregnancy,
      getDaysSinceInsemination,
      getCowStatusText,
      getCowStatusBadgeClass
    }
  },
  components: {
    GoogleMapLocationPicker
  }
}
</script>