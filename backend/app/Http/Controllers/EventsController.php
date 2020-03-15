<?php


namespace App\Http\Controllers;


use App\Models\Events;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    
    public function save(Request $request)
    {
        try {
            
            $service = new EventService(new Events());
            $service->save($request);
            
            return JsonResponse::create([
                'success' => true,
                'message' => 'Successfully saved Event!',
                'data'    => $request->all(),
            ]);
            
        } catch (\Exception $e) {
            return JsonResponse::create([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
