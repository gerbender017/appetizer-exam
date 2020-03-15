<?php


namespace App\Services;


use App\Models\Events;
use Illuminate\Http\Request;

class EventService
{
    
    public $event;
    
    public function __construct(Events $event)
    {
        $this->event = $event;
    }
    
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Exception
     */
    public function save(Request $request)
    {
        
        $days = json_encode($request->days);
        
        $this->event->days       = $days;
        $this->event->event_name = $request->event_name;
        $this->event->date_to    = $request->date_to;
        $this->event->date_from  = $request->date_from;
        
        if (!$this->event->save()) {
            throw new \Exception('Unable to Save Event!', 500);
        }
    }
}
