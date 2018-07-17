<?php

/*
 * This file is part of the sysoce/laravel-translation package.
 *
 * (c) Sysoce <sysoce@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/sysoce/laravel-translation
 */

namespace Sysoce\Http\Controllers;

use Sysoce\Contracts\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'source' => 'required|string|max:255',
            'target' => 'required|string|max:255',
            'input'  => 'required|string',
            'text'   => 'nullable|string'
        ]);

        $data = $request->only('source', 'target', 'input', 'text');

        $string = implode($request->only('source', 'target', 'input'));
        $hash_id = Translation::generateHash($string);
        $translation = Translation::where('hash_id', $hash_id)->first();

        if(!$translation) {
            if(!isset($data['text'])) {
                $result = app('App\Contracts\Translator')->translate(
                    $data['input'],
                    $request->only('source', 'target')
                );
                if(empty($result['text'])) {
                    return Response::json(['error' => 'Translation failed.'], 500);
                }
                $data['text'] = $result['text'];
            }
            $translation = Translation::create($data);
        }

        return response()->json([
            'model'  => $translation
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Sysoce\Contracts\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function show(Translation $translation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Sysoce\Contracts\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function edit(Translation $translation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Sysoce\Contracts\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Translation $translation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Sysoce\Contracts\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Translation $translation)
    {
        //
    }
}
