<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDescriptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EditController extends Controller
{
    public function edit(Request $request) {

//        return response()->json($request->all());

        if (!auth()->check()) {
            return response()->json(ResponseHelper::unAuthorize(), 401);
        }

        $user = auth()->user();

        if(! $user) {
            return response()->json(ResponseHelper::unAuthorize(), 401);
        }

//        $this->validate($request, [
//            'name' => ['required'],
//            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
//            'fa_description' => ['required', 'string', 'min:20'],
//            'en_description' => ['required', 'string', 'min:20'],
//        ]);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'fa_description' => ['required', 'string', 'min:20'],
            'en_description' => ['required', 'string', 'min:20'],
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseHelper::formatResponse(false, 422, $validator->errors()), 422);
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image); // حذف عکس قبلی از storage
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $user->image = $imagePath;
        }

        $user->update([
            'name' => $request->name,
            'image' => $user->image, // استفاده از مقدار به‌روز شده‌ی $user->image
        ]);

        // ذخیره توضیحات فارسی
        UserDescriptions::updateOrCreate(
            ['user_id' => $user->id, 'lang' => 'fa'],
            ['short_description' => $request->fa_description]
        );

        // ذخیره توضیحات انگلیسی
        UserDescriptions::updateOrCreate(
            ['user_id' => $user->id, 'lang' => 'en'],
            ['short_description' => $request->en_description]
        );

        return response()->json(ResponseHelper::formatResponse(true, 200, ['message' => 'your profile changed']), 200, [], JSON_UNESCAPED_UNICODE);
    }
}
