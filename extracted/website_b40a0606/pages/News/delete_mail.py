import imaplib
import email
from email.header import decode_header, make_header
import base64
import sys
import io

# 設置標準輸出編碼為 UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# 登錄 Gmail 的 IMAP 伺服器
def login_to_gmail(username, password):
    mail = imaplib.IMAP4_SSL("imap.gmail.com")
    mail.login(username, password)
    return mail

# 將標題進行編碼以支持非 ASCII 字符
def decode_str(s):
    if s is None:
        return None
    try:
        subject, encoding = decode_header(s)[0]
        if isinstance(subject, bytes):
            return subject.decode(encoding if encoding else 'utf-8', errors='ignore')
        return subject
    except Exception as e:
        print(f"解碼標題時出錯: {e}")
        return None

# 搜尋並刪除寄件備份中的郵件
def delete_sent_emails_with_subject(mail, subject):
    # 選擇已發送郵件夾
    status, data = mail.select('"[Gmail]/&W8RO9lCZTv0-"')  # 發送郵件夾
    
    # 確認選擇成功
    if status != "OK":
        print("無法選擇已發送郵件夾。")
        return

    # 將中文標題進行 MIME 編碼
    encoded_subject = decode_str(subject)

    # 搜尋標題為編碼後「每日新聞報告」的郵件
    status, messages = mail.search(None, f'(HEADER Subject "{encoded_subject}")')

    if status != "OK":
        print("未能找到郵件。")
        return

    # 列出所有郵件 ID
    email_ids = messages[0].split()

    if not email_ids:
        print("找不到符合條件的郵件。")
        return

    for email_id in email_ids:
        # 刪除郵件
        mail.store(email_id, '+FLAGS', '\\Deleted')

    # 確認刪除
    mail.expunge()
    print(f"已刪除 {len(email_ids)} 封郵件。")

def main():
    username = 'jacky09299@gmail.com'
    password = 'dmyj znrb xncv uywn'  # 請使用應用專用密碼替換此處

    # 登錄 Gmail
    mail = login_to_gmail(username, password)
    
    # 刪除標題為「每日新聞報告」的已發送郵件
    delete_sent_emails_with_subject(mail, "Everyday News")

    # 退出郵件伺服器
    mail.logout()

if __name__ == "__main__":
    main()
