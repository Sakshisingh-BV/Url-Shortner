// --- URL SHORTENER LOGIC (IMPROVED) ---
const shortenButton = document.querySelector('.shorten-button');
const urlInput = document.getElementById('long-url');
const resultDiv = document.getElementById('shorten-result');
// Flask server URL
const API_BASE_URL = 'http://127.0.0.1:5000';
if (shortenButton && urlInput && resultDiv) {
  shortenButton.addEventListener('click', async function () {
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    if (!isLoggedIn) {
      alert("🔒 Please login to continue");
      return;
    }
    let longUrl = urlInput.value.trim();
    if (!longUrl) {
      alert("Please enter a URL to shorten.");
      return;
    }
    // Prepend protocol if missing
    if (!/^https?:\/\//i.test(longUrl)) {
      longUrl = "https://" + longUrl;
    }
    try {
      const response = await fetch(`${API_BASE_URL}/shorten`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ url: longUrl })
      });
      const text = await response.text();
      let data;
      
      try {
        data = JSON.parse(text);
      } catch (err) {
        alert("❌ Server error - please try again");
        return;
      }
    if (response.ok && data.short_url) {
  // Success alert
  alert("✅ URL shortened successfully!");
  
  // Show shortened URL in the input field
  urlInput.value = data.short_url;
  urlInput.style.paddingRight = "35px";
  
  // Create wrapper div for input with copy icon
  const wrapper = document.createElement('div');
  wrapper.style.position = "relative";
  wrapper.style.display = "inline-block";
  wrapper.style.width = "100%";
  
  // Move input inside wrapper
  urlInput.parentNode.insertBefore(wrapper, urlInput);
  wrapper.appendChild(urlInput);
  
  // Add copy icon to wrapper
  const copyIcon = document.createElement('i');
  copyIcon.className = 'ri-file-copy-line';
  copyIcon.onclick = () => copyToClipboard(data.short_url);
  copyIcon.style.cssText = `
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    font-size: 18px; color: #00d4aa; cursor: pointer;
  `;
  copyIcon.title = "Copy URL";
  wrapper.appendChild(copyIcon);
  
  resultDiv.innerHTML = "";
} else {
  alert(`❌ Error: ${data.error || "Something went wrong"}`);
}
    } catch (error) {
      alert("❌ Please check if server is running");
    }
  });
}

// Copy to clipboard function (silent - no alerts)
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(function() {
    alert("✅ Copied to clipboard!");
  }, function(err) {
    // Fallback for older browsers - also silent
    const textArea = document.createElement("textarea");
    textArea.value = text;  
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
      document.execCommand('copy');
    } catch (err) {
      // Silent fail
    }
    document.body.removeChild(textArea);
  });
}

// Clear button functionality
const clearButton = document.querySelector('.clear-button');
if (clearButton && urlInput && resultDiv) {
  clearButton.addEventListener('click', function() {
    // Check if input is already empty
    if (!urlInput.value.trim()) {
      alert("⚠️ Please enter URL!");
      return;
    }
    
    // Check if URL has been shortened (copy icon exists)
    const copyIcon = document.querySelector('.ri-file-copy-line');
    if (copyIcon) {
      // Clear input field
      urlInput.value = '';
      urlInput.placeholder = 'Enter URL to shorten';
      urlInput.style.paddingRight = '';
      
      // Remove copy icon and wrapper
      const wrapper = urlInput.parentElement;
      if (wrapper.tagName === 'DIV' && wrapper.style.position === 'relative') {
        const parent = wrapper.parentElement;
        parent.insertBefore(urlInput, wrapper);
        parent.removeChild(wrapper);
      }
      
      // Clear result div
      resultDiv.innerHTML = '';
      
      // Success alert
      alert("🚀 Ready for next dive into URL!");
    } else {
      // URL not shortened yet, just clear
      urlInput.value = '';
      
    }
  });
}

// Toggle eye password functionality - FIXED VERSION
document.addEventListener("DOMContentLoaded", function () {
  const toggleEyes = document.querySelectorAll(".toggle-eye");

  toggleEyes.forEach((eye) => {
    eye.addEventListener("click", () => {
      // Find the password input in the same input__row
      const inputRow = eye.closest('.input__row');
      const passwordInput = inputRow.querySelector('.password-input');
      
      if (!passwordInput) return;

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eye.innerHTML = '<i class="ri-eye-line"></i>';
      } else {
        passwordInput.type = "password";
        eye.innerHTML = '<i class="ri-eye-off-line"></i>';
      }
    });
  });
});

