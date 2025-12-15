<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function getUnreadCount()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['count' => 0]);
        }

        $count = $this->notificationModel->getUnreadCount($session->get('user_id'));
        return $this->response->setJSON(['count' => $count]);
    }

    public function getUnread()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['notifications' => []]);
        }

        // Get recent notifications (both read and unread for display)
        // Order by: unread first, then by created_at DESC (newest first)
        $notifications = $this->notificationModel->where('user_id', $session->get('user_id'))
            ->orderBy('is_read', 'ASC')  // Unread (0) before read (1)
            ->orderBy('created_at', 'DESC')  // Then newest first
            ->limit(10)
            ->findAll();
            
        return $this->response->setJSON(['notifications' => $notifications]);
    }

    public function markAsRead($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $notification = $this->notificationModel->find($id);
        if ($notification && $notification['user_id'] == $session->get('user_id')) {
            $this->notificationModel->update($id, ['is_read' => 1]);
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['error' => 'Notification not found'])->setStatusCode(404);
    }

    public function markAllAsRead()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $this->notificationModel->where('user_id', $session->get('user_id'))
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Order by: unread first, then by created_at DESC (newest first)
        $notifications = $this->notificationModel->where('user_id', $session->get('user_id'))
            ->orderBy('is_read', 'ASC')  // Unread (0) before read (1)
            ->orderBy('created_at', 'DESC')  // Then newest first
            ->findAll();

        return view('notifications/index', [
            'notifications' => $notifications,
            'title' => 'Notifications'
        ]);
    }

    public function getAll()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['notifications' => []]);
        }

        // Order by: unread first, then by created_at DESC (newest first)
        $notifications = $this->notificationModel->where('user_id', $session->get('user_id'))
            ->orderBy('is_read', 'ASC')  // Unread (0) before read (1)
            ->orderBy('created_at', 'DESC')  // Then newest first
            ->limit(20)
            ->findAll();
            
        return $this->response->setJSON(['notifications' => $notifications]);
    }

    /**
     * Clean up duplicate notifications for current user
     */
    public function cleanupDuplicates()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $userId = $session->get('user_id');
        
        // Get all notifications for user
        $notifications = $this->notificationModel->where('user_id', $userId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
        
        $seen = [];
        $duplicateIds = [];
        
        foreach ($notifications as $notif) {
            // Create unique key based on title + message
            $key = $notif['title'] . '|' . $notif['message'];
            
            if (isset($seen[$key])) {
                // This is a duplicate - mark for deletion
                $duplicateIds[] = $notif['id'];
            } else {
                $seen[$key] = $notif['id'];
            }
        }
        
        // Delete duplicates
        if (!empty($duplicateIds)) {
            $this->notificationModel->whereIn('id', $duplicateIds)->delete();
        }
        
        return $this->response->setJSON([
            'success' => true,
            'deleted' => count($duplicateIds)
        ]);
    }
}

