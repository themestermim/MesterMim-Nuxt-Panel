<?php

namespace App\Http\Controllers\Experiences;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Carbon;

class ExperiencesController extends Controller
{

    public function index(Request $request) {

        // دریافت زبان از هدر و تنظیم 'fa' به عنوان مقدار پیش‌فرض
        $lang = $request->headers->get('lang');
//        dd($lang);

        if (!$lang) {
            return response()->json(ResponseHelper::response422(['message' => 'lang is required.']), 422);
        }

        // واکشی تجربیات و گروه‌بندی بر اساس type_id
        $experiences = Experience::where('lang', $lang)
            ->orderBy('arrival_date', 'desc') // مرتب‌سازی بر اساس تاریخ ورود (نزولی)
            ->get()
            ->groupBy('type_id'); // گروه‌بندی تجربیات بر اساس type_id

        // فرمت‌دهی تجربیات بر اساس زبان مورد نظر
        $formattedExperiences = [];
        foreach ($experiences as $typeId => $experienceGroup) {
            $formattedExperiences[] = $experienceGroup->first(); // فقط رکورد زبان مورد نظر را اضافه می‌کنیم
        }

        return response()->json(ResponseHelper::formatResponse(true, 200, $formattedExperiences), 200);
    }


    public function edit(Request $request) {

        if (!auth()->check()) {
            return response()->json(ResponseHelper::unAuthorize(), 401);
        }

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
            return response()->json(ResponseHelper::response422($validator->errors()), 422);
        }

        $validated = $validator->validated();

        foreach ($validated['experiences'] as $experienceData) {
            $uniqueTypeId = uniqid();

//            $arrivalDate = Carbon::createFromFormat('Y/m/d', $experienceData['arrival_date'])->format('Y-m-d');
//            $exitDate = Carbon::createFromFormat('Y/m/d', $experienceData['exit_date'])->format('Y-m-d');


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
                'type_id' => $uniqueTypeId,
                'company_name' => $experienceData['enCompanyName'],
                'arrival_date' => $experienceData['arrival_date'],
                'exit_date' => $experienceData['exit_date'] ?? null,
                'role' => $experienceData['enRole'],
                'lang' => 'en',
            ]);

            if (isset($experienceData['image'])) {
                $experienceEn->image = $imagePath;
            }

            $experienceEn->save();
        }

        return response()->json([
            'message' => 'Experiences updated successfully.'
        ], 200);
    }


    public function delete($id)
    {
        $experience = Experience::find($id);


        if (!$experience) {
            return response()->json(ResponseHelper::response422(['message' => 'Experience not found.']), 404);
        }

        $typeId = $experience->type_id;

        $experiences = Experience::where('type_id', $typeId)->get();

        foreach ($experiences as $exp) {
            if ($exp->image) {
                $imagePath = public_path('storage/' . $exp->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $exp->delete();
        }

        return response()->json(ResponseHelper::formatResponse(true, 200, ['message' => 'success']), 200);
    }

}
