<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Contact;
use Illuminate\Http\Request;
use App\Services\ContactService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\Contact as ContactResource;

class ContactController extends Controller
{
    /**
     * Inject needed Services
     * @param ContactService $contactService
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        $user = Auth::user();

        // Save on Active Campaign saving creating locally
        $acResponse = $this->contactService->sendActiveCampaignSync($request->input());
        
        if (!(int)$acResponse->success) {
            return response()->json([
                'errors'    => [],
                'message'   => 'An error occurred. Please refresh your page and try again.'
            ], 502);
        } else {
            $newContact = $this->contactService->create($user, array_merge(
                $request->input(),
                ['active_campaign_id' => $acResponse->subscriber_id]
            ));

            return new ContactResource($newContact);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);
        
        // Save on Active Campaign saving creating locally
        $acResponse = $this->contactService->sendActiveCampaignSync($request->input());

        if (!(int)$acResponse->success) {
            return response()->json([
                'errors'    => [],
                'message'   => 'An error occurred. Please refresh your page and try again.'
            ], 502);
        } else {
            $updatedContact = $this->contactService->update($contact, array_merge(
                $request->input(),
                ['active_campaign_id' => $acResponse->subscriber_id]
            ));

            return new ContactResource($updatedContact);
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
