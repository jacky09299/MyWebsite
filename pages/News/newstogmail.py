from scraparazzie import scraparazzie
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import requests
import sys
import io

# 設置標準輸出編碼為 UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')


# 設定 SMTP 伺服器和登錄憑證
SMTP_SERVER = 'smtp.gmail.com'
SMTP_PORT = 587
EMAIL = 'jacky09299@gmail.com'  # 你的 Gmail 帳號
PASSWORD = 'dmyj znrb xncv uywn'  # 你的 Gmail 密碼

# 設定多個收件者 Gmail
RECIPIENT_EMAILS = ['jacky09299@gmail.com', 'fannytnig1969@gmail.com', '20160103sunday@gmail.com']


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

# 收集每個話題的新聞資料
all_news_content = ""  # 用於 Gmail 的所有主題新聞內容

for topic in topics:
    news_content = f'爬取 {topic} 類別的新聞：\n\n'
    
    # 建立scraparazzie新聞物件
    client = scraparazzie.NewsClient(language=language, location=location, topic=topic, max_results=10)
    items = client.export_news()

    if not items:  # 檢查是否有成功取得新聞資料
        print(f"{topic} 類別的新聞無法取得。")
        continue

    # 打印每則新聞的詳細資料
    for i, item in enumerate(items):
        shortened_link = shorten_url(item["link"])
        
        news_content += f'第 {i+1} 則新聞\n'
        news_content += f'新聞標題：{item["title"]}\n'
        news_content += f'新聞機構：{item["source"]}\n'
        news_content += f'新聞連結：{shortened_link}\n'
        news_content += f'發佈時間：{item["publish_date"]}\n'
        news_content += '=====================================================================\n'
    
    all_news_content += news_content + '\n'
    
# 檢查 all_news_content 的內容
print("檢查所有新聞內容:\n", all_news_content)

# 發送所有主題的新聞總結到 Gmail
message = MIMEMultipart()
message['From'] = EMAIL
message['To'] = ", ".join(RECIPIENT_EMAILS)
message['Subject'] = 'Everyday News'
message.attach(MIMEText(all_news_content, 'plain'))

# 發送郵件
try:
    server = smtplib.SMTP(SMTP_SERVER, SMTP_PORT)
    server.starttls()
    server.login(EMAIL, PASSWORD)
    server.sendmail(EMAIL, RECIPIENT_EMAILS, message.as_string())
    print("郵件已成功發送給所有收件者")
except Exception as e:
    print(f"發送郵件時出現錯誤: {e}")
finally:
    server.quit()
