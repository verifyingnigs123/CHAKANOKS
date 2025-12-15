# Transfer Request Feature - Complete Implementation

## ✅ NEW FEATURE: Request Transfer

Added a new "Request Transfer" button that allows branches to REQUEST products FROM other branches (pull request instead of push transfer).

## Two Types of Transfers

### 1. Create Transfer (Push - Existing)
**Scenario:** Branch A wants to SEND products TO Branch B
- **From:** Branch A (has products)
- **To:** Branch B (will receive)
- **Initiated by:** Branch A
- **Flow:** A → B

**Example:** Main Branch sends 20 chickens to North Branch

---

### 2. Request Transfer (Pull - NEW)
**Scenario:** Branch B wants to REQUEST products FROM Branch A
- **From:** Branch A (has products)
- **To:** Branch B (requesting)
- **Initiated by:** Branch B
- **Flow:** B requests from A

**Example:** North Branch requests 20 chickens from Main Branch

---

## Complete Workflow

Both types follow the SAME workflow after creation:

### Step 1: Create/Request Transfer
**Who:** Branch Manager, Franchise Manager  
**Status:** `pending`  
**Actions:**
- **Create Transfer:** Select TO branch, products to send
- **Request Transfer:** Select FROM branch, products to request

**Notifications:**
- Central Admin: "New Transfer Request" (needs approval)
- Source Branch: "Transfer Request" (awareness)
- Destination Branch: "Incoming Transfer" (awareness)

---

### Step 2: Central Admin Approves
**Who:** Central Admin ONLY  
**Status:** `approved`  
**Action:** Review and approve

**Notifications:**
- All parties notified
- Logistics Coordinator: "Ready for scheduling"

---

### Step 3: Logistics Schedules (Optional)
**Who:** Central Admin, Logistics Coordinator  
**Status:** `scheduled`  
**Action:** Set scheduled date

---

### Step 4: Logistics Dispatches
**Who:** Central Admin, Logistics Coordinator  
**Status:** `in_transit`  
**Action:** Mark as dispatched  
**Inventory:** Source branch inventory DEDUCTED

---

### Step 5: Destination Receives
**Who:** Destination Branch Manager  
**Status:** `completed`  
**Action:** Receive and confirm  
**Inventory:** Destination branch inventory ADDED

---

## User Interface

### New Button
Located next to "Create Transfer" button:
```
[Create Transfer] [Request Transfer]
```

**Colors:**
- Create Transfer: Emerald (green) - sending out
- Request Transfer: Blue - requesting in

**Icons:**
- Create Transfer: `fa-plus` (add/send)
- Request Transfer: `fa-hand-holding` (requesting/receiving)

---

## Request Transfer Modal

### Features:
1. **Blue gradient header** (different from green Create Transfer)
2. **Info banner:** Explains you're requesting FROM another branch
3. **From Branch selector:** Select which branch has the products
4. **To Branch (readonly):** Shows your branch (auto-filled)
5. **Product selector:** Loads products from selected source branch
6. **Quantity inputs:** Specify how much you need
7. **Add/Remove products:** Multiple products in one request
8. **Notes field:** Optional request notes

### Validation:
- ✅ Checks if source branch has sufficient inventory
- ✅ Prevents requesting from same branch
- ✅ Requires at least one product
- ✅ Validates quantities

---

## Technical Implementation

### Files Modified:

1. **app/Views/transfers/index.php**
   - Added "Request Transfer" button
   - Added Request Transfer modal
   - Added JavaScript functions:
     - `openRequestModal()`
     - `closeRequestModal()`
     - `loadRequestBranchProducts()`
     - `addRequestProductRow()`
     - `removeRequestProductRow()`

2. **app/Controllers/TransferController.php**
   - Added `requestStore()` method
   - Same validation as regular transfer
   - Same notification workflow

3. **app/Config/Routes.php**
   - Added `POST /transfers/request-store` route

---

## Key Differences

### Create Transfer:
```
User selects:
- TO branch (destination)
- Products from THEIR inventory

Result: Sends products away
```

### Request Transfer:
```
User selects:
- FROM branch (source)
- Products from OTHER branch's inventory

Result: Requests products to come
```

---

## Use Cases

### When to use Create Transfer:
- You have excess inventory
- You want to help another branch
- You're redistributing stock
- Proactive sending

### When to use Request Transfer:
- You need products urgently
- You know another branch has stock
- You're running low
- Reactive requesting

---

## Example Scenarios

### Scenario 1: Create Transfer
```
Main Branch has 100 chickens
North Branch needs chickens
Main Branch creates transfer TO North Branch
→ Main Branch initiates the send
```

### Scenario 2: Request Transfer
```
Main Branch has 100 chickens
North Branch needs chickens
North Branch requests transfer FROM Main Branch
→ North Branch initiates the request
```

**Result:** Both create the same transfer record, just initiated by different parties!

---

## Benefits

✅ **Flexibility:** Branches can both push and pull inventory  
✅ **Efficiency:** Requesting branch can initiate without waiting  
✅ **Visibility:** Both branches aware of the transfer  
✅ **Same workflow:** No confusion, same approval process  
✅ **Inventory control:** Same validation and tracking  

---

## Testing

### Test Request Transfer:

1. **Login as Branch Manager (North Branch)**
2. **Click "Request Transfer" button**
3. **Select FROM branch:** Main Branch
4. **Select products** from Main Branch inventory
5. **Enter quantities**
6. **Submit request**
7. ✅ Transfer created with status `pending`
8. ✅ Central Admin receives notification
9. **Login as Central Admin**
10. **Approve the transfer**
11. **Login as Logistics**
12. **Schedule and dispatch**
13. **Login as North Branch Manager**
14. **Receive the transfer**
15. ✅ Inventory updated automatically

---

## Summary

The Request Transfer feature provides a complete "pull" mechanism to complement the existing "push" transfer system. Both types follow the same workflow and have the same inventory management, ensuring consistency and reliability throughout the system.

**Key Point:** Whether you CREATE a transfer or REQUEST a transfer, the end result is the same - products move from one branch to another with full tracking and automatic inventory updates!
