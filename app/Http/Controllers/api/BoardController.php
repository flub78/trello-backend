<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Board;

class BoardController extends Controller
{
    //

    public function index()
    {
        $boards = Board::all();

        $data = [
            'status' => 200,
            'boards' => $boards,
        ];

        return response()->json($data, 200);
    }

    public function show($id)
    {
        $board = Board::find($id);

        $data = [
            'status' => 200,
            'board' => $board,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => '',
            'email' => 'email',
            'favorite' => 'required|boolean',
            'read_at' => 'date',
            'href' => 'url',
            'image' => '',
            'theme' => 'in:light,dark',
        ]);

        if ($validated->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validated->errors(),
                'message' => 'Validation failed',
            ];

            return response()->json($data, 422);
        }

        $board = new Board;
        $board->name = $request->name;
        $board->description = $request->description;
        $board->email = $request->email;
        $board->favorite = $request->favorite;
        $board->read_at = $request->read_at;
        $board->href = $request->href;
        $board->image = $request->image;
        $board->theme = $request->theme;
        $board->save();

        $data = [
            'status' => 200,
            'board' => $board,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $board = Board::find($id);

        $validated = $request->validate([
            'name' => 'required',
            'description' => '',
            'email' => 'email',
            'favorite' => 'required|boolean',
            'read_at' => 'date',
            'href' => 'url',
            'image' => '',
            'theme' => 'in:light,dark',
        ]);

        if ($validated->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validated->errors(),
                'message' => 'Validation failed',
            ];

            return response()->json($data, 422);
        }

        $board->name = $request->name;
        $board->description = $request->description;
        $board->email = $request->email;
        $board->favorite = $request->favorite;
        $board->read_at = $request->read_at;
        $board->href = $request->href;
        $board->image = $request->image;
        $board->theme = $request->theme;
        $board->save();

        $data = [
            'status' => 200,
            'board' => $board,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $board = Board::find($id);

        if (!$board) {
            $data = [
                'status' => 404,
                'message' => 'Board not found',
            ];

            return response()->json($data, 404);
        }

        $board->delete();

        $data = [
            'status' => 200,
            'message' => "Board $id deleted",
        ];

        return response()->json($data, 200);
    }

}
