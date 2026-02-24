<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Services\SubjectService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $subjectService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjects = $this->subjectService->getList($request->all());
        return $this->respondSuccess(SubjectResource::collection($subjects));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $subject = $this->subjectService->createSubject($request -> validated());
        return $this->respondCreated(new SubjectResource($subject), "Subject created successfully.");

    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, $id): Response
    {
        $subject = $this->subjectService->updateSubject($id, $request -> validated());
        return $this->respondSuccess(new SubjectResource($subject), "Subject updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
