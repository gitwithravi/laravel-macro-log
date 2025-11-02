# PWA Icons Directory

This directory should contain all the app icons for your Progressive Web App.

## Required Icons

Create the following icon files in this directory:

### Standard Icons
- `icon-72x72.png` - 72x72 pixels
- `icon-96x96.png` - 96x96 pixels
- `icon-128x128.png` - 128x128 pixels
- `icon-144x144.png` - 144x144 pixels
- `icon-152x152.png` - 152x152 pixels
- `icon-192x192.png` - 192x192 pixels
- `icon-384x384.png` - 384x384 pixels
- `icon-512x512.png` - 512x512 pixels

### Special Icons
- `apple-touch-icon.png` - 180x180 pixels (for iOS)
- `maskable-icon-512x512.png` - 512x512 pixels (Android adaptive icon)

## How to Create Icons

### Option 1: Online Generator (Recommended)
1. Create a 512x512 base icon for your app
2. Use one of these tools:
   - https://realfavicongenerator.net
   - https://www.pwabuilder.com/imageGenerator
   - https://favicon.io/
3. Upload your base icon
4. Download generated icons
5. Place all files in this directory

### Option 2: Manual Creation
Use design tools like:
- Figma (free, web-based)
- Photoshop
- GIMP (free)
- Inkscape (free, vector)

### Maskable Icon
For the maskable icon:
1. Visit https://maskable.app
2. Upload your 512x512 icon
3. Ensure your logo/text is within the safe zone (center 80%)
4. Export as `maskable-icon-512x512.png`

## Design Tips

- Use a simple, recognizable design
- Ensure the icon looks good at small sizes (72x72)
- Use high contrast colors
- Avoid thin lines that disappear at small sizes
- Test on both light and dark backgrounds
- For maskable icon: keep important elements centered

## Testing

After adding icons:
1. Rebuild the app: `npm run build`
2. Test the manifest: Open DevTools > Application > Manifest
3. Verify all icons load correctly
4. Test install on mobile devices

## Icon Theme

Recommended colors based on your app theme:
- Primary: #4F46E5 (Indigo 600)
- Background: #FFFFFF (White)
- Accent: #9333EA (Purple 600)
