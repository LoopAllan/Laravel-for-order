[[_TOC_]]

# 使用說明

## 環境建置

```bash
composer install
```


```bash
cp .env.example .env
```

```bash
./vendor/bin/sail up -d; 
```

幾秒後再執行
```bash
./vendor/bin/sail artisan migrate
```

## 執行範例

### 新增訂單

```bash
curl 'http://127.0.0.1:8080/orders' \
-H 'Content-Type:application/json' \
-d '{
    "id": "A0000001",
    "name": "Melody Holiday Inn2",
    "address": {
        "city": "taipei-city",
        "district": "da-an-district",
        "street": "fuxing-south-road"
    },
    "price": "20067",
    "currency": "JPY"
}'
```

### 查詢訂單

```bash
curl 'http://127.0.0.1:8080/orders/A0000001'
```

# 設計模式

- 依賴注入模式：於 Controller、ProcessOrder 等類裡面在需要使用實體的地方使用依賴注入來實現
- 工廠模式：使用 OrderFactory 根據幣別判斷產出對應的模型
- 觀察者模式：當 OrderCreated 事件觸發，ProcessOrder 自動觸發處理訂單建立的邏輯

# SOLID 原則

## SRP

模型
- Model/OrderTWD, Model/OrderUSD 等等的 Models 只針對各自幣別的訂單資料表做操作
- Model/OrderCurrency 只針對 order_currency 訂單編號對應幣別的資料表做操作

控制器
- OrderController 只負責處理訂單的 HTTP 請求，不包含業務邏輯及數據訪問邏輯

請求驗證
- OrderRequest 只針對訂單請求時的數據格式驗證

事件和監聽器
- OrderCreated 訂單成立的事件，僅包含訂單的相關資訊
- ProcessOrder 專門處理由 OrderCreated 事件觸發後的訂單建立業務邏輯

服務類
- CurrencyService 處理幣別相關的操作，如獲取幣別列表、驗證幣別是否支援

工廠類
- OrderFactory 負責產出訂單相關的資料模型

## OCP

服務類
- CurrencyService 讓新增幣別只需修改配置文件，無需更動到相關依賴的類

工廠類
- OrderFactory 內使用到了 CurrencyService，使得幣別發生變化，無需更動到工廠本身

新增幣別如 EUR 只需：
- 創建 OrderEUR.php
- 增加 EUR 到 config/currencies.php

## LSP

模型
- OrderTWD, OrderUSD 因為都繼承自 Illuminate\Database\Eloquent\Model 且沒有行為上的變動故可以互相替換，而不改變操作行為

服務類
- CurrencyService 實作了 CurrencyServiceInterface 介面，可以在任何依賴於 CurrencyServiceInterface 的地方做替換使用

## DIP

OrderRequest, OrderFactory, ProcessOrder 等類別都依賴 CurrencyServiceInterface，而不是特定的 CurrencyService

AppServiceProvider.php 中，將 CurrencyServiceInterface 綁定到了具體的 CurrencyService 實現，客戶端無需關心特定的實作細節。

OrderController 和 ProcessOrder 透過建構函式註入依賴（如 OrderFactory）