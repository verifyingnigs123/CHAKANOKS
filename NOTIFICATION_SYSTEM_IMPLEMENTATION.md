# Notification System Implementation

## Overview
The notification system has been enhanced to automatically notify users when there are items that need approval or receiving. Notifications appear in the notification bell icon in the top navigation bar.

## What Triggers Notifications

### 1. **Purchase Requests - Approval Needed**
- **When:** A new purchase request is created with status "pending"
- **Who Gets Notified:** 
  - System Administrators (`system_admin`)
  - Central Administrators (`central_admin`)
- **Notification Message:** "New purchase request [REQUEST_NUMBER] from [BRANCH_NAME] requires approval"
- **Link:** Direct link to view the purchase request

### 2. **Deliveries - Receiving Needed**
- **When:** 
  - A delivery is scheduled
  - A delivery status is updated to "in_transit"
  - A delivery status is updated to "delivered"
- **Who Gets Notified:**
  - Branch Managers at the destination branch
  - Inventory Staff at the destination branch
- **Notification Messages:**
  - Scheduled: "Delivery [DELIVERY_NUMBER] has been scheduled for [BRANCH_NAME]. Please prepare to receive."
  - In Transit: "Delivery [DELIVERY_NUMBER] is in transit to [BRANCH_NAME]. Please prepare to receive."
  - Delivered: "Delivery [DELIVERY_NUMBER] has arrived at [BRANCH_NAME] and needs to be received."
- **Link:** Direct link to view/receive the delivery

### 3. **Transfers - Approval Needed**
- **When:** A new inter-branch transfer request is created with status "pending"
- **Who Gets Notified:**
  - System Administrators (`system_admin`)
  - Central Administrators (`central_admin`)
  - Branch Managers of both source and destination branches
- **Notification Message:** "Transfer request [TRANSFER_NUMBER] from [FROM_BRANCH] to [TO_BRANCH] requires approval"
- **Link:** Direct link to view the transfer request

## How to View Notifications

1. **Notification Bell Icon:** Located in the top right navigation bar
2. **Badge Count:** Shows the number of unread notifications (red badge)
3. **Dropdown Menu:** Click the bell icon to see:
   - List of unread notifications
   - Notification title and message
   - Time when notification was created
   - Direct link to the relevant page
   - "Mark all as read" option

## Notification Features

- **Auto-refresh:** Notifications refresh every 30 seconds
- **Click to View:** Clicking a notification marks it as read and takes you to the relevant page
- **Mark as Read:** Individual notifications are marked as read when clicked
- **Mark All as Read:** Option to mark all notifications as read at once
- **Color Coding:** 
  - `info` (blue) - Informational notifications
  - `warning` (yellow) - Action required notifications
  - `success` (green) - Success notifications
  - `danger` (red) - Urgent/error notifications

## Technical Implementation

### Files Modified:
1. **app/Libraries/NotificationService.php**
   - Added `sendDeliveryReceivingNotification()` method
   - Added `sendTransferApprovalNotification()` method
   - Enhanced existing notification methods

2. **app/Controllers/PurchaseRequestController.php**
   - Added notification when purchase request is created
   - Notifies admins for approval

3. **app/Controllers/DeliveryController.php**
   - Added notification when delivery is scheduled
   - Added notification when delivery status changes to "in_transit" or "delivered"
   - Notifies branch managers and inventory staff

4. **app/Controllers/TransferController.php**
   - Added notification when transfer request is created
   - Notifies admins and branch managers for approval

### Notification Database
- Notifications are stored in the `notifications` table
- Each notification includes:
  - `user_id` - Who should receive the notification
  - `type` - Notification type (info, warning, success, danger)
  - `title` - Notification title
  - `message` - Notification message
  - `link` - Direct link to relevant page
  - `is_read` - Read status (0 = unread, 1 = read)
  - `created_at` - When notification was created

## Testing the System

1. **Test Purchase Request Notification:**
   - Create a new purchase request as a branch manager
   - Login as system admin or central admin
   - Check notification bell - should show notification

2. **Test Delivery Notification:**
   - Create a delivery
   - Login as branch manager or inventory staff of the destination branch
   - Check notification bell - should show notification

3. **Test Transfer Notification:**
   - Create a transfer request
   - Login as system admin, central admin, or branch manager
   - Check notification bell - should show notification

## Future Enhancements

Potential improvements:
- Email notifications
- SMS notifications
- Push notifications for mobile
- Notification preferences per user
- Notification categories/filters
- Sound alerts for urgent notifications

---

**Last Updated:** Implementation completed
**Status:** ✅ Fully Functional

