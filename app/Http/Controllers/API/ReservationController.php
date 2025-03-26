<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Session;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class ReservationController extends Controller
{
    protected $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function index()
    {
        $reservations = $this->reservationRepository->all();
        return response()->json([
            'status' => true,
            'data' => $reservations
        ]);
    }

    public function show($id)
    {
        $reservation = $this->reservationRepository->find($id);
        if (!$reservation) {
            return response()->json([
                'status' => false,
                'message' => 'Reservation not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $reservation
        ]);
    }

    public function store(Request $request, $session_id, $seat_ids)
    {
        try {
            // Check if session exists
            if (!$this->reservationRepository->checkSessionExists($session_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Convert comma-separated seat IDs to array
            $seatIdsArray = explode(',', $seat_ids);

            // Check if seats exist
            $seats = $this->reservationRepository->getSeatsByIds($seatIdsArray);
            if (count($seats) !== count($seatIdsArray)) {
                return response()->json([
                    'status' => false,
                    'message' => 'One or more seats not found'
                ], 404);
            }

            // Check if seats are available
            if (!$this->reservationRepository->areSeatsAvailable($seatIdsArray, $session_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'One or more seats are already reserved'
                ], 400);
            }

            // Create reservation
            $reservation = $this->reservationRepository->create([
                'user_id' => Auth::id(),
                'session_id' => $session_id,
                'seats' => $seatIdsArray,
                'status' => 'pending'
            ]);

            // Generate PayPal payment link
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $total = count($seatIdsArray) * $reservation->session->price;

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $total
                        ],
                        "description" => "Movie Tickets Reservation #" . $reservation->id
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('payment.cancel', $reservation->id),
                    "return_url" => route('payment.success', $reservation->id)
                ]
            ]);

            // Add payment link to response
            $reservation->payment_link = $order['links'][1]['href'];

            return response()->json([
                'status' => true,
                'message' => 'Reservation created successfully',
                'data' => $reservation,
                'payment_link' => $order['links'][1]['href']
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $result = $this->reservationRepository->update($id, $request->all());
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = $this->reservationRepository->delete($id);
        return response()->json($result);
    }

    public function getUserReservations($userId)
    {
        $reservations = $this->reservationRepository->getUserReservations($userId);
        return response()->json([
            'status' => true,
            'data' => $reservations
        ]);
    }

    public function getReservationSeats($id)
    {
        $seats = $this->reservationRepository->getReservationSeats($id);
        return response()->json([
            'status' => true,
            'data' => $seats
        ]);
    }

    public function handlePaymentSuccess(Request $request, $reservation_id)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $order = $provider->capturePaymentOrder($request->token);

            if ($order['status'] === "COMPLETED") {
                // Update reservation status
                $this->reservationRepository->update($reservation_id, [
                    'status' => 'paid',
                    'payment_id' => $order['id']
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Payment successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Payment failed'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error processing payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handlePaymentCancel($reservation_id)
    {
        try {
            // Update reservation status to cancelled
            $this->reservationRepository->update($reservation_id, [
                'status' => 'cancelled'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment cancelled'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error cancelling payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
