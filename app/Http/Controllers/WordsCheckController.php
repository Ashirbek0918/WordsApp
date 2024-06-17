<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Words\CheckWordsRequest;
use App\Models\Unverified;
use App\Models\UserCheck;

class WordsCheckController extends Controller
{
    public function checkWords(CheckWordsRequest $request){
        $user = User::findOrFail(auth()->user()->id);
        $data = $request->validated();
        $checkedwords = $user->checkedwords()->where('unverified_id',$data['unverified_id'])->first();
        if(Unverified::findOrFail($data['unverified_id'])->user_id == $user->id){
            return response()->json([
               'success' => false,
               'message' => 'You cannot check your own words'
            ]);
        }
        if(!$checkedwords ){
            $user->checkedwords()->create([
                'user_id' => $user->id,
                'unverified_id' => $data['unverified_id'],
                'type' => $data['type']
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Checked'
            ]);
        }
        return response()->json([
           'success' => false,
           'message' => 'Already checked'
        ]);
    }
}
