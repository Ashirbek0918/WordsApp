<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unverified\UnverifiedUpdateRequest;
use App\Models\User;
use App\Models\Word;
use App\Models\Unverified;
use Illuminate\Http\Request;
use App\Http\Resources\Words\UnverifiedWordsResource;
use App\Http\Requests\Unverified\UnverifiedWordsAddRequest;
use App\Http\Resources\Words\WordsResource;

class WordsController extends Controller
{
    public function create(UnverifiedWordsAddRequest $request)
    {
        $data = $request->validated();
        if (Word::where('word', $data['word'])->first()) {
            return ResponseController::error('Word already exists', 400);
        }
        Unverified::create([
            'user_id' => $data['user_id'],
            'word' => $data['word']
        ]);
        return ResponseController::success('Word added successfully', 201);
    }

    public function uncheckedWords(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $checkedAndOwnerWords = $user->checkedwords()->pluck('unverified_id')
            ->merge($user->unverifiedwords()->pluck('id'))->toArray();

        $words = Unverified::whereNotIn('id', $checkedAndOwnerWords)
            ->where('type', 'unverified')
            ->get();
        return response()->json([
            'success' => true,
            'total' => $words->count(),
            'data' => UnverifiedWordsResource::collection($words)
        ]);
    }

    public function mywords()
    {
        $user = User::findOrFail(auth()->user()->id);
        // return $user;
        return response()->json([
            'success' => true,
            'total' => $user->unverifiedwords()->count(),
            'data' =>WordsResource::collection($user->unverifiedwords)
        ]);
        
    }

    public function delete($id){
        $unverified  = Unverified::findOrFail($id);
        if($unverified){
            $unverified->checkusers()->delete();
            $unverified->delete();
            return response()->json([
                'success' => true,
                'message' => 'Word deleted successfully'
             ]);
        }
        return response()->json([
            'success' => false,
           'message' => 'Word not found'
        ],404);
        
    }

    public function update(UnverifiedUpdateRequest $request, $id){
        $user = User::findOrFail(auth()->user()->id);
        $data = $request->validated();
        $unverified = Unverified::findOrFail($id);
        if($unverified && $user->id == $unverified->user_id){
            if($request->has('word')){
                $unverified->update([
                    'word' => $data['word']
                ]);
            }
            return ResponseController::success('Successfully updated',200);
        }
        return response()->json([
           'success' => false,
           'message' => 'Word not found'
        ],404);
    }

    public function usersWithWords(){
        $users = User::with(['unverifiedwords','checkedwords'])->get();
        $users->each(function($user){
            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'words' => $user?->unverifiedwords()->count(),
                'checked_words' => $user?->checkedwords()->count()
            ];
        });
        return response()->json([
           'success' => true,
            'total' => $users?->count(),
            'data' => $users
        ]);
    }
}
