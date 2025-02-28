<html>
<head>
    <title>Google Translation Custom Example</title>
    <style>
        body > .skiptranslate {
            display: none;
        }
        .goog-logo-link {
            display: none !important;
        }
        .goog-te-gadget {
            color: transparent !important;
        }
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }
        a[href="https://translate.google.com"] {
            display: none !important;
        }
    </style>
</head>
<body>
    <div>
        <div id="google_translate_element" style="display: none;"></div>
        <div>
            <label for="sourceLang">選擇輸入語言：</label>
            <select id="sourceLang">
                <option value="zh-TW">繁體中文</option>
                <option value="zh-CN">简体中文</option>
                <option value="en">English</option>
                <option value="ja">日本語</option>
                <option value="ko">한국어</option>
            </select>
        </div>
        <div>
            <label for="targetLang">選擇翻譯語言：</label>
            <select id="targetLang">
                <option value="zh-TW">繁體中文</option>
                <option value="zh-CN">简体中文</option>
                <option value="en">English</option>
                <option value="ja">日本語</option>
                <option value="ko">한국어</option>
            </select>
        </div>
        <div>
            <label for="textInput">輸入要翻譯的文字：</label>
            <textarea id="textInput" rows="4" cols="50"></textarea>
        </div>
        <button onclick="translateText()">翻譯</button>
        <div>
            <h3>翻譯結果：</h3>
            <div id="translationResult"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'zh-TW',
                autoDisplay: false,
                includedLanguages: 'zh-TW,zh-CN,en,ja,ko',
                layout: google.translate.TranslateElement.InlineLayout.VERTICAL
            }, 'google_translate_element');
        }

        function translateText() {
            const sourceLang = document.getElementById('sourceLang').value;
            const targetLang = document.getElementById('targetLang').value;
            const textInput = document.getElementById('textInput').value;

            if (!textInput) {
                alert("請輸入要翻譯的文字！");
                return;
            }

            const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${sourceLang}&tl=${targetLang}&dt=t&q=${encodeURIComponent(textInput)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('translationResult').innerText = data[0][0][0];
                })
                .catch(err => {
                    console.error("翻譯失敗：", err);
                    document.getElementById('translationResult').innerText = "翻譯失敗，請稍後再試。";
                });
        }
    </script>
</body>
</html>
