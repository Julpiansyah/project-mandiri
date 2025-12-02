<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    /**
     * TAMPILKAN SEMUA EVENT (HOME)
     * Digunakan oleh route: GET /
     */
    public function index()
    {
        $events = Event::all();
        return view('home', compact('events'));
    }

    /**
     * TAMPILKAN SEMUA EVENT - ADMIN TABLE (ADMIN)
     * Digunakan oleh route: GET /admin/events
     */
    public function adminIndex()
    {
        $events = Event::all();
        return view('admin.event.index', compact('events'));
    }

    /**
     * TAMPILKAN SEMUA EVENT (EVENTS PAGE)
     * Digunakan oleh route: GET /events
     */
    public function events()
    {
        $events = Event::all();
        return view('events', compact('events'));
    }

    /**
     * DETAIL EVENT (USER)
     * Digunakan oleh route: GET /events/{id}
     */
    public function detail($id)
    {
        $event = Event::findOrFail($id);
        return view('schedules.detail-konser', compact('event'));
    }

    /**
     * Redirect ke halaman pembayaran
     */
    public function buy($id)
    {
        return redirect()->route('payment.create', $id);
    }

    /**
     * FORM TAMBAH EVENT (ADMIN)
     */
    public function create()
    {
        return view('admin.event.create');
    }

    /**
     * STORE EVENT BARU (ADMIN)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|min:3',
            'description' => 'required|min:10',
            'location'    => 'required',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // upload gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = Str::random(6) . '-event.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs('events', $imageName, 'public');
        }

        Event::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'image'       => $imagePath,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    /**
     * FORM EDIT EVENT (ADMIN)
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.event.edit', compact('event'));
    }

    /**
     * UPDATE EVENT (ADMIN)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|min:3',
            'description' => 'required|min:10',
            'location'    => 'required',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $event = Event::findOrFail($id);

        // update gambar
        if ($request->hasFile('image')) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $event->image = $request->file('image')->store('events', 'public');
        }

        $event->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'image'       => $event->image,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * HAPUS EVENT (ADMIN)
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }

    /**
     * TRASH / DATA TERHAPUS
     */
    public function trash()
    {
        $eventsTrash = Event::onlyTrashed()->get();
        return view('admin.event.trash', compact('eventsTrash'));
    }

    /**
     * RESTORE EVENT
     */
    public function restore($id)
    {
        $event = Event::onlyTrashed()->findOrFail($id);
        $event->restore();

        return redirect()->route('admin.events.trash')->with('success', 'Event berhasil dikembalikan!');
    }

    /**
     * HAPUS PERMANEN
     */
    public function deletePermanent($id)
    {
        $event = Event::onlyTrashed()->findOrFail($id);

        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->forceDelete();
        return redirect()->route('admin.events.trash')->with('success', 'Event berhasil dihapus permanen!');
    }

    /**
     * EXPORT KE EXCEL
     */
    public function export()
    {
        return Excel::download(new EventsExport, 'events.xlsx');
    }
}
