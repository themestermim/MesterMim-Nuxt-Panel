<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\UserDescriptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EditController extends Controller
{
    public function edit(Request $request) {

        if (!auth()->check()) {
            return response()->json(ResponseHelper::unAuthorize(), 401);
        }

        $user = auth()->user();

        if(! $user) {
            return response()->json(ResponseHelper::unAuthorize(), 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'fa_description' => ['required', 'string', 'min:20'],
            'en_description' => ['required', 'string', 'min:20'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
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
            'image' => $user->image,
        ]);

        UserDescriptions::updateOrCreate(
            ['user_id' => $user->id, 'lang' => 'fa'],
            ['short_description' => $request->fa_description]
        );

        UserDescriptions::updateOrCreate(
            ['user_id' => $user->id, 'lang' => 'en'],
            ['short_description' => $request->en_description]
        );

//        $lang = $request->header('lang');

//        dd($lang);
        switch ($request->header('lang')) {
            case 'fa':
            case 'en':
                $description = UserDescriptions::where('user_id', auth()->id())
                    ->where('lang', $request->header('lang'))
                    ->first();

                return ResponseHelper::formatResponse(true, 200, new UserResource(auth()->user(), $description));

            default:
                return response()->json(ResponseHelper::langUnsupport(), 444);
        }
    }
}
