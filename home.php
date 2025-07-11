<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>URL Shortener</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
  </head>
  <body>
<div class="background-image"></div>
     <header style="display: flex; justify-content: space-between; align-items: center; padding: 10px 30px;">
  <!-- 🔽 Left: Pin Icon + Logo -->
  <div style="display: flex; align-items: center; gap: 10px;">
    <i class="ri-pushpin-fill" style="color: white; font-size: 24px;"></i>
    <h1 style="color: white; font-size: 24px; margin: 0;">ShrinkIt</h1>
  </div>

  <!-- 🔽 Right: User icon + Logout -->
  <div style="display: flex; align-items: center; gap: 12px;">
    <i class="ri-user-line" style="color: white; font-size: 22px;"></i>
    <a href="Login.php" style="color: white; text-decoration: none; background-color: #444; padding: 6px 12px; border-radius: 6px;"onmouseover="this.style.border='2px solid white'"
   onmouseout="this.style.border='2px solid transparent'">Logout</a>
  </div>
</header>


    <div id="home" class="page active">
        <main>
            <div class="shorten-bar">
                <input
                    type="text"
                    id="long-url"
                    class="url-input"
                    placeholder="Enter your URL here..."
                    autocomplete="off"
                />
                <div class="button-container">
                    <button class="shorten-button">Shrink It</button>
                    <button class="shorten-button clear-button">Clear</button>
                </div>
            </div>
            <div id="shorten-result"></div>
        </main>
    </div>

    <footer>
        <p>&copy; 2025 URL Shortener. All rights reserved.</p>
    </footer>
    <script src="main.js"></script>
  </body>
</html>
