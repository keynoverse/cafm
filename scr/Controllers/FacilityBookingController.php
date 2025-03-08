<?php

namespace App\Controllers;

use App\Models\FacilityBooking;
use App\Models\Location;

class FacilityBookingController extends Controller
{
    protected $facilityBooking;
    protected $location;

    public function __construct()
    {
        parent::__construct();
        $this->facilityBooking = new FacilityBooking();
        $this->location = new Location();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $bookings = $this->facilityBooking->search($query, $page, $perPage);
        } else {
            $bookings = $this->facilityBooking->getAllWithDetails($page, $perPage);
        }

        $stats = $this->facilityBooking->getBookingStats();

        return $this->view('facility-bookings/index', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $booking = $this->facilityBooking->getWithDetails($id);

        if (!$booking) {
            $this->redirect('facility-bookings');
        }

        return $this->view('facility-bookings/show', [
            'booking' => $booking
        ]);
    }

    public function create()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'facility_id' => $_POST['facility_id'],
                'booked_by' => $this->user['id'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'purpose' => $_POST['purpose'],
                'status' => 'pending'
            ];

            // Validate input
            $errors = $this->validateBooking($data);
            if (!empty($errors)) {
                return $this->view('facility-bookings/create', [
                    'errors' => $errors,
                    'old' => $_POST,
                    'facilities' => $this->location->getAll()
                ]);
            }

            // Check availability
            if (!$this->facilityBooking->checkAvailability(
                $data['facility_id'],
                $data['start_time'],
                $data['end_time']
            )) {
                $errors['availability'] = 'The facility is not available for the selected time period.';
                return $this->view('facility-bookings/create', [
                    'errors' => $errors,
                    'old' => $_POST,
                    'facilities' => $this->location->getAll()
                ]);
            }

            if ($this->facilityBooking->create($data)) {
                $this->setFlash('success', 'Facility booking created successfully.');
                $this->redirect('facility-bookings');
            } else {
                $this->setFlash('error', 'Failed to create facility booking.');
            }
        }

        return $this->view('facility-bookings/create', [
            'facilities' => $this->location->getAll()
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();

        $booking = $this->facilityBooking->getWithDetails($id);

        if (!$booking) {
            $this->redirect('facility-bookings');
        }

        if ($booking['status'] !== 'pending') {
            $this->setFlash('error', 'Only pending bookings can be edited.');
            $this->redirect("facility-bookings/{$id}");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'facility_id' => $_POST['facility_id'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'purpose' => $_POST['purpose'],
                'status' => $_POST['status'],
                'approved_by' => $_POST['status'] === 'approved' ? $this->user['id'] : null
            ];

            // Validate input
            $errors = $this->validateBooking($data);
            if (!empty($errors)) {
                return $this->view('facility-bookings/edit', [
                    'booking' => $booking,
                    'errors' => $errors,
                    'old' => $_POST,
                    'facilities' => $this->location->getAll()
                ]);
            }

            // Check availability
            if (!$this->facilityBooking->checkAvailability(
                $data['facility_id'],
                $data['start_time'],
                $data['end_time'],
                $id
            )) {
                $errors['availability'] = 'The facility is not available for the selected time period.';
                return $this->view('facility-bookings/edit', [
                    'booking' => $booking,
                    'errors' => $errors,
                    'old' => $_POST,
                    'facilities' => $this->location->getAll()
                ]);
            }

            if ($this->facilityBooking->update($id, $data)) {
                $this->setFlash('success', 'Facility booking updated successfully.');
                $this->redirect("facility-bookings/{$id}");
            } else {
                $this->setFlash('error', 'Failed to update facility booking.');
            }
        }

        return $this->view('facility-bookings/edit', [
            'booking' => $booking,
            'facilities' => $this->location->getAll()
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $booking = $this->facilityBooking->getWithDetails($id);

        if (!$booking) {
            $this->redirect('facility-bookings');
        }

        if ($booking['status'] !== 'pending') {
            $this->setFlash('error', 'Only pending bookings can be deleted.');
            $this->redirect("facility-bookings/{$id}");
        }

        if ($this->facilityBooking->delete($id)) {
            $this->setFlash('success', 'Facility booking deleted successfully.');
        } else {
            $this->setFlash('error', 'Failed to delete facility booking.');
        }

        $this->redirect('facility-bookings');
    }

    public function updateStatus($id)
    {
        $this->requireAuth();

        $booking = $this->facilityBooking->getWithDetails($id);

        if (!$booking) {
            $this->redirect('facility-bookings');
        }

        $status = $_POST['status'] ?? '';
        $validStatuses = ['pending', 'approved', 'rejected', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            $this->setFlash('error', 'Invalid status.');
            $this->redirect("facility-bookings/{$id}");
        }

        // Only admins can approve/reject bookings
        if (in_array($status, ['approved', 'rejected']) && $this->user['role'] !== 'admin') {
            $this->setFlash('error', 'You do not have permission to perform this action.');
            $this->redirect("facility-bookings/{$id}");
        }

        $approvedBy = in_array($status, ['approved', 'rejected']) ? $this->user['id'] : null;

        if ($this->facilityBooking->updateStatus($id, $status, $approvedBy)) {
            $this->setFlash('success', 'Booking status updated successfully.');
        } else {
            $this->setFlash('error', 'Failed to update booking status.');
        }

        $this->redirect("facility-bookings/{$id}");
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $bookings = $this->facilityBooking->search($query, $page, $perPage);

        return $this->json([
            'bookings' => $bookings,
            'currentPage' => $page
        ]);
    }

    public function checkAvailability()
    {
        $this->requireAuth();

        $facilityId = $_GET['facility_id'] ?? '';
        $startTime = $_GET['start_time'] ?? '';
        $endTime = $_GET['end_time'] ?? '';
        $excludeId = $_GET['exclude_id'] ?? null;

        if (!$facilityId || !$startTime || !$endTime) {
            return $this->json([
                'available' => false,
                'message' => 'Missing required parameters.'
            ]);
        }

        $available = $this->facilityBooking->checkAvailability(
            $facilityId,
            $startTime,
            $endTime,
            $excludeId
        );

        return $this->json([
            'available' => $available,
            'message' => $available ? 'Facility is available.' : 'Facility is not available.'
        ]);
    }

    protected function validateBooking($data)
    {
        $errors = [];

        if (empty($data['facility_id'])) {
            $errors['facility_id'] = 'Please select a facility.';
        }

        if (empty($data['start_time'])) {
            $errors['start_time'] = 'Please select a start time.';
        }

        if (empty($data['end_time'])) {
            $errors['end_time'] = 'Please select an end time.';
        }

        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            $start = strtotime($data['start_time']);
            $end = strtotime($data['end_time']);

            if ($start >= $end) {
                $errors['end_time'] = 'End time must be after start time.';
            }

            if ($start < time()) {
                $errors['start_time'] = 'Start time cannot be in the past.';
            }
        }

        if (empty($data['purpose'])) {
            $errors['purpose'] = 'Please enter the purpose of the booking.';
        }

        return $errors;
    }
} 