# Requirements Document

## Introduction

This feature enhances the existing notification system to provide real-time, role-based notifications with "Mark as Read" functionality. The system will display relevant notifications based on user roles (central_admin, branch_manager, branch_staff, supplier) and allow users to mark notifications as read, which will update the notification badge accordingly.

## Glossary

- **Notification**: A system-generated message alerting users about relevant events
- **Notification Badge**: A visual indicator (red dot) showing unread notification count
- **Role**: User permission level (central_admin, branch_manager, branch_staff, supplier)
- **Mark as Read**: Action to indicate a notification has been viewed
- **Central Admin**: Administrator with full system access
- **Branch Manager**: Manager of a specific branch location
- **Branch Staff**: Staff member working at a branch
- **Supplier**: External vendor providing products

## Requirements

### Requirement 1

**User Story:** As a user, I want to see my unread notifications in the header dropdown, so that I can stay informed about relevant system events.

#### Acceptance Criteria

1. WHEN a user clicks the notification bell icon THEN the System SHALL display a dropdown with the user's unread notifications fetched from the database
2. WHEN notifications are loaded THEN the System SHALL display each notification with its title, message, icon, and relative timestamp
3. WHEN the user has unread notifications THEN the System SHALL display a red badge indicator on the bell icon
4. WHEN the user has zero unread notifications THEN the System SHALL hide the red badge indicator

### Requirement 2

**User Story:** As a user, I want to mark notifications as read, so that I can track which notifications I have already seen.

#### Acceptance Criteria

1. WHEN a user clicks on a notification item THEN the System SHALL mark that notification as read and navigate to the related link
2. WHEN a user clicks "Mark all as read" THEN the System SHALL mark all unread notifications as read
3. WHEN a notification is marked as read THEN the System SHALL update the badge count immediately without page refresh
4. WHEN all notifications are marked as read THEN the System SHALL hide the red badge indicator

### Requirement 3

**User Story:** As a central admin, I want to receive notifications relevant to my administrative role, so that I can monitor and manage system-wide activities.

#### Acceptance Criteria

1. WHEN a new purchase request is created by any branch THEN the System SHALL create a notification for central admin users only
2. WHEN a purchase order status changes (sent, confirmed, delivered) THEN the System SHALL create a notification for central admin users only
3. WHEN a low stock alert is triggered at any branch THEN the System SHALL create a notification for central admin users only
4. WHEN a new franchise application is submitted THEN the System SHALL create a notification for central admin users only
5. WHEN a delivery is completed at any branch THEN the System SHALL create a notification for central admin users only
6. WHEN a new branch is created THEN the System SHALL create a notification for central admin users only
7. WHEN a new user account is created THEN the System SHALL create a notification for central admin users only

### Requirement 4

**User Story:** As a branch manager, I want to receive notifications about my specific branch activities only, so that I can manage my branch effectively.

#### Acceptance Criteria

1. WHEN a purchase request for the manager's branch is approved or rejected THEN the System SHALL create a notification for that branch manager only
2. WHEN a delivery arrives at the manager's branch THEN the System SHALL create a notification for that branch manager only
3. WHEN a transfer involves the manager's branch (incoming or outgoing) THEN the System SHALL create a notification for that branch manager only
4. WHEN inventory at the manager's branch reaches low stock level THEN the System SHALL create a notification for that branch manager only
5. WHEN a purchase order for the manager's branch is created THEN the System SHALL create a notification for that branch manager only

### Requirement 5

**User Story:** As a branch staff member, I want to receive notifications about tasks at my specific branch only, so that I can respond promptly to my duties.

#### Acceptance Criteria

1. WHEN a delivery arrives at the staff's branch THEN the System SHALL create a notification for branch staff at that branch only
2. WHEN a transfer is ready for pickup or delivery at the staff's branch THEN the System SHALL create a notification for branch staff at that branch only
3. WHEN inventory at the staff's branch needs attention THEN the System SHALL create a notification for branch staff at that branch only

### Requirement 6

**User Story:** As a supplier, I want to receive notifications about orders for my products only, so that I can fulfill them promptly.

#### Acceptance Criteria

1. WHEN a purchase order is sent to the supplier THEN the System SHALL create a notification for that specific supplier user only
2. WHEN a purchase order for the supplier is confirmed THEN the System SHALL create a notification for that specific supplier user only
3. WHEN payment is received for the supplier's order THEN the System SHALL create a notification for that specific supplier user only
4. WHEN a purchase order for the supplier is marked as delivered THEN the System SHALL create a notification for that specific supplier user only

### Requirement 7

**User Story:** As a user, I want to view all my notifications in a dedicated page, so that I can review my notification history.

#### Acceptance Criteria

1. WHEN a user clicks "View all notifications" THEN the System SHALL navigate to a notifications page showing all notifications
2. WHEN viewing the notifications page THEN the System SHALL display notifications sorted by date with newest first
3. WHEN viewing the notifications page THEN the System SHALL visually distinguish between read and unread notifications
