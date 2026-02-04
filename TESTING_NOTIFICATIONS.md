# خطوات اختبار الإشعارات 🔔

## ✅ الإعداد الأساسي (اعملها مرة واحدة)

### 1. أضف السكريبتات في صفحات Dashboard

أضف هذا الكود قبل `</body>` في الصفحات التالية:
- `resources/views/dashboard/index.blade.php`
- `resources/views/dashboard/orders/index.blade.php`
- `resources/views/dashboard/designs/index.blade.php`

```html
<!-- Firebase Push Notifications -->
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>
<script src="{{ asset('js/firebase-init.js') }}"></script>
```

### 2. شغل Queue Worker

**في terminal جديد:**
```bash
php artisan queue:work
```

**⚠️ مهم:** خلي هذا الـ terminal يشتغل طول الوقت!

---

## 🧪 اختبار الإشعارات

### **اختبار 1: Design Created** (إشعار للأدمن)

1. افتح المتصفح واذهب للـ Dashboard كـ Admin
2. اسمح بالإشعارات عندما يطلب المتصفح
3. افتح Console في المتصفح (F12)
4. شوف رسالة: `FCM Token saved successfully`

5. **سجل دخول كمستخدم عادي** (في نافذة أخرى/Incognito)
6. أنشئ تصميم جديد من صفحة Designs
7. **شوف الـ Admin يستلم إشعار!** 🔔

**شو لازم يصير:**
- ✅ Admin يشوف إشعار Push في المتصفح
- ✅ Admin يلاقي الإشعار في قاعدة البيانات

**كيف تتأكد من قاعدة البيانات:**
```sql
SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5;
```

---

### **اختبار 2: Order Created** (إشعار للأدمن والمستخدم)

1. سجل دخول كمستخدم
2. اسمح بالإشعارات
3. أنشئ طلب جديد (Order)

**شو لازم يصير:**
- ✅ المستخدم يستلم إشعار Push: "Order created successfully"
- ✅ Admin يستلم إشعار Push: "New order created"
- ✅ لو التصميم لمستخدم ثاني، هذاك المستخدم بيستلم إشعار كمان
- ✅ الكل بيلاقوا الإشعارات في قاعدة البيانات

---

### **اختبار 3: Order Status Changed** (إشعار للمستخدم - DB فقط)

1. سجل دخول كـ Admin
2. افتح Order موجود
3. غير الـ Status (مثلاً من pending → confirmed)

**شو لازم يصير:**
- ✅ المستخدم صاحب الـ Order يلاقي إشعار في قاعدة البيانات
- ❌ ما في Push notification (DB فقط حسب المطلوب)

---

## 🔍 Debugging - لو ما اشتغل

### 1. تحقق من Queue Worker
```bash
# شوف اللوغ
php artisan queue:work --verbose
```

إذا شفت أخطاء، شوف:
```bash
tail -f storage/logs/laravel.log
```

### 2. تحقق من FCM Token

**في Console المتصفح:**
```javascript
// شوف Token موجود
console.log('Token saved in localStorage:', localStorage.getItem('fcm_token'));
```

**في قاعدة البيانات:**
```sql
SELECT id, name, fcm_token FROM users WHERE fcm_token IS NOT NULL;
```

### 3. اختبار يدوي (Postman/Thunder Client)

**تحديث FCM Token:**
```http
POST http://localhost:8000/api/update-fcm-token
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "fcm_token": "test_token_123"
}
```

**إرسال إشعار تجريبي (Admin فقط):**
```http
POST http://localhost:8000/api/admin/test-notification
Authorization: Bearer ADMIN_TOKEN
```

**جلب الإشعارات:**
```http
GET http://localhost:8000/api/notifications
Authorization: Bearer YOUR_TOKEN
```

---

## 📋 Checklist سريع

قبل ما تبلش الاختبار:

- [ ] ملف `firebase_credentials.json` موجود في `storage/app/firebase/`
- [ ] VAPID key محدث في `firebase-init.js`
- [ ] السكريبتات مضافة في صفحات Dashboard
- [ ] Queue worker شغال (`php artisan queue:work`)
- [ ] قاعدة البيانات شغالة
- [ ] جدول `notifications` موجود
- [ ] عمود `fcm_token` موجود في جدول `users`

---

## 🎯 مثال واقعي كامل

```bash
# Terminal 1: شغل Laravel
php artisan serve

# Terminal 2: شغل Queue Worker
php artisan queue:work

# Terminal 3: راقب اللوغات
tail -f storage/logs/laravel.log
```

**في المتصفح:**
1. افتح `http://localhost:8000/dashboard` كـ Admin
2. اسمح بالإشعارات
3. افتح نافذة Incognito وسجل دخول كـ User
4. أنشئ تصميم جديد
5. شوف الإشعار يظهر عند الـ Admin! 🎉

---

## ⚡ نصائح مهمة

1. **HTTPS مطلوب في Production** - لكن localhost يشتغل عادي
2. **Service Worker** لازم يكون في `public/` مش في `resources/`
3. **Queue Worker** لازم يكون شغال دائماً
4. المتصفحات المدعومة: Chrome, Firefox, Edge (Safari ما بدعم)
5. لو عملت تغيير على Service Worker، اعمل Hard Refresh (Ctrl+Shift+R)

---

هل تريد مساعدة في إضافة السكريبتات للصفحات؟
