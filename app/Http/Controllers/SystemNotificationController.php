<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemNotification;
use App\Processes\NotificationProcess;

class SystemNotificationController extends Controller
{

    protected $system_notification;
    protected $request;



    public function __construct($request = null)
    {
        $this->middleware(['auth']);
        $this->request = ($request) ? (object) $request : request();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', SystemNotification::class);

        $query = SystemNotification::selectedFields()
                    ->leftJoinViews()
                    ->withLimit()
                    ->orderBy('system_notifications.created_at', 'DESC');


        return $this->response($query);
    }

    public function store(Request $request, NotificationProcess $process, SystemNotification $sytem_notification)
    {
        $this->authorize('create', SystemNotification::class);

        $request->validate([

            'title'         =>  'required',
            'message'       =>  'required',
            'redirect_link' =>  'required',
            'to'            =>  'nullable',
            'warehouses'    =>  'required|array'
        ]);

         $process->create();


        activity()
            ->performedOn($sytem_notification)
            ->withProperties($sytem_notification)
            ->log('System Notification has been created');

        return [
            'success'   =>  1,
            'data'  => $process
        ];
    }


    public function update(Request $request,  SystemNotification $systemnotification)
    {

        // $this->authorize('update', $systemnotification);

        // $request->validate([
        //     'channel'       =>  'required',
        //     'title'         =>  'required',
        //     'message'       =>  'required',
        //     'redirect_link' =>  'required',
        //     'to'            =>  'required',
        //     'warehouse_id'  =>  'required'
        // ]);

        // $systemnotification->update([
        //     'channel'       =>  $request->channel,
        //     'title'         =>  strtoupper($request->title),
        //     'message'       =>  strtoupper($request->message),
        //     'redirect_link' =>  $request->redirect_link,
        //     'from'          =>  auth()->id(),
        //     'to'            =>  $request->to,
        //     'warehouse_id'  =>  $request->warehouse_id,
        // ]);

        // activity()
        //     ->performedOn($systemnotification)
        //     ->log('Notification has been update');

        return [
            'success' => 1,
            'data' => $systemnotification
        ];
    }

    public function destroy(SystemNotification $system_notification)
    {
        $this->authorize('delete', $system_notification);

        $system_notification->forceDelete();

        activity()
            ->performedOn($system_notification)
            ->log('Notification has been deleted');

        return [
            'success' => 1
        ];
    }

    public function listing()
    {
        $this->authorize('list', SystemNotification::class);

        $query = SystemNotification::selectedFields()
            ->joinWarehouse()
            ->leftjoinUser()
            ->orderBy('system_notifications.id', 'DESC')
            ->searchable()
            ->sortable();

        return $this->response($query);
    }
}
