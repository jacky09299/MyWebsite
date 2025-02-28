document.getElementById("translateButton").addEventListener("click", async () => {
  const inputLanguage = document.getElementById("inputLanguage").value;
  const outputLanguage = document.getElementById("outputLanguage").value;
  const inputText = document.getElementById("inputText").value;

  if (!inputText) {
    alert("請輸入需要翻譯的文字！");
    return;
  }

  try {
    // 使用 LibreTranslate API 作為示例
    const response = await fetch("https://libretranslate.de/translate", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        q: inputText,
        source: inputLanguage,
        target: outputLanguage,
        format: "text"
      })
    });

    if (!response.ok) {
      throw new Error("翻譯失敗，請稍後再試！");
    }

    const data = await response.json();
    document.getElementById("outputText").value = data.translatedText;
  } catch (error) {
    console.error("翻譯錯誤：", error);
    alert("翻譯過程中發生錯誤，請檢查網絡連線或稍後再試。");
  }
});