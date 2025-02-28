#!/usr/bin/python3

import cgitb
cgitb.enable()

import sys
import os

# 設定Flask應用程式的路徑
sys.path.insert(0, "/home2/hgpqtbmy/public_html/website_b40a0606/cgi-bin/flaskapp")

from my_flask_app import app as application  # 假設你的 Flask 應用程式名為 `my_flask_app.py`，且實例化的 Flask app 物件名稱為 `app`

# 配置CGI環境變量
def application(environ, start_response):
    # 使用WSGI的方式來執行你的 Flask 應用
    from werkzeug.middleware.dispatcher import DispatcherMiddleware
    return DispatcherMiddleware(application)(environ, start_response)
import cgitb
cgitb.enable()