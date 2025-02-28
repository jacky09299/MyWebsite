// snowflake.js
function createWeatherEffect() {
  const snowflakeContainer = document.querySelector('.snowflake-container');
  const weatherEffect = document.createElement("div");

  // Get current time to decide whether to show stars, clouds, or sun
  const currentHour = new Date().getHours();
  let emoji = "☀️"; // Default to sun emoji
  let effectClass = "sun"; // Default to sun effect

  // Assign weather based on time
  if ((currentHour >= 19 && currentHour < 24) || (currentHour >= 0 && currentHour < 4)) {
    emoji = "⭐";  // Star emoji from 00:00 to 12:00
    effectClass = "star";
  } else if (currentHour >= 13&& currentHour < 19) {
    emoji = "☁️";  // Cloud emoji from 12:00 to 18:00
    effectClass = "cloud";
  } else if (currentHour >= 4 && currentHour < 13) {
    emoji = "☀️";  // Sun emoji from 18:00 to 24:00
    effectClass = "sun";
  }

  weatherEffect.innerText = emoji;
  weatherEffect.classList.add("weather-effect", effectClass);

  // Set positions based on weather type
  if (effectClass === "star") {
    // 星星顯示在最頂部
    weatherEffect.style.top = Math.random() * (window.innerHeight * 0.5) + "px";
    weatherEffect.style.left = window.innerWidth * 0.3 + Math.random() * window.innerWidth + "px";
  } else if (effectClass === "cloud") {
    // 雲朵顯示在最右邊
    weatherEffect.style.top = Math.random() * window.innerHeight + "px";
    weatherEffect.style.left = window.innerWidth * 0.9 + Math.random() * (window.innerWidth * 0.1) + "px";
  } else if (effectClass === "sun") {
    // 太陽顯示在右下角
    weatherEffect.style.top = window.innerHeight * 0.7+ Math.random() * (window.innerHeight * 0.3) + "px";
    weatherEffect.style.left = window.innerWidth * 0.9 + Math.random() * (window.innerWidth * 0.1) + "px";
  }

  // Random size
  weatherEffect.style.fontSize = (1 + Math.random() * 2) + "rem";
  weatherEffect.style.animationDuration = (5 + Math.random() * 10) + "s";

  // Add the weather effect to the container
  snowflakeContainer.appendChild(weatherEffect);

  // Remove the element when animation ends
  weatherEffect.addEventListener("animationend", () => {
    weatherEffect.remove();
  });
}

// Generate a new weather effect every 500ms
setInterval(createWeatherEffect, 400);