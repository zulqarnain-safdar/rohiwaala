<template>
  <div class="relative">
    <!-- Hidden form to confuse browser autocomplete -->
    <form style="position: absolute; left: -9999px; opacity: 0;" autocomplete="off">
      <input
        type="text"
        :name="`fake_${randomName}`"
        autocomplete="off"
        tabindex="-1"
      />
    </form>
    
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <div class="flex items-center space-x-2">
        <div class="w-6 h-4 rounded-sm border border-gray-200 shadow-sm bg-green-600 flex items-center justify-center">
          <img 
            :src="selectedCountry.flag" 
            :alt="selectedCountry.name"
            class="w-full h-full rounded-sm object-cover"
            @error="handleImageError"
          />
        </div>
        <span class="text-sm text-gray-600 font-semibold">{{ selectedCountry.code }}</span>
      </div>
    </div>
    <input
      :id="uniqueId"
      :value="formattedValue"
      @input="validateInput"
      @keydown="$emit('keydown', $event)"
      @keypress="validateKeyPress"
      :name="randomName"
      :type="inputType"
      :required="required"
      :class="inputClass"
      :placeholder="placeholder"
      pattern="[0-9]{10}"
      minlength="10"
      maxlength="10"
      autocomplete="new-password"
      autocapitalize="off"
      autocorrect="off"
      spellcheck="false"
    />
  </div>
</template>

<script>
import { ref, computed, watch, nextTick, onMounted } from 'vue'

export default {
  name: 'FlagSelector',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    inputId: {
      type: String,
      default: 'mobile'
    },
    inputName: {
      type: String,
      default: 'mobile'
    },
    inputType: {
      type: String,
      default: 'text'
    },
    required: {
      type: Boolean,
      default: false
    },
    inputClass: {
      type: String,
      default: 'block w-full pl-20 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200'
    },
    placeholder: {
      type: String,
      default: '3001234567'
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const countries = [
      {
        name: 'Pakistan',
        code: '+92',
        flag: 'https://flagcdn.com/w40/pk.png'
      }
    ]

    const selectedCountry = ref(countries[0]) // Default to Pakistan
    const randomName = ref(`mobile_${Math.random().toString(36).substr(2, 9)}`)
    const uniqueId = ref(`mobile_${Math.random().toString(36).substr(2, 9)}`)
    const inputType = ref('password') // Start with password type to prevent autocomplete
    
    // Debug logging
    console.log('FlagSelector mounted with modelValue:', props.modelValue)

    const formattedValue = computed(() => {
      if (!props.modelValue) return ''
      
      // Remove +92 prefix for display
      if (props.modelValue.startsWith('+92')) {
        return props.modelValue.substring(3)
      }
      
      return props.modelValue
    })

    const handleInput = (event) => {
      let value = event.target.value
      
      // Remove all non-numeric characters
      value = value.replace(/\D/g, '')
      
      // Limit to 10 digits for Pakistani mobile numbers
      value = value.slice(0, 10)
      
      // Add +92 prefix
      const fullNumber = value ? `+92${value}` : ''
      
      emit('update:modelValue', fullNumber)
    }

    const validateInput = (event) => {
      let value = event.target.value
      
      // Only allow digits
      value = value.replace(/\D/g, '')
      
      // Limit to 10 digits
      value = value.slice(0, 10)
      
      // Update the input value
      event.target.value = value
      
      // Add +92 prefix
      const fullNumber = value ? `+92${value}` : ''
      emit('update:modelValue', fullNumber)
    }

    const validateKeyPress = (event) => {
      // Only allow digits (0-9)
      const char = String.fromCharCode(event.which)
      if (!/[0-9]/.test(char)) {
        event.preventDefault()
      }
    }

    const handleImageError = (event) => {
      // Fallback to a simple flag representation if image fails to load
      event.target.style.display = 'none'
      console.warn('Flag image failed to load, using fallback')
    }

    // Watch for modelValue changes to ensure input is cleared when parent resets
    watch(() => props.modelValue, (newValue) => {
      console.log('FlagSelector modelValue changed to:', newValue)
      if (!newValue) {
        // If modelValue is empty, ensure the input is also empty
        nextTick(() => {
          const input = document.getElementById(uniqueId.value)
          if (input) {
            console.log('Clearing input field')
            input.value = ''
            input.setAttribute('value', '')
            // Trigger input event to ensure Vue updates
            input.dispatchEvent(new Event('input', { bubbles: true }))
          }
        })
      }
    }, { immediate: true })

    // Force clear input on component mount
    onMounted(() => {
      console.log('FlagSelector mounted, forcing input clear')
      nextTick(() => {
        const input = document.getElementById(uniqueId.value)
        if (input) {
          // Multiple clearing attempts to override browser cache
          input.value = ''
          input.setAttribute('value', '')
          input.removeAttribute('value')
          input.defaultValue = ''
          
          // Force focus and blur to trigger browser clearing
          input.focus()
          input.blur()
          
          // Additional clearing after a short delay
          setTimeout(() => {
            input.value = ''
            input.setAttribute('value', '')
            // Switch to text type after clearing
            inputType.value = props.inputType
            console.log('Input cleared on mount with delay')
          }, 100)
          
          console.log('Input cleared on mount')
        }
      })
    })

    return {
      selectedCountry,
      formattedValue,
      randomName,
      uniqueId,
      inputType,
      handleInput,
      validateInput,
      validateKeyPress,
      handleImageError
    }
  }
}
</script>
