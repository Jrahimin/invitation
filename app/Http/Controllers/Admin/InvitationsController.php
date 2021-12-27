<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Requests\Admin\StoreImportInvitationsRequest;
use App\Invitation;
use App\Mail\InvitationSend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationsRequest;
use App\Http\Requests\Admin\UpdateInvitationsRequest;
use Illuminate\Support\Facades\Mail;

class InvitationsController extends Controller
{
    /**
     * Display a listing of Invitation.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('invitation_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (!Gate::allows('invitation_delete')) {
                return abort(401);
            }
            $invitations = Invitation::onlyTrashed()->get();
        } else {
            $invitations = Invitation::all();
        }

        return view('admin.invitations.index', compact('invitations'));
    }

    /**
     * Show the form for creating new Invitation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('invitation_create')) {
            return abort(401);
        }

        $events = \App\Event::get()->pluck('name', 'id')->prepend(trans('global.app_please_select'), '');

        return view('admin.invitations.create', compact('events'));
    }

    /**
     * Show the form for import new Invitation.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        if (!Gate::allows('invitation_create')) {
            return abort(401);
        }

        $events = \App\Event::get()->pluck('name', 'id')->prepend(trans('global.app_please_select'), '');

        return view('admin.invitations.import', compact('events'));
    }

    /**
     * Store a newly created Invitation in storage.
     *
     * @param \App\Http\Requests\StoreInvitationsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvitationsRequest $request)
    {
        if (!Gate::allows('invitation_create')) {
            return abort(401);
        }

        $event = Event::findOrFail($request->event_id);

        DB::beginTransaction();

        Invitation::create($request->all());
        $event->increment('total_guest', $request->people_count);

        DB::commit();


        return redirect()->route('admin.invitations.index');
    }

    /**
     * Store a newly created Invitation in storage.
     *
     * @param \App\Http\Requests\StoreImportInvitationsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function import_store(StoreImportInvitationsRequest $request)
    {
        if (!Gate::allows('invitation_create')) {
            return abort(401);
        }

        $event = Event::findOrFail($request->event_id);

        $request->file('csv')->storeAs(
            'csv', 'import.csv'
        );

        $invitationList = [];
        $totalGuest = 0;
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $handle = fopen(storage_path() . '/app/csv/import.csv', "r");
        while ($csvLine = fgetcsv($handle, 1000, ",")) {
            if (strtolower($csvLine[0]) == 'name') {
                continue;
            }

            if(!$csvLine[0]){
                continue;
            }

            $invitationList[] = [
                'event_id' => $request->event_id,
                'name' => $csvLine[0],
                'mobile_number' => $csvLine[1],
                'email' => $csvLine[2],
                'people_count' => $csvLine[3],
                'relation' => $csvLine[4],
                'address' => $csvLine[5],
                'remark' => $csvLine[6],
                'created_at' => $today
            ];

            $totalGuest += $csvLine[3];
        }

        DB::beginTransaction();

        Invitation::insert($invitationList);
        $event->increment('total_guest', $totalGuest);

        DB::commit();

        return redirect()->route('admin.invitations.index');
    }

    /**
     * Show the form for editing Invitation.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('invitation_edit')) {
            return abort(401);
        }

        $events = \App\Event::get()->pluck('name', 'id')->prepend(trans('global.app_please_select'), '');

        $invitation = Invitation::findOrFail($id);

        return view('admin.invitations.edit', compact('invitation', 'events'));
    }

    /**
     * Update Invitation in storage.
     *
     * @param \App\Http\Requests\UpdateInvitationsRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvitationsRequest $request, $id)
    {
        if (!Gate::allows('invitation_edit')) {
            return abort(401);
        }

        $event = Event::findOrFail($request->event_id);
        $invitation = Invitation::findOrFail($id);
        $updatedCount = $event->total_guest - $invitation->people_count + $request->people_count;

        DB::beginTransaction();

        $invitation->update($request->all());
        $event->update(['total_guest' => $updatedCount]);

        DB::commit();

        return redirect()->route('admin.invitations.index');
    }


    /**
     * Display Invitation.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('invitation_view')) {
            return abort(401);
        }
        $invitation = Invitation::findOrFail($id);

        return view('admin.invitations.show', compact('invitation'));
    }

    /**
     * Send Invitation.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function send($id)
    {
        if (!Gate::allows('invitation_view')) {
            return abort(401);
        }
        $invitation = Invitation::findOrFail($id);
        Mail::to($invitation->email)->send(new InvitationSend($invitation));

        $invitation->update(['sent_at' => Carbon::now()->toDateTimeString()]);

        return redirect()->route('admin.invitations.index');
    }

    /**
     * Remove Invitation from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('invitation_delete')) {
            return abort(401);
        }
        $invitation = Invitation::findOrFail($id);

        DB::beginTransaction();

        $invitation->event->decrement('total_guest', $invitation->people_count);
        $invitation->delete();

        DB::commit();

        return redirect()->route('admin.invitations.index');
    }

    /**
     * Delete all selected Invitation at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('invitation_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $totalGuest = Invitation::whereIn('id', $request->input('ids'))->sum('people_count');
            $invitation = Invitation::whereIn('id', $request->input('ids'))->first();

            DB::beginTransaction();

            $invitation->event->decrement('total_guest', $totalGuest);
            Invitation::whereIn('id', $request->input('ids'))->delete();

            DB::commit();
        }
    }


    /**
     * Restore Invitation from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('invitation_delete')) {
            return abort(401);
        }
        $invitation = Invitation::onlyTrashed()->findOrFail($id);
        $invitation->restore();

        return redirect()->route('admin.invitations.index');
    }

    /**
     * Permanently delete Invitation from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('invitation_delete')) {
            return abort(401);
        }
        $invitation = Invitation::onlyTrashed()->findOrFail($id);
        $invitation->forceDelete();

        return redirect()->route('admin.invitations.index');
    }
}
