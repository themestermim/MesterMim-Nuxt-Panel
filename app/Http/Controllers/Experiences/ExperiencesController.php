<?php

namespace App\Http\Controllers\Experiences;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperiencesController extends Controller
{
    public function edit(Request $request) {

        if (!auth()->check()) {
            return response()->json(ResponseHelper::unAuthorize(), 401);
        }

//        return response()->json($request->all(), 200);

        $validator = Validator::make($request->all(), [
            'experiences' => ['required', 'array'],
            'experiences.*.faCompanyName' => ['required'],
            'experiences.*.enCompanyName' => ['required'],
            'experiences.*.arrival_date' => ['required'],
            'experiences.*.exit_date' => ['required'],
            'experiences.*.faRole' => ['required'],
            'experiences.*.enRole' => ['required'],
            'experiences.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        foreach ($validated['experiences'] as $experienceData) {
            $uniqueTypeId = uniqid();


            $experienceFa = new Experience([
                'type_id' => $uniqueTypeId,
                'company_name' => $experienceData['faCompanyName'],
                'arrival_date' => $experienceData['arrival_date'],
                'exit_date' => $experienceData['exit_date'] ?? null,
                'role' => $experienceData['faRole'],
                'lang' => 'fa',
            ]);

            if (isset($experienceData['image'])) {
                $imagePath = $experienceData['image']->store('images', 'public');
                $experienceFa->image = $imagePath;
            }


            $experienceFa->save();

            $experienceEn = new Experience([
                'type_id' => $uniqueTypeId, // همان شناسه برای زبان انگلیسی
                'company_name' => $experienceData['enCompanyName'],
                'arrival_date' => $experienceData['arrival_date'],
                'exit_date' => $experienceData['exit_date'] ?? null,
                'role' => $experienceData['enRole'],
                'lang' => 'en',
            ]);

            if (isset($experienceData['image'])) {
                $experienceEn->image = $imagePath; // همان مسیر تصویر استفاده شده
            }

            $experienceEn->save();
        }

        return response()->json([
            'message' => 'Experiences updated successfully.'
        ], 200);
    }
}
