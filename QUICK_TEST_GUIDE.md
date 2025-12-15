# 🚀 Quick Test Guide - Workflow Notifications

## ✅ All Systems Ready!

No errors detected in any controllers or notification system. Ready for testing!

---

## 🎯 Quick 5-Minute Test

### Test the Complete Flow (Fastest Way)

**1. Branch Manager Creates Request (1 min)**
```
Login: branchmanager@scms.com / branch123
Go to: Purchase Requests → Create New
Select: Supplier, Products, Quantities
Submit
✓ Check: Central Admin notification bell shows (1)
```

**2. Central Admin Approves (1 min)**
```
Login: centraladmin@scms.com / admin123
Click: Notification bell → Click notification
Action: Approve request
✓ Check: Redirects to request view
✓ Check: New notification appears "Create PO"
```

**3. Central Admin Creates PO (1 min)**
```
Click: "Create PO" notification
Action: Create and send PO
✓ Check: Supplier notification appears
```

**4. Supplier Confirms & Prepares (1 min)**
```
Login: supplier@scms.com / supplier123
Click: Notification → Confirm order
Click: Next notification → Mark as prepared
✓ Check: Logistics notification appears
```

**5. Logistics Schedules & Dispatches (1 min)**
```
Login: logistics@scms.com / logistics123
Click: Notification → Schedule delivery
Action: Update status to "In Transit"
✓ Check: Branch notification appears
```

**6. Branch Receives (30 sec)**
```
Login: branchmanager@scms.com / branch123
Click: Notification → Receive delivery
✓ Check: All stakeholders get completion notification
```

---

## 🔍 What to Verify

### ✅ Notification Appearance
- [ ] Notification bell shows count
- [ ] Unread notifications have blue background
- [ ] Read notifications have gray background
- [ ] Emoji indicators show correctly (🔔 ✅ ❌)

### ✅ Click Behavior
- [ ] Clicking notification marks it as read
- [ ] Redirects to correct page
- [ ] No JavaScript errors in console
- [ ] Notification dropdown closes after click

### ✅ Role Targeting
- [ ] Only relevant users receive notifications
- [ ] Branch-specific notifications work
- [ ] Supplier-specific notifications work
- [ ] Central Admin receives oversight notifications

### ✅ Workflow Continuity
- [ ] Each action triggers next notification
- [ ] No missing notifications in chain
- [ ] Rejection notifications work
- [ ] Completion notifications sent to all

---

## 🐛 Common Issues & Fixes

### Issue: Notifications not appearing
**Fix:** 
1. Open browser console (F12)
2. Check for JavaScript errors
3. Verify notification API endpoint: `/notifications/get-unread`
4. Check database `notifications` table for records

### Issue: Notification count not updating
**Fix:**
1. Refresh page
2. Check Alpine.js is loaded (view page source)
3. Verify `loadNotifications()` function runs every 30 seconds

### Issue: Click not redirecting
**Fix:**
1. Check `link` field in database notification record
2. Verify `handleNotificationClick()` function exists in main.php
3. Check browser console for errors

### Issue: Wrong users receiving notifications
**Fix:**
1. Check user `role` field in database
2. Verify `branch_id` and `supplier_id` associations
3. Check user `status` is 'active'

---

## 📱 Test on Different Roles

### Central Admin Test
```
Login: centraladmin@scms.com / admin123
Expected Notifications:
- New purchase requests
- Approved requests ready for PO
- Delivery completions
- Payment requirements
```

### Branch Manager Test
```
Login: branchmanager@scms.com / branch123
Expected Notifications:
- Request approvals/rejections
- PO status updates
- Delivery schedules
- Transfer requests
```

### Supplier Test
```
Login: supplier@scms.com / supplier123
Expected Notifications:
- New purchase orders
- Confirmation needed
- Preparation needed
- Delivery confirmations
```

### Logistics Test
```
Login: logistics@scms.com / logistics123
Expected Notifications:
- Orders ready for delivery
- Schedule delivery needed
- Transfer approvals
```

### Inventory Staff Test
```
Login: inventory@scms.com / inventory123
Expected Notifications:
- Incoming deliveries
- Delivery arrivals
- Inventory updates
```

---

## 🎨 Notification Message Examples

### Action Required (🔔)
- "🔔 Action Required: Approve Purchase Request"
- "🔔 Action Required: Confirm Purchase Order"
- "🔔 Action Required: Schedule Delivery"
- "🔔 Action Required: Approve Transfer"

### Success (✅)
- "✅ Purchase Request Approved"
- "✅ Purchase Order Confirmed"
- "✅ Delivery Completed"
- "✅ Transfer Completed"

### Rejection (❌)
- "❌ Purchase Request Rejected"
- "❌ Transfer Rejected"

### Info (📦 🚚)
- "📦 Purchase Order Sent to Supplier"
- "🚚 Delivery Scheduled"
- "🚚 Delivery In Transit"
- "📤 Transfer Request Sent"

---

## 📊 Expected Notification Flow

### Purchase Request → Delivery (8 notifications)
1. Central Admin: "Approve Request" (Action)
2. Branch Manager: "Request Approved" (Success)
3. Central Admin: "Create PO" (Action)
4. Supplier: "Confirm Order" (Action)
5. Supplier: "Prepare Order" (Action)
6. Logistics: "Schedule Delivery" (Action)
7. Branch: "Delivery In Transit" (Action)
8. All: "Delivery Completed" (Success)

### Transfer (3-4 notifications)
1. Branch B: "Approve Transfer" (Action)
2. Branch A: "Ready to Ship" (Action)
3. Branch B: "Transfer Completed" (Success)
4. (Optional) Branch A: "Transfer Rejected" (Rejection)

---

## ✅ Success Criteria

Your notification system is working correctly if:

- ✅ All roles receive appropriate notifications
- ✅ Clicking notifications redirects to correct pages
- ✅ Notifications auto-mark as read when clicked
- ✅ No duplicate notifications appear
- ✅ Notification count updates in real-time
- ✅ Workflow completes without missing notifications
- ✅ Rejection flows work correctly
- ✅ No JavaScript errors in console

---

## 🎉 You're All Set!

The workflow notification system is fully implemented and ready to use. Start testing with the 5-minute quick test above, then explore the complete workflows.

**Need Help?**
- Check `WORKFLOW_NOTIFICATIONS_COMPLETE.md` for detailed documentation
- Check `NOTIFICATION_IMPLEMENTATION_GUIDE.md` for implementation details
- Check browser console for errors
- Verify database records in `notifications` table

Happy testing! 🚀
