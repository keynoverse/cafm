<?php

namespace App\Controllers;

use App\Models\Location;
use App\Models\Room;

class LocationController extends Controller
{
    protected $location;
    protected $room;

    public function __construct()
    {
        parent::__construct();
        $this->location = new Location();
        $this->room = new Room();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $locations = $this->location->search($query, $page, $perPage);
        } else {
            $locations = $this->location->getAllWithDetails($page, $perPage);
        }

        $stats = $this->location->getLocationStats();

        return $this->view('locations/index', [
            'locations' => $locations,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $location = $this->location->getWithDetails($id);

        if (!$location) {
            $this->redirect('locations');
        }

        $hierarchy = $this->location->getLocationHierarchy($id);

        return $this->view('locations/show', [
            'location' => $location,
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
                'room_id' => $_POST['room_id'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateLocation($data);
            if (!empty($errors)) {
                $rooms = $this->room->getActiveRooms();
                return $this->view('locations/create', [
                    'rooms' => $rooms,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->location->create($data)) {
                $this->setFlash('success', 'Location created successfully.');
                $this->redirect('locations');
            } else {
                $this->setFlash('error', 'Failed to create location.');
            }
        }

        $rooms = $this->room->getActiveRooms();
        return $this->view('locations/create', [
            'rooms' => $rooms
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $location = $this->location->getWithDetails($id);

        if (!$location) {
            $this->redirect('locations');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'room_id' => $_POST['room_id'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateLocation($data);
            if (!empty($errors)) {
                $rooms = $this->room->getActiveRooms();
                return $this->view('locations/edit', [
                    'location' => $location,
                    'rooms' => $rooms,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->location->update($id, $data)) {
                $this->setFlash('success', 'Location updated successfully.');
                $this->redirect("locations/{$id}");
            } else {
                $this->setFlash('error', 'Failed to update location.');
            }
        }

        $rooms = $this->room->getActiveRooms();
        return $this->view('locations/edit', [
            'location' => $location,
            'rooms' => $rooms
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $location = $this->location->getWithDetails($id);

        if (!$location) {
            $this->redirect('locations');
        }

        if ($this->location->delete($id)) {
            $this->setFlash('success', 'Location deleted successfully.');
        } else {
            $this->setFlash('error', 'Failed to delete location.');
        }

        $this->redirect('locations');
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $locations = $this->location->search($query, $page, $perPage);

        return $this->json([
            'locations' => $locations,
            'currentPage' => $page
        ]);
    }

    public function getLocationsByRoom()
    {
        $this->requireAuth();

        $roomId = $_GET['room_id'] ?? null;

        if (!$roomId) {
            return $this->json(['error' => 'Room ID is required'], 400);
        }

        $locations = $this->location->getLocationsByRoom($roomId);

        return $this->json(['locations' => $locations]);
    }

    protected function validateLocation($data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Please enter a name for the location.';
        }

        if (empty($data['room_id'])) {
            $errors['room_id'] = 'Please select a room.';
        }

        if (empty($data['type'])) {
            $errors['type'] = 'Please select a type for the location.';
        }

        return $errors;
    }
} 