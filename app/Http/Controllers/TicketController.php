<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = \App\Models\Payment::with(['user', 'event'])->where('status', 'completed')->paginate(10);
        return view('admin.ticket.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    /**
     * Update ticket statuses based on event dates
     */
    public function updateStatuses()
    {
        $tickets = Ticket::with('event')->get();

        foreach ($tickets as $ticket) {
            $ticket->updateStatusBasedOnDate();
        }

        return redirect()->back()->with('success', 'Status tiket telah diperbarui berdasarkan tanggal event');
    }
}
