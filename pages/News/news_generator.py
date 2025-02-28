from scraparazzie import scraparazzie
import requests
import sys
import io

# 設置標準輸出編碼為 UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# 定義要爬取的話題
topics = ['Business', 'World', 'Technology', 'Top Stories']
language = 'chinese traditional'
location = 'Taiwan'

# TinyURL API 縮短網址
def shorten_url(long_url):
    try:
        response = requests.get(f'http://tinyurl.com/api-create.php?url={long_url}')
        if response.status_code == 200:
            return response.text
        else:
            return long_url
    except Exception as e:
        print(f"縮短網址時出現錯誤: {e}")
        return long_url

# HTML 模板
html_content = """
<?php
include '../../common/navbar.php';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>即時新聞</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo filemtime('../css/style.css'); ?>">
    <style>
        
        .news-item { padding: 10px; margin-bottom: 20px; border-bottom: 1px solid #ddd; }
        .news-title { font-size: 18px; font-weight: bold; color: #0056b3; text-decoration: none; }
        .news-source { color: #888; font-size: 14px; }
        .news-date { color: #888; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <h1>即時新聞</h1>
"""

# 收集每個話題的新聞資料
for topic in topics:
    html_content += f'<div class="topic-section"><h2>{topic} 類別的新聞</h2>'
    
    # 建立scraparazzie新聞物件
    client = scraparazzie.NewsClient(language=language, location=location, topic=topic, max_results=10)
    items = client.export_news()

    if not items:
        html_content += f'<p>{topic} 類別的新聞無法取得。</p>'
        continue

    # 新增每則新聞的 HTML
    for i, item in enumerate(items):
        shortened_link = shorten_url(item["link"])
        
        html_content += f"""
            <div class="news-item">
                <a href="{shortened_link}" class="news-title" target="_blank">{item["title"]}</a>
                <div class="news-source">新聞機構：{item["source"]}</div>
                <div class="news-date">發佈時間：{item["publish_date"]}</div>
            </div>
        """
    
    html_content += '</div>'

# 結束 HTML 文件
html_content += """
</body>
</html>
"""

# 將 HTML 寫入文件
with open(R"/home2/hgpqtbmy/public_html/website_b40a0606/pages/News/news.php", "w", encoding="utf-8") as file:
    file.write(html_content)