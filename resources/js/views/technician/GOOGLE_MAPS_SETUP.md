# Google Maps Integration Setup

This application now includes Google Maps integration for farm location selection in the farmer module.

## Features Added

✅ **Interactive Map Component**: `GoogleMapLocationPicker.vue`
- Click on map to select location
- Drag marker to adjust position
- Search for locations using text input
- Get current location using GPS
- Real-time latitude/longitude extraction

✅ **Add Farm Modal**: Enhanced with Google Maps
- Interactive map for location selection
- Automatic coordinate population
- Better user experience

✅ **Edit Farm Modal**: Enhanced with Google Maps
- Pre-populated with existing farm coordinates
- Interactive map for location updates
- Seamless editing experience

## Setup Instructions

### 1. Get Google Maps API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable the following APIs:
   - **Maps JavaScript API**
   - **Places API**
   - **Geocoding API**
4. Create credentials (API Key)
5. Restrict the API key to your domain for security

### 2. Configure Environment Variables

Add your Google Maps API key to your environment file:

```bash
# In your .env file
VITE_GOOGLE_MAPS_API_KEY=your_actual_api_key_here
```

### 3. API Key Security

For production, make sure to:
- Restrict your API key to specific domains
- Enable only the required APIs
- Set up billing alerts
- Monitor usage regularly

## Usage

### For Farmers

1. **Adding a New Farm**:
   - Click "Add New Farm" button
   - Fill in farm details
   - Use the map to select location by:
     - Clicking on the map
     - Dragging the marker
     - Using "Use Current Location" button
     - Searching for a specific address
   - Latitude and longitude are automatically populated

2. **Editing an Existing Farm**:
   - Click the edit button on any farm
   - The map will show the current farm location
   - Update the location using the same methods as adding
   - Save changes

### Map Features

- **Current Location**: Uses GPS to get user's current location
- **Search**: Type any address to search and select
- **Click to Select**: Click anywhere on the map to set location
- **Drag Marker**: Drag the marker to fine-tune location
- **Address Display**: Shows the selected address below the map
- **Coordinate Display**: Shows exact latitude and longitude

## Technical Details

### Component Structure

```
GoogleMapLocationPicker.vue
├── Map Container (Google Maps)
├── Search Input
├── Action Buttons (Current Location, Search)
├── Coordinate Display (Latitude/Longitude)
└── Address Display
```

### Integration Points

- **Add Farm Modal**: Replaces manual lat/lng input fields
- **Edit Farm Modal**: Pre-populates with existing coordinates
- **Form Validation**: Coordinates are automatically validated
- **Data Binding**: Two-way binding with parent component

### Default Location

- **Default Center**: Lahore, Pakistan (31.5204, 74.3587)
- **Initial Zoom**: 15 (city level)
- **Map Type**: Roadmap

## Troubleshooting

### Common Issues

1. **Map Not Loading**:
   - Check if API key is correctly set in .env
   - Verify API key has required permissions
   - Check browser console for errors

2. **Current Location Not Working**:
   - Ensure HTTPS is enabled (required for geolocation)
   - Check browser permissions for location access
   - Verify GPS is enabled on device

3. **Search Not Working**:
   - Verify Places API is enabled
   - Check API key restrictions
   - Ensure billing is set up for Google Cloud

### Browser Compatibility

- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+

## Cost Considerations

Google Maps API has usage-based pricing:
- Maps JavaScript API: Free tier available
- Places API: Pay per request
- Geocoding API: Pay per request

Monitor usage in Google Cloud Console to avoid unexpected charges.

## Support

For issues related to:
- **Google Maps API**: Check [Google Maps Documentation](https://developers.google.com/maps/documentation)
- **Application Integration**: Check component implementation in `GoogleMapLocationPicker.vue`
- **Form Integration**: Check `Dashboard.vue` for usage examples
