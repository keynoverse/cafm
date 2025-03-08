<?php

namespace App\Controllers;

use App\Models\Room;
use App\Models\Floor;

class RoomController extends Controller
{
    protected $room;
    protected $floor;

    public function __construct()
    {
        parent::__construct();
        $this->room = new Room();
        $this->floor = new Floor();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $rooms = $this->room->search($query, $page, $perPage);
        } else {
            $rooms = $this->room->getAllWithDetails($page, $perPage);
        }

        $stats = $this->room->getRoomStats();

        return $this->view('rooms/index', [
            'rooms' => $rooms,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $room = $this->room->getWithDetails($id);

        if (!$room) {
            $this->redirect('rooms');
        }

        $hierarchy = $this->room->getRoomHierarchy($id);

        return $this->view('rooms/show', [
            'room' => $room,
            'hierarchy' => $hierarchy
        ]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'floor_id' => $_POST['floor_id'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateRoom($data);
            if (!empty($errors)) {
                $floors = $this->floor->getActiveFloors();
                return $this->view('rooms/create', [
                    'floors' => $floors,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->room->create($data)) {
                $this->setFlash('success', 'Room created successfully.');
                $this->redirect('rooms');
            } else {
                $this->setFlash('error', 'Failed to create room.');
            }
        }

        $floors = $this->floor->getActiveFloors();
        return $this->view('rooms/create', [
            'floors' => $floors
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $room = $this->room->getWithDetails($id);

        if (!$room) {
            $this->redirect('rooms');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'floor_id' => $_POST['floor_id'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateRoom($data);
            if (!empty($errors)) {
                $floors = $this->floor->getActiveFloors();
                return $this->view('rooms/edit', [
                    'room' => $room,
                    'floors' => $floors,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->room->update($id, $data)) {
                $this->setFlash('success', 'Room updated successfully.');
                $this->redirect("rooms/{$id}");
            } else {
                $this->setFlash('error', 'Failed to update room.');
            }
        }

        $floors = $this->floor->getActiveFloors();
        return $this->view('rooms/edit', [
            'room' => $room,
            'floors' => $floors
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $room = $this->room->getWithDetails($id);

        if (!$room) {
            $this->redirect('rooms');
        }

        if ($this->room->delete($id)) {
            $this->setFlash('success', 'Room deleted successfully.');
        } else {
            $this->setFlash('error', 'Failed to delete room.');
        }

        $this->redirect('rooms');
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $rooms = $this->room->search($query, $page, $perPage);

        return $this->json([
            'rooms' => $rooms,
            'currentPage' => $page
        ]);
    }

    public function getRoomsByFloor()
    {
        $this->requireAuth();

        $floorId = $_GET['floor_id'] ?? null;

        if (!$floorId) {
            return $this->json(['error' => 'Floor ID is required'], 400);
        }

        $rooms = $this->room->getRoomsByFloor($floorId);

        return $this->json(['rooms' => $rooms]);
    }

    protected function validateRoom($data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Please enter a name for the room.';
        }

        if (empty($data['floor_id'])) {
            $errors['floor_id'] = 'Please select a floor.';
        }

        return $errors;
    }
} 