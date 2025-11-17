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

        $notifications = $this->notificationModel->getUnreadNotifications($session->get('user_id'), 10);
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

        $notifications = $this->notificationModel->where('user_id', $session->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('notifications/index', ['notifications' => $notifications]);
    }
}

