<?php

namespace App\Http\Controllers\JobOffer;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobOffer\JobOfferRequest;
use App\Models\JobOffer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JobOfferController extends Controller
{
    public function index(Request $request): View
    {
        $jobOffers = $request->user()->jobOffers()->latest()->get();

        return view('job-offers.index', compact('jobOffers'));
    }

    public function create(): View
    {
        return view('job-offers.create');
    }

    public function store(JobOfferRequest $request): RedirectResponse
    {
        $jobOffer = $request->user()->jobOffers()->create($request->validated());

        return Redirect::route('job-offers.show', $jobOffer)->with('status', 'job-offer-created');
    }

    public function show(Request $request, JobOffer $jobOffer): View
    {
        $this->authorizeOwnership($request, $jobOffer);

        return view('job-offers.show', compact('jobOffer'));
    }

    public function edit(Request $request, JobOffer $jobOffer): View
    {
        $this->authorizeOwnership($request, $jobOffer);

        return view('job-offers.edit', compact('jobOffer'));
    }

    public function update(JobOfferRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        $this->authorizeOwnership($request, $jobOffer);

        $jobOffer->update($request->validated());

        return Redirect::route('job-offers.show', $jobOffer)->with('status', 'job-offer-updated');
    }

    public function destroy(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        $this->authorizeOwnership($request, $jobOffer);

        $jobOffer->delete();

        return Redirect::route('job-offers.index')->with('status', 'job-offer-deleted');
    }
}
