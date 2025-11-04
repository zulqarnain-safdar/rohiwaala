const fs = require('fs');
const path = require('path');

// Function to recursively find all .vue files
function findVueFiles(dir) {
  let results = [];
  const list = fs.readdirSync(dir);
  
  list.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    
    if (stat && stat.isDirectory()) {
      results = results.concat(findVueFiles(filePath));
    } else if (file.endsWith('.vue')) {
      results.push(filePath);
    }
  });
  
  return results;
}

// Function to replace alert calls with appropriate SweetAlert2 functions
function replaceAlerts(filePath) {
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Replace success messages
  const successPatterns = [
    /showInfo\('([^']*successfully[^']*)'\)/g,
    /showInfo\("([^"]*successfully[^"]*)"\)/g,
    /showInfo\(`([^`]*successfully[^`]*)`\)/g
  ];
  
  successPatterns.forEach(pattern => {
    if (pattern.test(content)) {
      content = content.replace(pattern, "showSuccess('$1')");
      modified = true;
    }
  });
  
  // Replace error messages
  const errorPatterns = [
    /showInfo\('([^']*failed[^']*)'\)/g,
    /showInfo\('([^']*error[^']*)'\)/g,
    /showInfo\('([^']*Failed[^']*)'\)/g,
    /showInfo\("([^"]*failed[^"]*)"\)/g,
    /showInfo\("([^"]*error[^"]*)"\)/g,
    /showInfo\("([^"]*Failed[^"]*)"\)/g,
    /showInfo\(`([^`]*failed[^`]*)`\)/g,
    /showInfo\(`([^`]*error[^`]*)`\)/g,
    /showInfo\(`([^`]*Failed[^`]*)`\)/g
  ];
  
  errorPatterns.forEach(pattern => {
    if (pattern.test(content)) {
      content = content.replace(pattern, "showError('$1')");
      modified = true;
    }
  });
  
  // Replace warning messages
  const warningPatterns = [
    /showInfo\('([^']*warning[^']*)'\)/g,
    /showInfo\('([^']*Warning[^']*)'\)/g,
    /showInfo\('([^']*Please[^']*)'\)/g,
    /showInfo\("([^"]*warning[^"]*)"\)/g,
    /showInfo\("([^"]*Warning[^"]*)"\)/g,
    /showInfo\("([^"]*Please[^"]*)"\)/g,
    /showInfo\(`([^`]*warning[^`]*)`\)/g,
    /showInfo\(`([^`]*Warning[^`]*)`\)/g,
    /showInfo\(`([^`]*Please[^`]*)`\)/g
  ];
  
  warningPatterns.forEach(pattern => {
    if (pattern.test(content)) {
      content = content.replace(pattern, "showWarning('$1')");
      modified = true;
    }
  });
  
  if (modified) {
    fs.writeFileSync(filePath, content);
    console.log(`Updated: ${filePath}`);
  }
}

// Main execution
const vueFiles = findVueFiles('./resources/js');
console.log(`Found ${vueFiles.length} Vue files`);

vueFiles.forEach(file => {
  replaceAlerts(file);
});

console.log('Alert replacement completed!');
